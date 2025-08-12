<?php

use App\Http\Controllers\Admin\System\CityController;
use App\Http\Controllers\Admin\System\CountryController;
use App\Http\Controllers\Admin\System\CurrencyController;
use App\Http\Controllers\Admin\System\LanguageController;
use App\Http\Controllers\Admin\System\MailSettingController;
use App\Http\Controllers\Admin\System\PaymentSettingController;
use App\Http\Controllers\Admin\System\PermissionController;
use App\Http\Controllers\Admin\System\RoleController;
use App\Http\Controllers\Admin\System\SettingController;
use App\Http\Controllers\Admin\System\SmsSettingController; // Added
use App\Http\Controllers\Admin\System\StateController;
use App\Http\Controllers\Admin\System\TimezoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('system')->group(function () {
    // Setting Routes
    Route::apiResource('settings', SettingController::class)->only(['index', 'store']);
    // Country Routes
    Route::prefix('countries')->group(function () {
        Route::patch('{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
        Route::delete('{id}/force-destroy', [CountryController::class, 'forceDestroy'])->name('countries.force-destroy');
        Route::delete('empty-trash', [CountryController::class, 'emptyTrash'])->name('countries.empty-trash');
        Route::delete('bulk-destroy', [CountryController::class, 'bulkDestroy'])->name('countries.bulk-destroy');
        Route::patch('bulk-restore', [CountryController::class, 'bulkRestore'])->name('countries.bulk-restore');
        Route::delete('bulk-force-destroy', [CountryController::class, 'bulkForceDestroy'])->name('countries.bulk-force-destroy');
        Route::patch('bulk-status', [CountryController::class, 'bulkUpdateStatus'])->name('countries.bulk-status');
        Route::patch('{id}/toggle-status', [CountryController::class, 'toggleStatus'])->name('countries.toggle-status');
        Route::post('{id}/duplicate', [CountryController::class, 'duplicate'])->name('countries.duplicate');
        Route::get('statistics/overview', [CountryController::class, 'statistics'])->name('countries.statistics');
    });
    Route::apiResource('countries', CountryController::class);

    // State Routes
    Route::prefix('states')->group(function () {
        Route::patch('{id}/restore', [StateController::class, 'restore'])->name('states.restore');
        Route::delete('{id}/force-destroy', [StateController::class, 'forceDestroy'])->name('states.force-destroy');
        Route::delete('empty-trash', [StateController::class, 'emptyTrash'])->name('states.empty-trash');
        Route::delete('bulk-destroy', [StateController::class, 'bulkDestroy'])->name('states.bulk-destroy');
        Route::patch('bulk-restore', [StateController::class, 'bulkRestore'])->name('states.bulk-restore');
        Route::delete('bulk-force-destroy', [StateController::class, 'bulkForceDestroy'])->name('states.bulk-force-destroy');
        Route::patch('bulk-status', [StateController::class, 'bulkUpdateStatus'])->name('states.bulk-status');
        Route::patch('{id}/toggle-status', [StateController::class, 'toggleStatus'])->name('states.toggle-status');
        Route::post('{id}/duplicate', [StateController::class, 'duplicate'])->name('states.duplicate');
        Route::get('statistics/overview', [StateController::class, 'statistics'])->name('states.statistics');
    });
    Route::apiResource('states', StateController::class);

    // City Routes
    Route::prefix('cities')->group(function () {
        Route::patch('{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
        Route::delete('{id}/force-destroy', [CityController::class, 'forceDestroy'])->name('cities.force-destroy');
        Route::delete('empty-trash', [CityController::class, 'emptyTrash'])->name('cities.empty-trash');
        Route::delete('bulk-destroy', [CityController::class, 'bulkDestroy'])->name('cities.bulk-destroy');
        Route::patch('bulk-restore', [CityController::class, 'bulkRestore'])->name('cities.bulk-restore');
        Route::delete('bulk-force-destroy', [CityController::class, 'bulkForceDestroy'])->name('cities.bulk-force-destroy');
        Route::patch('bulk-status', [CityController::class, 'bulkUpdateStatus'])->name('cities.bulk-status');
        Route::patch('{id}/toggle-status', [CityController::class, 'toggleStatus'])->name('cities.toggle-status');
        Route::post('{id}/duplicate', [CityController::class, 'duplicate'])->name('cities.duplicate');
        Route::get('statistics/overview', [CityController::class, 'statistics'])->name('cities.statistics');
    });
    Route::apiResource('cities', CityController::class);

    // Language Routes
    Route::prefix('languages')->group(function () {
        Route::patch('{id}/restore', [LanguageController::class, 'restore'])->name('languages.restore');
        Route::delete('{id}/force-destroy', [LanguageController::class, 'forceDestroy'])->name('languages.force-destroy');
        Route::delete('empty-trash', [LanguageController::class, 'emptyTrash'])->name('languages.empty-trash');
        Route::delete('bulk-destroy', [LanguageController::class, 'bulkDestroy'])->name('languages.bulk-destroy');
        Route::patch('bulk-restore', [LanguageController::class, 'bulkRestore'])->name('languages.bulk-restore');
        Route::delete('bulk-force-destroy', [LanguageController::class, 'bulkForceDestroy'])->name('languages.bulk-force-destroy');
        Route::patch('bulk-status', [LanguageController::class, 'bulkUpdateStatus'])->name('languages.bulk-status');
        Route::patch('{id}/toggle-status', [LanguageController::class, 'toggleStatus'])->name('languages.toggle-status');
        Route::post('{id}/duplicate', [LanguageController::class, 'duplicate'])->name('languages.duplicate');
        Route::get('statistics/overview', [LanguageController::class, 'statistics'])->name('languages.statistics');
    });
    Route::apiResource('languages', LanguageController::class);

    // Currency Routes
    Route::prefix('currencies')->group(function () {
        Route::patch('{id}/restore', [CurrencyController::class, 'restore'])->name('currencies.restore');
        Route::delete('{id}/force-destroy', [CurrencyController::class, 'forceDestroy'])->name('currencies.force-destroy');
        Route::delete('empty-trash', [CurrencyController::class, 'emptyTrash'])->name('currencies.empty-trash');
        Route::delete('bulk-destroy', [CurrencyController::class, 'bulkDestroy'])->name('currencies.bulk-destroy');
        Route::patch('bulk-restore', [CurrencyController::class, 'bulkRestore'])->name('currencies.bulk-restore');
        Route::delete('bulk-force-destroy', [CurrencyController::class, 'bulkForceDestroy'])->name('currencies.bulk-force-destroy');
        Route::patch('bulk-status', [CurrencyController::class, 'bulkUpdateStatus'])->name('currencies.bulk-status');
        Route::patch('{id}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('currencies.toggle-status');
        Route::post('{id}/duplicate', [CurrencyController::class, 'duplicate'])->name('currencies.duplicate');
        Route::get('statistics/overview', [CurrencyController::class, 'statistics'])->name('currencies.statistics');
    });
    Route::apiResource('currencies', CurrencyController::class);

    // Timezone Routes
    Route::prefix('timezones')->group(function () {
        Route::patch('{id}/restore', [TimezoneController::class, 'restore'])->name('timezones.restore');
        Route::delete('{id}/force-destroy', [TimezoneController::class, 'forceDestroy'])->name('timezones.force-destroy');
        Route::delete('empty-trash', [TimezoneController::class, 'emptyTrash'])->name('timezones.empty-trash');
        Route::delete('bulk-destroy', [TimezoneController::class, 'bulkDestroy'])->name('timezones.bulk-destroy');
        Route::patch('bulk-restore', [TimezoneController::class, 'bulkRestore'])->name('timezones.bulk-restore');
        Route::delete('bulk-force-destroy', [TimezoneController::class, 'bulkForceDestroy'])->name('timezones.bulk-force-destroy');
        Route::patch('bulk-status', [TimezoneController::class, 'bulkUpdateStatus'])->name('timezones.bulk-status');
        Route::patch('{id}/toggle-status', [TimezoneController::class, 'toggleStatus'])->name('timezones.toggle-status');
        Route::post('{id}/duplicate', [TimezoneController::class, 'duplicate'])->name('timezones.duplicate');
        Route::get('statistics/overview', [TimezoneController::class, 'statistics'])->name('timezones.statistics');
    });
    Route::apiResource('timezones', TimezoneController::class);

    // Roles Routes
    Route::prefix('roles')->group(function () {
        Route::patch('{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');
        Route::delete('{id}/force-destroy', [RoleController::class, 'forceDestroy'])->name('roles.force-destroy');
        Route::delete('empty-trash', [RoleController::class, 'emptyTrash'])->name('roles.empty-trash');
        Route::delete('bulk-destroy', [RoleController::class, 'bulkDestroy'])->name('roles.bulk-destroy');
        Route::patch('bulk-restore', [RoleController::class, 'bulkRestore'])->name('roles.bulk-restore');
        Route::delete('bulk-force-destroy', [RoleController::class, 'bulkForceDestroy'])->name('roles.bulk-force-destroy');
        Route::patch('bulk-status', [RoleController::class, 'bulkUpdateStatus'])->name('roles.bulk-status');
        Route::patch('{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
        Route::post('{id}/duplicate', [RoleController::class, 'duplicate'])->name('roles.duplicate');
        Route::get('statistics/overview', [RoleController::class, 'statistics'])->name('roles.statistics');
    });
    Route::apiResource('roles', RoleController::class);

    // Permissions Routes
    Route::prefix('permissions')->group(function () {
        Route::patch('{id}/restore', [PermissionController::class, 'restore'])->name('permissions.restore');
        Route::delete('{id}/force-destroy', [PermissionController::class, 'forceDestroy'])->name('permissions.force-destroy');
        Route::delete('empty-trash', [PermissionController::class, 'emptyTrash'])->name('permissions.empty-trash');
        Route::delete('bulk-destroy', [PermissionController::class, 'bulkDestroy'])->name('permissions.bulk-destroy');
        Route::patch('bulk-restore', [PermissionController::class, 'bulkRestore'])->name('permissions.bulk-restore');
        Route::delete('bulk-force-destroy', [PermissionController::class, 'bulkForceDestroy'])->name('permissions.bulk-force-destroy');
        Route::patch('bulk-status', [PermissionController::class, 'bulkUpdateStatus'])->name('permissions.bulk-status');
        Route::patch('{id}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
        Route::post('{id}/duplicate', [PermissionController::class, 'duplicate'])->name('permissions.duplicate');
        Route::get('statistics/overview', [PermissionController::class, 'statistics'])->name('permissions.statistics');
    });
    Route::apiResource('permissions', PermissionController::class);

    // Mail Settings Routes (single entry)
    Route::apiResource('mail-settings', MailSettingController::class)->only(['index', 'store']);

    // SMS Settings Routes (single entry)
    Route::apiResource('sms-settings', SmsSettingController::class)->only(['index', 'store']);

    // Payment Settings Routes (single entry)
    Route::apiResource('payment-settings', PaymentSettingController::class)->only(['index', 'store']);
});
