<?php

namespace App\Services\Web;

use App\Models\Web\Slider;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of sliders.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getSliders(Request $request): LengthAwarePaginator
    {
        return Slider::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('sub_title', "%{$search}%")
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', (bool) $request->status))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'title', 'status', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new slider.
     *
     * @param array $data
     * @param Request $request
     * @return Slider
     * @throws Exception
     */
    public function createSlider(array $data, Request $request): Slider
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'slider');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $slider = new Slider($data);
            $slider->status = $data['status'] ?? true;

            $slider->attach = $this->uploadImage($request, 'attach', 'slider', 1920, 850);

            if (auth()->check()) {
                $slider->created_by = auth()->id();
            }
            $slider->save();

            return $slider;
        });
    }

    /**
     * Find a slider by ID.
     *
     * @param int $id
     * @return Slider
     */
    public function findSlider(int $id): Slider
    {
        return Slider::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing slider.
     *
     * @param Slider $slider
     * @param array $data
     * @param Request $request
     * @return Slider
     * @throws Exception
     */
    public function updateSlider(Slider $slider, array $data, Request $request): Slider
    {
        return DB::transaction(function () use ($slider, $data, $request) {
            $slider->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'slider', 1920, 850, $slider, 'attach');
            $slider->attach = $imageName;

            if (auth()->check()) {
                $slider->updated_by = auth()->id();
            }
            $slider->save();

            return $slider;
        });
    }

    /**
     * Soft delete a slider.
     *
     * @param Slider $slider
     * @return bool|null
     * @throws Exception
     */
    public function deleteSlider(Slider $slider): ?bool
    {
        return DB::transaction(fn() => $slider->delete());
    }

    /**
     * Restore a soft-deleted slider.
     *
     * @param string $id
     * @return Slider
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreSlider(string $id): Slider
    {
        return DB::transaction(function () use ($id) {
            $slider = Slider::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $slider->updated_by = auth()->id();
            }
            $slider->restore();

            return $slider;
        });
    }

    /**
     * Permanently delete a slider.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteSlider(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $slider = Slider::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('slider', $slider); // Delete associated image permanently
            return $slider->forceDelete();
        });
    }

    /**
     * Bulk soft delete sliders.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteSliders(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Slider::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No sliders found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore sliders.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreSliders(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Slider::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No sliders found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Slider::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Slider::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete sliders.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteSliders(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $sliders = Slider::onlyTrashed()->whereIn('id', $ids)->get();
            if ($sliders->isEmpty()) {
                throw new ModelNotFoundException('No sliders found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($sliders as $item) {
                $this->deleteMedia('slider', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the slider trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptySliderTrash(): int
    {
        return DB::transaction(function () {
            $trashedSliders = Slider::onlyTrashed()->get();
            if ($trashedSliders->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedSliders as $slider) {
                $this->deleteMedia('slider', $slider);
                $slider->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of sliders.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateSliderStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Slider::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No sliders found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single slider.
     *
     * @param Slider $slider
     * @return Slider
     * @throws Exception
     */
    public function toggleSliderStatus(Slider $slider): Slider
    {
        return DB::transaction(function () use ($slider) {
            $slider->status = !$slider->status;
            if (auth()->check()) {
                $slider->updated_by = auth()->id();
            }
            $slider->save();
            return $slider;
        });
    }

    /**
     * Duplicate a slider.
     *
     * @param Slider $slider
     * @return Slider
     * @throws Exception
     */
    public function duplicateSlider(Slider $slider): Slider
    {
        return DB::transaction(function () use ($slider) {
            $duplicatedSlider = $slider->replicate();
            $duplicatedSlider->title = $slider->title . ' (Copy)';
            $duplicatedSlider->status = false;

            if (auth()->check()) {
                $duplicatedSlider->created_by = auth()->id();
                $duplicatedSlider->updated_by = null;
            }
            $duplicatedSlider->save();
            return $duplicatedSlider;
        });
    }

    /**
     * Get statistics for sliders.
     *
     * @return array
     */
    public function getSliderStatistics(): array
    {
        return [
            'total' => Slider::query()->count(),
            'active' => Slider::query()->where('status', true)->count(),
            'inactive' => Slider::query()->where('status', false)->count(),
            'trashed' => Slider::onlyTrashed()->count(),
            'this_month' => Slider::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Slider::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Slider::query()->whereDate('created_at', today())->count(),
        ];
    }
}
