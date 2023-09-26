<?php

use Illuminate\Support\Facades\Route;
use Orion\FilamentBackup\Http\Controllers\DownloadBackupController;
use Orion\FilamentBackup\Http\Middleware\Authorize;

Route::get('download-backup', DownloadBackupController::class)
    ->middleware([
        ...filament()->getCurrentPanel()?->getMiddleware() ?? [],
        ...filament()->getCurrentPanel()?->getAuthMiddleware() ?? [],
        Authorize::class,
    ])
    ->name('download-backup');
