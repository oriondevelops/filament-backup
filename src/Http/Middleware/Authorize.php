<?php

namespace Orion\FilamentBackup\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Orion\FilamentBackup\BackupPlugin;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    public function handle(Request $request, Closure $next): Response
    {
        if (BackupPlugin::get()->isDownloadable()) {
            return $next($request);
        }

        return redirect(Filament::getHomeUrl());
    }
}
