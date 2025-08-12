<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\CourseRequest;
use App\Http\Resources\Web\CourseResource;
use App\Services\Web\CourseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Course
 */
class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a paginated listing of the Course resources.
     *
     * @param Request $request
     * @return CourseResource|JsonResponse
     */
    public function index(Request $request): CourseResource|JsonResponse
    {
        $courses = $this->courseService->getCourses($request);
        return Response::paginated(CourseResource::collection($courses), 'Courses retrieved successfully.');
    }

    /**
     * Store a newly created Course resource in storage.
     *
     * @param CourseRequest $request The validated request.
     * @return CourseResource|JsonResponse
     */
    public function store(CourseRequest $request): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->createCourse($request->validated(), $request);
            return Response::created(new CourseResource($course->load(['createdBy'])), 'Course created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create course: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Course resource.
     *
     * @param int $id The Course ID or slug.
     * @return CourseResource|JsonResponse
     */
    public function show(int $id): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->findCourse($id);
            return Response::success(new CourseResource($course), 'Course retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found.');
        }
    }

    /**
     * Update the specified Course resource in storage.
     *
     * @param CourseRequest $request The validated request.
     * @param int $id The Course ID.
     * @return CourseResource|JsonResponse
     */
    public function update(CourseRequest $request, int $id): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->findCourse($id);
            $updatedCourse = $this->courseService->updateCourse($course, $request->validated(), $request);
            return Response::updated(new CourseResource($updatedCourse->load(['createdBy', 'updatedBy'])), 'Course updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Course resource from storage (soft delete).
     *
     * @param int $id The Course ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $course = $this->courseService->findCourse($id);
            $this->courseService->deleteCourse($course);
            return Response::deleted('Course moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Course resource from trash.
     *
     * @param int $id The Course ID.
     * @return CourseResource|JsonResponse
     */
    public function restore(int $id): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->restoreCourse($id);
            return Response::success(new CourseResource($course->load(['createdBy', 'updatedBy'])), 'Course restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Course resource from storage.
     *
     * @param int $id The Course ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->courseService->forceDeleteCourse($id);
            return Response::deleted('Course permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete courses (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:courses,id'
        ]);

        try {
            $deletedCount = $this->courseService->bulkDeleteCourses($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} courses moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No courses found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete courses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore courses from trash.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer'
        ]);

        try {
            $restoredCount = $this->courseService->bulkRestoreCourses($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} courses restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No courses found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore courses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete courses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkForceDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer'
        ]);

        try {
            $deletedCount = $this->courseService->bulkForceDeleteCourses($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} courses permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No courses found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete courses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed courses.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->courseService->emptyCourseTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} courses permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of courses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:courses,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->courseService->bulkUpdateCourseStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} courses status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No courses found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update courses status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single course.
     *
     * @param string $id
     * @return CourseResource|JsonResponse
     */
    public function toggleStatus(string $id): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->findCourse($id);
            $updatedCourse = $this->courseService->toggleCourseStatus($course);
            $statusText = $updatedCourse->status ? 'activated' : 'deactivated';
            return Response::success(new CourseResource($updatedCourse), "Course {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle course status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a course.
     *
     * @param int $id
     * @return CourseResource|JsonResponse
     */
    public function duplicate(int $id): CourseResource|JsonResponse
    {
        try {
            $course = $this->courseService->findCourse($id);
            $duplicatedCourse = $this->courseService->duplicateCourse($course);
            return Response::created(new CourseResource($duplicatedCourse->load(['createdBy'])), 'Course duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Course not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for courses.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->courseService->getCourseStatistics();
            return Response::success($stats, 'Course statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve course statistics: ' . $e->getMessage(), 500);
        }
    }
}
