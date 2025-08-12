<?php

namespace App\Services\Web;

use App\Models\Web\Feature;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of features.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getFeatures(Request $request): LengthAwarePaginator
    {
        return Feature::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('description', "%{$search}%")
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
     * Create a new feature.
     *
     * @param array $data
     * @param Request $request
     * @return Feature
     * @throws Exception
     */
    public function createFeature(array $data, Request $request): Feature
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'feature');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $feature = new Feature($data);
            $feature->status = $data['status'] ?? true;

            $feature->attach = $this->uploadImage($request, 'attach', 'feature', 500, 280);

            if (auth()->check()) {
                $feature->created_by = auth()->id();
            }
            $feature->save();

            return $feature;
        });
    }

    /**
     * Find a feature by ID.
     *
     * @param int $id
     * @return Feature
     */
    public function findFeature(int $id): Feature
    {
        return Feature::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing feature.
     *
     * @param Feature $feature
     * @param array $data
     * @param Request $request
     * @return Feature
     * @throws Exception
     */
    public function updateFeature(Feature $feature, array $data, Request $request): Feature
    {
        return DB::transaction(function () use ($feature, $data, $request) {
            $feature->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'feature', 500, 280, $feature, 'attach');
            $feature->attach = $imageName;

            if (auth()->check()) {
                $feature->updated_by = auth()->id();
            }
            $feature->save();

            return $feature;
        });
    }

    /**
     * Soft delete a feature.
     *
     * @param Feature $feature
     * @return bool|null
     * @throws Exception
     */
    public function deleteFeature(Feature $feature): ?bool
    {
        return DB::transaction(fn() => $feature->delete());
    }

    /**
     * Restore a soft-deleted feature.
     *
     * @param string $id
     * @return Feature
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreFeature(string $id): Feature
    {
        return DB::transaction(function () use ($id) {
            $feature = Feature::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $feature->updated_by = auth()->id();
            }
            $feature->restore();

            return $feature;
        });
    }

    /**
     * Permanently delete a feature.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteFeature(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $feature = Feature::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('feature', $feature); // Delete associated image permanently
            return $feature->forceDelete();
        });
    }

    /**
     * Bulk soft delete features.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteFeatures(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Feature::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No features found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore features.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreFeatures(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Feature::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No features found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Feature::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Feature::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete features.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteFeatures(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $features = Feature::onlyTrashed()->whereIn('id', $ids)->get();
            if ($features->isEmpty()) {
                throw new ModelNotFoundException('No features found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($features as $item) {
                $this->deleteMedia('feature', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the feature trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyFeatureTrash(): int
    {
        return DB::transaction(function () {
            $trashedFeatures = Feature::onlyTrashed()->get();
            if ($trashedFeatures->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedFeatures as $feature) {
                $this->deleteMedia('feature', $feature);
                $feature->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of features.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateFeatureStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Feature::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No features found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single feature.
     *
     * @param Feature $feature
     * @return Feature
     * @throws Exception
     */
    public function toggleFeatureStatus(Feature $feature): Feature
    {
        return DB::transaction(function () use ($feature) {
            $feature->status = !$feature->status;
            if (auth()->check()) {
                $feature->updated_by = auth()->id();
            }
            $feature->save();
            return $feature;
        });
    }

    /**
     * Duplicate a feature.
     *
     * @param Feature $feature
     * @return Feature
     * @throws Exception
     */
    public function duplicateFeature(Feature $feature): Feature
    {
        return DB::transaction(function () use ($feature) {
            $duplicatedFeature = $feature->replicate();
            $duplicatedFeature->title = $feature->title . ' (Copy)';
            $duplicatedFeature->status = false;

            if (auth()->check()) {
                $duplicatedFeature->created_by = auth()->id();
                $duplicatedFeature->updated_by = null;
            }
            $duplicatedFeature->save();
            return $duplicatedFeature;
        });
    }

    /**
     * Get statistics for features.
     *
     * @return array
     */
    public function getFeatureStatistics(): array
    {
        return [
            'total' => Feature::query()->count(),
            'active' => Feature::query()->where('status', true)->count(),
            'inactive' => Feature::query()->where('status', false)->count(),
            'trashed' => Feature::onlyTrashed()->count(),
            'this_month' => Feature::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Feature::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Feature::query()->whereDate('created_at', today())->count(),
        ];
    }
}
