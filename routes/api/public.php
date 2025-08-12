<?php

use App\Http\Controllers\Admin\Web\CallToActionController;
use App\Http\Controllers\Admin\Web\CourseController;
use App\Http\Controllers\Admin\Web\FaqController;
use App\Http\Controllers\Admin\Web\FeatureController;
use App\Http\Controllers\Admin\Web\GalleryController;
use App\Http\Controllers\Admin\Web\NewsController;
use App\Http\Controllers\Admin\Web\PageController;
use App\Http\Controllers\Admin\Web\SliderController;
use App\Http\Controllers\Admin\Web\TestimonialController;
use App\Http\Controllers\Admin\Web\WebEventController;
use Illuminate\Support\Facades\Route;

// Public API Routes (for frontend consumption)
Route::prefix('public')->group(function () {
    // Public news routes (only active, non-trashed items)
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('public.news.index');
        Route::get('/{news:slug}', [NewsController::class, 'show'])->name('public.news.show');
        Route::get('/statistics/overview', [NewsController::class, 'statistics'])->name('public.news.statistics');
    });

    // Public page routes (only active, non-trashed items)
    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('public.pages.index');
        Route::get('/{page:slug}', [PageController::class, 'show'])->name('public.pages.show');
        Route::get('/statistics/overview', [PageController::class, 'statistics'])->name('public.pages.statistics');
    });

    Route::get('testimonials', [TestimonialController::class, 'index'])->name('public.testimonials.index');
    Route::get('faqs', [FaqController::class, 'index'])->name('public.faqs.index');
    Route::get('sliders', [SliderController::class, 'index'])->name('public.sliders.index');
    Route::get('features', [FeatureController::class, 'index'])->name('public.features.index');
    Route::get('web-events', [WebEventController::class, 'index'])->name('public.web-events.index');
    Route::get('courses', [CourseController::class, 'index'])->name('public.courses.index');
    Route::get('galleries', [GalleryController::class, 'index'])->name('public.galleries.index');
    Route::get('call-to-actions', [CallToActionController::class, 'index'])->name('public.call-to-actions.index');

    // Add other public routes as needed
});
