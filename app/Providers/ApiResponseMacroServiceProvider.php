<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = null, string $message = 'Operation successful.', int $status = 200) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function (string $message = 'Operation failed.', int $status = 400, $data = null) {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('paginated', function ($data, string $message = 'Data retrieved successfully.', int $status = 200) {
            // Handle different types of paginated data
            if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
                $paginator = $data->resource;

                return Response::json([
                    'success' => true,
                    'message' => $message,
                    'data' => $data->items(),
                    'pagination' => [
                        'current_page' => $paginator->currentPage(),
                        'per_page' => $paginator->perPage(),
                        'total' => $paginator->total(),
                        'last_page' => $paginator->lastPage(),
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                        'has_more_pages' => $paginator->hasMorePages(),
                        'next_page_url' => $paginator->nextPageUrl(),
                        'prev_page_url' => $paginator->previousPageUrl(),
                        'first_page_url' => $paginator->url(1),
                        'last_page_url' => $paginator->url($paginator->lastPage()),
                        'links' => $paginator->linkCollection()->toArray(),
                    ]
                ], $status);
            }

            // Handle direct paginator instance
            if ($data instanceof LengthAwarePaginator) {
                return Response::json([
                    'success' => true,
                    'message' => $message,
                    'data' => $data->items(),
                    'pagination' => [
                        'current_page' => $data->currentPage(),
                        'per_page' => $data->perPage(),
                        'total' => $data->total(),
                        'last_page' => $data->lastPage(),
                        'from' => $data->firstItem(),
                        'to' => $data->lastItem(),
                        'has_more_pages' => $data->hasMorePages(),
                        'next_page_url' => $data->nextPageUrl(),
                        'prev_page_url' => $data->previousPageUrl(),
                        'first_page_url' => $data->url(1),
                        'last_page_url' => $data->url($data->lastPage()),
                        'links' => $data->linkCollection()->toArray(),
                    ]
                ], $status);
            }

            // Fallback for non-paginated data
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('collection', function ($data, string $message = 'Collection retrieved successfully.', array $meta = [], int $status = 200) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'meta' => $meta,
            ], $status);
        });

        Response::macro('created', function ($data = null, string $message = 'Resource created successfully.') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], 201);
        });

        Response::macro('updated', function ($data = null, string $message = 'Resource updated successfully.') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], 200);
        });

        Response::macro('deleted', function (string $message = 'Resource deleted successfully.') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => null,
            ], 200);
        });

        Response::macro('noContent', function (string $message = 'No content.') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => null,
            ], 204);
        });

        Response::macro('unauthorized', function (string $message = 'Unauthorized access.') {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => null,
            ], 401);
        });

        Response::macro('forbidden', function (string $message = 'Access forbidden.') {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => null,
            ], 403);
        });

        Response::macro('notFound', function (string $message = 'Resource not found.') {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => null,
            ], 404);
        });

        Response::macro('validationError', function ($errors, string $message = 'Validation failed.') {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => $errors,
            ], 422);
        });
    }
}
