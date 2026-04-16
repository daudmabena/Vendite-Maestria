<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\CampaignController;
use Modules\Brand\Http\Controllers\DashboardController;
use Modules\Brand\Http\Controllers\EngagementController;

Route::prefix('api/brand')->middleware(['api'])->group(function () {
    Route::get('dashboard', DashboardController::class);

    Route::get('customers/{customerId}/engagement', [EngagementController::class, 'show']);

    Route::apiResource('campaigns', CampaignController::class);
    Route::post('campaigns/{id}/launch', [CampaignController::class, 'launch']);
});
