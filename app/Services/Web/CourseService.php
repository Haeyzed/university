<?php

namespace App\Services\Web;

use App\Helpers\SlugHelper;
use App\Models\Web\Course;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of courses.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getCourses(Request $request): LengthAwarePaginator
    {
        return Course::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('description', "%{$search}%")
                    ->orWhereLike('faculty', "%{$search}%")
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
     * Create a new course.
     *
     * @param array $data
     * @param Request $request
     * @return Course
     * @throws Exception
     */
    public function createCourse(array $data, Request $request): Course
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'course');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $slug = SlugHelper::generateUniqueSlug($data['slug'] ?? $data['title'], Course::class);

            $course = new Course($data);
            $course->slug = $slug;
            $course->status = $data['status'] ?? true;

            $course->attach = $this->uploadImage($request, 'attach', 'course', 800, 500);

            if (auth()->check()) {
                $course->created_by = auth()->id();
            }
            $course->save();

            return $course;
        });
    }

    /**
     * Find a course by ID or slug.
     *
     * @param int $id
     * @return Course
     */
    public function findCourse(int $id): Course
    {
        return Course::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing course.
     *
     * @param Course $course
     * @param array $data
     * @param Request $request
     * @return Course
     * @throws Exception
     */
    public function updateCourse(Course $course, array $data, Request $request): Course
    {
        return DB::transaction(function () use ($course, $data, $request) {
            $slug = $data['slug'] ?? $data['title'];
            if ($slug !== $course->slug) {
                $course->slug = SlugHelper::generateUniqueSlug($slug, Course::class, $course->id);
            }

            $course->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'course', 800, 500, $course, 'attach');
            $course->attach = $imageName;

            if (auth()->check()) {
                $course->updated_by = auth()->id();
            }
            $course->save();

            return $course;
        });
    }

    /**
     * Soft delete a course.
     *
     * @param Course $course
     * @return bool|null
     * @throws Exception
     */
    public function deleteCourse(Course $course): ?bool
    {
        return DB::transaction(fn() => $course->delete());
    }

    /**
     * Restore a soft-deleted course.
     *
     * @param string $id
     * @return Course
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreCourse(string $id): Course
    {
        return DB::transaction(function () use ($id) {
            $course = Course::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $course->updated_by = auth()->id();
            }
            $course->restore();

            return $course;
        });
    }

    /**
     * Permanently delete a course.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteCourse(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $course = Course::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('course', $course); // Delete associated image permanently
            return $course->forceDelete();
        });
    }

    /**
     * Bulk soft delete courses.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteCourses(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Course::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No courses found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore courses.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreCourses(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Course::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No courses found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Course::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Course::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete courses.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteCourses(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $courses = Course::onlyTrashed()->whereIn('id', $ids)->get();
            if ($courses->isEmpty()) {
                throw new ModelNotFoundException('No courses found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($courses as $item) {
                $this->deleteMedia('course', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the course trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyCourseTrash(): int
    {
        return DB::transaction(function () {
            $trashedCourses = Course::onlyTrashed()->get();
            if ($trashedCourses->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedCourses as $course) {
                $this->deleteMedia('course', $course);
                $course->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of courses.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateCourseStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Course::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No courses found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single course.
     *
     * @param Course $course
     * @return Course
     * @throws Exception
     */
    public function toggleCourseStatus(Course $course): Course
    {
        return DB::transaction(function () use ($course) {
            $course->status = !$course->status;
            if (auth()->check()) {
                $course->updated_by = auth()->id();
            }
            $course->save();
            return $course;
        });
    }

    /**
     * Duplicate a course.
     *
     * @param Course $course
     * @return Course
     * @throws Exception
     */
    public function duplicateCourse(Course $course): Course
    {
        return DB::transaction(function () use ($course) {
            $duplicatedCourse = $course->replicate();
            $duplicatedCourse->title = $course->title . ' (Copy)';
            $duplicatedCourse->slug = SlugHelper::generateUniqueSlug($duplicatedCourse->title, Course::class);
            $duplicatedCourse->status = false;

            if (auth()->check()) {
                $duplicatedCourse->created_by = auth()->id();
                $duplicatedCourse->updated_by = null;
            }
            $duplicatedCourse->save();
            return $duplicatedCourse;
        });
    }

    /**
     * Get statistics for courses.
     *
     * @return array
     */
    public function getCourseStatistics(): array
    {
        return [
            'total' => Course::query()->count(),
            'active' => Course::query()->where('status', true)->count(),
            'inactive' => Course::query()->where('status', false)->count(),
            'trashed' => Course::onlyTrashed()->count(),
            'this_month' => Course::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Course::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Course::query()->whereDate('created_at', today())->count(),
        ];
    }
}
