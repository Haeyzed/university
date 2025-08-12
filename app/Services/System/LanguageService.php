<?php

namespace App\Services\System;

use App\Models\System\Language;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageService
{
    /**
     * Retrieve a paginated list of languages.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getLanguages(Request $request): LengthAwarePaginator
    {
        return Language::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhere('code', "%{$search}%")
                    ->orWhere('name_native', "%{$search}%")
                );
            })
            ->when($request->filled('dir'), fn($q) => $q->where('dir', $request->dir))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'code', 'dir', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new language.
     *
     * @param array $data
     * @return Language
     * @throws Exception
     */
    public function createLanguage(array $data): Language
    {
        return DB::transaction(function () use ($data) {
            $language = new Language($data);

            if (auth()->check()) {
                $language->created_by = auth()->id();
            }
            $language->save();

            return $language;
        });
    }

    /**
     * Find a language by ID.
     *
     * @param int $id
     * @return Language
     */
    public function findLanguage(int $id): Language
    {
        return Language::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing language.
     *
     * @param Language $language
     * @param array $data
     * @return Language
     * @throws Exception
     */
    public function updateLanguage(Language $language, array $data): Language
    {
        return DB::transaction(function () use ($language, $data) {
            $language->fill($data);

            if (auth()->check()) {
                $language->updated_by = auth()->id();
            }
            $language->save();

            return $language;
        });
    }

    /**
     * Soft delete a language.
     *
     * @param Language $language
     * @return bool|null
     * @throws Exception
     */
    public function deleteLanguage(Language $language): ?bool
    {
        return DB::transaction(fn() => $language->delete());
    }

    /**
     * Restore a soft-deleted language.
     *
     * @param int $id
     * @return Language
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreLanguage(int $id): Language
    {
        return DB::transaction(function () use ($id) {
            $language = Language::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $language->updated_by = auth()->id();
            }
            $language->restore();

            return $language;
        });
    }

    /**
     * Permanently delete a language.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteLanguage(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $language = Language::onlyTrashed()->findOrFail($id);
            return $language->forceDelete();
        });
    }

    /**
     * Bulk soft delete languages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteLanguages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Language::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No languages found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore languages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreLanguages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Language::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No languages found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Language::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Language::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete languages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteLanguages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $languages = Language::onlyTrashed()->whereIn('id', $ids)->get();
            if ($languages->isEmpty()) {
                throw new ModelNotFoundException('No languages found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($languages as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed languages.
     *
     * @return int
     * @throws Exception
     */
    public function emptyLanguageTrash(): int
    {
        return DB::transaction(function () {
            $trashedLanguages = Language::onlyTrashed()->get();
            if ($trashedLanguages->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedLanguages as $language) {
                $language->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for languages.
     *
     * @return array
     */
    public function getLanguageStatistics(): array
    {
        return [
            'total' => Language::query()->count(),
            'trashed' => Language::onlyTrashed()->count(),
            'this_month' => Language::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Language::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Language::query()->whereDate('created_at', today())->count(),
        ];
    }
}
