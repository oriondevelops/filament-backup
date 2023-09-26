<?php

namespace Orion\FilamentBackup\Tests;

use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Orion\FilamentBackup\BackupPluginServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BackupPluginServiceProvider::class,
            ActionsServiceProvider::class,
            FilamentServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
