<?php

use App\Http\Controllers\Admin\Web\AboutUsController;
use App\Http\Controllers\Admin\Web\CallToActionController;
use App\Http\Controllers\Admin\Web\CourseController;
use App\Http\Controllers\Admin\Web\FaqController;
use App\Http\Controllers\Admin\Web\FeatureController;
use App\Http\Controllers\Admin\Web\GalleryController;
use App\Http\Controllers\Admin\Web\NewsController;
use App\Http\Controllers\Admin\Web\PageController;
use App\Http\Controllers\Admin\Web\SliderController;
use App\Http\Controllers\Admin\Web\SocialSettingController;
use App\Http\Controllers\Admin\Web\TestimonialController;
use App\Http\Controllers\Admin\Web\TopBarSettingController;
use App\Http\Controllers\Admin\Web\WebEventController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/web')->group(function () {
    // AboutUs Routes (typically single entry)
    Route::apiResource('about-us', AboutUsController::class)->only(['index', 'store']);

    // Social Settings Routes (typically single entry)
    Route::apiResource('social-settings', SocialSettingController::class)->only(['index', 'store']);

    // CallToAction Routes (typically single entry)
    Route::apiResource('call-to-actions', CallToActionController::class)->only(['index', 'store']);

    // TopbarSettings Routes (typically single entry)
    Route::apiResource('topbar-settings', TopBarSettingController::class)->only(['index', 'store']);

    // Faq Routes with comprehensive functionality
    Route::prefix('faqs')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [FaqController::class, 'restore'])->name('faqs.restore');
        Route::delete('{id}/force-destroy', [FaqController::class, 'forceDestroy'])->name('faqs.force-destroy');
        Route::delete('empty-trash', [FaqController::class, 'emptyTrash'])->name('faqs.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [FaqController::class, 'bulkDestroy'])->name('faqs.bulk-destroy');
        Route::patch('bulk-restore', [FaqController::class, 'bulkRestore'])->name('faqs.bulk-restore');
        Route::delete('bulk-force-destroy', [FaqController::class, 'bulkForceDestroy'])->name('faqs.bulk-force-destroy');
        Route::patch('bulk-status', [FaqController::class, 'bulkUpdateStatus'])->name('faqs.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::post('{id}/duplicate', [FaqController::class, 'duplicate'])->name('faqs.duplicate');

        // Statistics
        Route::get('statistics/overview', [FaqController::class, 'statistics'])->name('faqs.statistics');
    });
    Route::apiResource('faqs', FaqController::class);

    // Sliders Routes with comprehensive functionality
    Route::prefix('sliders')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [SliderController::class, 'restore'])->name('sliders.restore');
        Route::delete('{id}/force-destroy', [SliderController::class, 'forceDestroy'])->name('sliders.force-destroy');
        Route::delete('empty-trash', [SliderController::class, 'emptyTrash'])->name('sliders.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [SliderController::class, 'bulkDestroy'])->name('sliders.bulk-destroy');
        Route::patch('bulk-restore', [SliderController::class, 'bulkRestore'])->name('sliders.bulk-restore');
        Route::delete('bulk-force-destroy', [SliderController::class, 'bulkForceDestroy'])->name('sliders.bulk-force-destroy');
        Route::patch('bulk-status', [SliderController::class, 'bulkUpdateStatus'])->name('sliders.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
        Route::post('{id}/duplicate', [SliderController::class, 'duplicate'])->name('sliders.duplicate');

        // Statistics
        Route::get('statistics/overview', [SliderController::class, 'statistics'])->name('sliders.statistics');
    });
    Route::apiResource('sliders', SliderController::class);

    // Feature Routes with comprehensive functionality
    Route::prefix('features')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [FeatureController::class, 'restore'])->name('features.restore');
        Route::delete('{id}/force-destroy', [FeatureController::class, 'forceDestroy'])->name('features.force-destroy');
        Route::delete('empty-trash', [FeatureController::class, 'emptyTrash'])->name('features.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [FeatureController::class, 'bulkDestroy'])->name('features.bulk-destroy');
        Route::patch('bulk-restore', [FeatureController::class, 'bulkRestore'])->name('features.bulk-restore');
        Route::delete('bulk-force-destroy', [FeatureController::class, 'bulkForceDestroy'])->name('features.bulk-force-destroy');
        Route::patch('bulk-status', [FeatureController::class, 'bulkUpdateStatus'])->name('features.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [FeatureController::class, 'toggleStatus'])->name('features.toggle-status');
        Route::post('{id}/duplicate', [FeatureController::class, 'duplicate'])->name('features.duplicate');

        // Statistics
        Route::get('statistics/overview', [FeatureController::class, 'statistics'])->name('features.statistics');
    });
    Route::apiResource('features', FeatureController::class);

    // Galleries Routes with comprehensive functionality
    Route::prefix('galleries')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [GalleryController::class, 'restore'])->name('galleries.restore');
        Route::delete('{id}/force-destroy', [GalleryController::class, 'forceDestroy'])->name('galleries.force-destroy');
        Route::delete('empty-trash', [GalleryController::class, 'emptyTrash'])->name('galleries.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [GalleryController::class, 'bulkDestroy'])->name('galleries.bulk-destroy');
        Route::patch('bulk-restore', [GalleryController::class, 'bulkRestore'])->name('galleries.bulk-restore');
        Route::delete('bulk-force-destroy', [GalleryController::class, 'bulkForceDestroy'])->name('galleries.bulk-force-destroy');
        Route::patch('bulk-status', [GalleryController::class, 'bulkUpdateStatus'])->name('galleries.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('galleries.toggle-status');
        Route::post('{id}/duplicate', [GalleryController::class, 'duplicate'])->name('galleries.duplicate');

        // Statistics
        Route::get('statistics/overview', [GalleryController::class, 'statistics'])->name('galleries.statistics');
    });
    Route::apiResource('galleries', GalleryController::class);

    // News Routes with comprehensive functionality
    Route::prefix('news')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [NewsController::class, 'restore'])->name('news.restore');
        Route::delete('{id}/force-destroy', [NewsController::class, 'forceDestroy'])->name('news.force-destroy');
        Route::delete('empty-trash', [NewsController::class, 'emptyTrash'])->name('news.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [NewsController::class, 'bulkDestroy'])->name('news.bulk-destroy');
        Route::patch('bulk-restore', [NewsController::class, 'bulkRestore'])->name('news.bulk-restore');
        Route::delete('bulk-force-destroy', [NewsController::class, 'bulkForceDestroy'])->name('news.bulk-force-destroy');
        Route::patch('bulk-status', [NewsController::class, 'bulkUpdateStatus'])->name('news.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
        Route::post('{id}/duplicate', [NewsController::class, 'duplicate'])->name('news.duplicate');

        // Statistics
        Route::get('statistics/overview', [NewsController::class, 'statistics'])->name('news.statistics');
    });
    Route::apiResource('news', NewsController::class);

    // Page Routes with comprehensive functionality
    Route::prefix('pages')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
        Route::delete('{id}/force-destroy', [PageController::class, 'forceDestroy'])->name('pages.force-destroy');
        Route::delete('empty-trash', [PageController::class, 'emptyTrash'])->name('pages.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [PageController::class, 'bulkDestroy'])->name('pages.bulk-destroy');
        Route::patch('bulk-restore', [PageController::class, 'bulkRestore'])->name('pages.bulk-restore');
        Route::delete('bulk-force-destroy', [PageController::class, 'bulkForceDestroy'])->name('pages.bulk-force-destroy');
        Route::patch('bulk-status', [PageController::class, 'bulkUpdateStatus'])->name('pages.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');
        Route::post('{id}/duplicate', [PageController::class, 'duplicate'])->name('pages.duplicate');

        // Statistics
        Route::get('statistics/overview', [PageController::class, 'statistics'])->name('pages.statistics');
    });
    Route::apiResource('pages', PageController::class);

    // Testimonial Routes with comprehensive functionality
    Route::prefix('testimonials')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [TestimonialController::class, 'restore'])->name('testimonials.restore');
        Route::delete('{id}/force-destroy', [TestimonialController::class, 'forceDestroy'])->name('testimonials.force-destroy');
        Route::delete('empty-trash', [TestimonialController::class, 'emptyTrash'])->name('testimonials.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [TestimonialController::class, 'bulkDestroy'])->name('testimonials.bulk-destroy');
        Route::patch('bulk-restore', [TestimonialController::class, 'bulkRestore'])->name('testimonials.bulk-restore');
        Route::delete('bulk-force-destroy', [TestimonialController::class, 'bulkForceDestroy'])->name('testimonials.bulk-force-destroy');
        Route::patch('bulk-status', [TestimonialController::class, 'bulkUpdateStatus'])->name('testimonials.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::post('{id}/duplicate', [TestimonialController::class, 'duplicate'])->name('testimonials.duplicate');

        // Statistics
        Route::get('statistics/overview', [TestimonialController::class, 'statistics'])->name('testimonials.statistics');
    });
    Route::apiResource('testimonials', TestimonialController::class);

    // Course Routes with comprehensive functionality
    Route::prefix('courses')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [CourseController::class, 'restore'])->name('courses.restore');
        Route::delete('{id}/force-destroy', [CourseController::class, 'forceDestroy'])->name('courses.force-destroy');
        Route::delete('empty-trash', [CourseController::class, 'emptyTrash'])->name('courses.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [CourseController::class, 'bulkDestroy'])->name('courses.bulk-destroy');
        Route::patch('bulk-restore', [CourseController::class, 'bulkRestore'])->name('courses.bulk-restore');
        Route::delete('bulk-force-destroy', [CourseController::class, 'bulkForceDestroy'])->name('courses.bulk-force-destroy');
        Route::patch('bulk-status', [CourseController::class, 'bulkUpdateStatus'])->name('courses.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
        Route::post('{id}/duplicate', [CourseController::class, 'duplicate'])->name('courses.duplicate');

        // Statistics
        Route::get('statistics/overview', [CourseController::class, 'statistics'])->name('courses.statistics');
    });
    Route::apiResource('courses', CourseController::class);

    // WebEvent Routes with comprehensive functionality
    Route::prefix('web-events')->group(function () {
        // Trash management
        Route::patch('{id}/restore', [WebEventController::class, 'restore'])->name('web-events.restore');
        Route::delete('{id}/force-destroy', [WebEventController::class, 'forceDestroy'])->name('web-events.force-destroy');
        Route::delete('empty-trash', [WebEventController::class, 'emptyTrash'])->name('web-events.empty-trash');

        // Bulk operations
        Route::delete('bulk-destroy', [WebEventController::class, 'bulkDestroy'])->name('web-events.bulk-destroy');
        Route::patch('bulk-restore', [WebEventController::class, 'bulkRestore'])->name('web-events.bulk-restore');
        Route::delete('bulk-force-destroy', [WebEventController::class, 'bulkForceDestroy'])->name('web-events.bulk-force-destroy');
        Route::patch('bulk-status', [WebEventController::class, 'bulkUpdateStatus'])->name('web-events.bulk-status');

        // Individual item operations
        Route::patch('{id}/toggle-status', [WebEventController::class, 'toggleStatus'])->name('web-events.toggle-status');
        Route::post('{id}/duplicate', [WebEventController::class, 'duplicate'])->name('web-events.duplicate');

        // Statistics
        Route::get('statistics/overview', [WebEventController::class, 'statistics'])->name('web-events.statistics');
    });
    Route::apiResource('web-events', WebEventController::class);
});
