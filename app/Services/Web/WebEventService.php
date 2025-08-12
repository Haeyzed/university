<?php

namespace App\Services\Web;

use App\Helpers\SlugHelper;
use App\Models\Web\WebEvent;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebEventService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of web events.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getWebEvents(Request $request): LengthAwarePaginator
    {
        return WebEvent::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('description', "%{$search}%")
                    ->orWhereLike('address', "%{$search}%")
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', (bool) $request->status))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'title', 'date', 'status', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new web event.
     *
     * @param array $data
     * @param Request $request
     * @return WebEvent
     * @throws Exception
     */
    public function createWebEvent(array $data, Request $request): WebEvent
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'web-event');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $slug = SlugHelper::generateUniqueSlug($data['slug'] ?? $data['title'], WebEvent::class);

            $webEvent = new WebEvent($data);
            $webEvent->slug = $slug;
            $webEvent->status = $data['status'] ?? true;

            $webEvent->attach = $this->uploadImage($request, 'attach', 'web-event', 1200, 600);

            if (auth()->check()) {
                $webEvent->created_by = auth()->id();
            }
            $webEvent->save();

            return $webEvent;
        });
    }

    /**
     * Find a web event by ID or slug.
     *
     * @param int $id
     * @return WebEvent
     */
    public function findWebEvent(int $id): WebEvent
    {
        return WebEvent::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing web event.
     *
     * @param WebEvent $webEvent
     * @param array $data
     * @param Request $request
     * @return WebEvent
     * @throws Exception
     */
    public function updateWebEvent(WebEvent $webEvent, array $data, Request $request): WebEvent
    {
        return DB::transaction(function () use ($webEvent, $data, $request) {
            $slug = $data['slug'] ?? $data['title'];
            if ($slug !== $webEvent->slug) {
                $webEvent->slug = SlugHelper::generateUniqueSlug($slug, WebEvent::class, $webEvent->id);
            }

            $webEvent->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'web-event', 1200, 600, $webEvent, 'attach');
            $webEvent->attach = $imageName;

            if (auth()->check()) {
                $webEvent->updated_by = auth()->id();
            }
            $webEvent->save();

            return $webEvent;
        });
    }

    /**
     * Soft delete a web event.
     *
     * @param WebEvent $webEvent
     * @return bool|null
     * @throws Exception
     */
    public function deleteWebEvent(WebEvent $webEvent): ?bool
    {
        return DB::transaction(fn() => $webEvent->delete());
    }

    /**
     * Restore a soft-deleted web event.
     *
     * @param string $id
     * @return WebEvent
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreWebEvent(string $id): WebEvent
    {
        return DB::transaction(function () use ($id) {
            $webEvent = WebEvent::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $webEvent->updated_by = auth()->id();
            }
            $webEvent->restore();

            return $webEvent;
        });
    }

    /**
     * Permanently delete a web event.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteWebEvent(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $webEvent = WebEvent::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('web-event', $webEvent); // Delete associated image permanently
            return $webEvent->forceDelete();
        });
    }

    /**
     * Bulk soft delete web events.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteWebEvents(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = WebEvent::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No web events found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore web events.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreWebEvents(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = WebEvent::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No web events found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                WebEvent::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return WebEvent::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete web events.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteWebEvents(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $webEvents = WebEvent::onlyTrashed()->whereIn('id', $ids)->get();
            if ($webEvents->isEmpty()) {
                throw new ModelNotFoundException('No web events found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($webEvents as $item) {
                $this->deleteMedia('web-event', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the web event trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyWebEventTrash(): int
    {
        return DB::transaction(function () {
            $trashedWebEvents = WebEvent::onlyTrashed()->get();
            if ($trashedWebEvents->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedWebEvents as $webEvent) {
                $this->deleteMedia('web-event', $webEvent);
                $webEvent->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of web events.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateWebEventStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = WebEvent::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No web events found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single web event.
     *
     * @param WebEvent $webEvent
     * @return WebEvent
     * @throws Exception
     */
    public function toggleWebEventStatus(WebEvent $webEvent): WebEvent
    {
        return DB::transaction(function () use ($webEvent) {
            $webEvent->status = !$webEvent->status;
            if (auth()->check()) {
                $webEvent->updated_by = auth()->id();
            }
            $webEvent->save();
            return $webEvent;
        });
    }

    /**
     * Duplicate a web event.
     *
     * @param WebEvent $webEvent
     * @return WebEvent
     * @throws Exception
     */
    public function duplicateWebEvent(WebEvent $webEvent): WebEvent
    {
        return DB::transaction(function () use ($webEvent) {
            $duplicatedWebEvent = $webEvent->replicate();
            $duplicatedWebEvent->title = $webEvent->title . ' (Copy)';
            $duplicatedWebEvent->slug = SlugHelper::generateUniqueSlug($duplicatedWebEvent->title, WebEvent::class);
            $duplicatedWebEvent->status = false;

            if (auth()->check()) {
                $duplicatedWebEvent->created_by = auth()->id();
                $duplicatedWebEvent->updated_by = null;
            }
            $duplicatedWebEvent->save();
            return $duplicatedWebEvent;
        });
    }

    /**
     * Get statistics for web events.
     *
     * @return array
     */
    public function getWebEventStatistics(): array
    {
        return [
            'total' => WebEvent::query()->count(),
            'active' => WebEvent::query()->where('status', true)->count(),
            'inactive' => WebEvent::query()->where('status', false)->count(),
            'trashed' => WebEvent::onlyTrashed()->count(),
            'this_month' => WebEvent::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => WebEvent::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => WebEvent::query()->whereDate('created_at', today())->count(),
        ];
    }
}
