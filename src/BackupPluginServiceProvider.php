<?php

namespace Orion\FilamentBackup;

use Livewire\Livewire;
use Orion\FilamentBackup\Components\BackupList;
use Orion\FilamentBackup\Components\BackupStatuses;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BackupPluginServiceProvider extends PackageServiceProvider
{
    public static string $name = 'backup';

    public static string $viewNamespace = 'backup';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name(static::$name)
            ->hasRoute('api')
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        Livewire::component('backup-list', BackupList::class);
        Livewire::component('backup-statuses', BackupStatuses::class);
    }
}
