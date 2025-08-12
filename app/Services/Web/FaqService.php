<?php

namespace App\Services\Web;

use App\Models\Web\Faq;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqService
{
    /**
     * Retrieve a paginated list of FAQs.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getFaqs(Request $request): LengthAwarePaginator
    {
        return Faq::query()
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
     * Create a new FAQ.
     *
     * @param array $data
     * @return Faq
     * @throws Exception
     */
    public function createFaq(array $data): Faq
    {
        return DB::transaction(function () use ($data) {
            $faq = new Faq($data);
            $faq->status = $data['status'] ?? true;

            if (auth()->check()) {
                $faq->created_by = auth()->id();
            }
            $faq->save();

            return $faq;
        });
    }

    /**
     * Find a FAQ by ID.
     *
     * @param int $id
     * @return Faq
     */
    public function findFaq(int $id): Faq
    {
        return Faq::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing FAQ.
     *
     * @param Faq $faq
     * @param array $data
     * @return Faq
     * @throws Exception
     */
    public function updateFaq(Faq $faq, array $data): Faq
    {
        return DB::transaction(function () use ($faq, $data) {
            $faq->fill($data);

            if (auth()->check()) {
                $faq->updated_by = auth()->id();
            }
            $faq->save();

            return $faq;
        });
    }

    /**
     * Soft delete a FAQ.
     *
     * @param Faq $faq
     * @return bool|null
     * @throws Exception
     */
    public function deleteFaq(Faq $faq): ?bool
    {
        return DB::transaction(fn() => $faq->delete());
    }

    /**
     * Restore a soft-deleted FAQ.
     *
     * @param string $id
     * @return Faq
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreFaq(string $id): Faq
    {
        return DB::transaction(function () use ($id) {
            $faq = Faq::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $faq->updated_by = auth()->id();
            }
            $faq->restore();

            return $faq;
        });
    }

    /**
     * Permanently delete a FAQ.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteFaq(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $faq = Faq::onlyTrashed()->findOrFail($id);
            return $faq->forceDelete();
        });
    }

    /**
     * Bulk soft delete FAQs.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteFaqs(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Faq::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No FAQs found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore FAQs.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreFaqs(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Faq::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No FAQs found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Faq::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Faq::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete FAQs.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteFaqs(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $faqs = Faq::onlyTrashed()->whereIn('id', $ids)->get();
            if ($faqs->isEmpty()) {
                throw new ModelNotFoundException('No FAQs found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($faqs as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the FAQ trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyFaqTrash(): int
    {
        return DB::transaction(function () {
            $trashedFaqs = Faq::onlyTrashed()->get();
            if ($trashedFaqs->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedFaqs as $faq) {
                $faq->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of FAQs.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateFaqStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Faq::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No FAQs found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single FAQ.
     *
     * @param Faq $faq
     * @return Faq
     * @throws Exception
     */
    public function toggleFaqStatus(Faq $faq): Faq
    {
        return DB::transaction(function () use ($faq) {
            $faq->status = !$faq->status;
            if (auth()->check()) {
                $faq->updated_by = auth()->id();
            }
            $faq->save();
            return $faq;
        });
    }

    /**
     * Duplicate a FAQ.
     *
     * @param Faq $faq
     * @return Faq
     * @throws Exception
     */
    public function duplicateFaq(Faq $faq): Faq
    {
        return DB::transaction(function () use ($faq) {
            $duplicatedFaq = $faq->replicate();
            $duplicatedFaq->title = $faq->title . ' (Copy)';
            $duplicatedFaq->status = false;

            if (auth()->check()) {
                $duplicatedFaq->created_by = auth()->id();
                $duplicatedFaq->updated_by = null;
            }
            $duplicatedFaq->save();
            return $duplicatedFaq;
        });
    }

    /**
     * Get statistics for FAQs.
     *
     * @return array
     */
    public function getFaqStatistics(): array
    {
        return [
            'total' => Faq::query()->count(),
            'active' => Faq::query()->where('status', true)->count(),
            'inactive' => Faq::query()->where('status', false)->count(),
            'trashed' => Faq::onlyTrashed()->count(),
            'this_month' => Faq::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Faq::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Faq::query()->whereDate('created_at', today())->count(),
        ];
    }
}
