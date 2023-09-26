<?php

namespace Orion\FilamentBackup\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Orion\FilamentBackup\BackupPlugin;
use Orion\FilamentBackup\Jobs\CreateBackupJob;
use Spatie\Backup\BackupDestination\Backup as SpatieBackup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Helpers\Format;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class Backups extends Page
{
    public ?string $activeDisk = null;

    public array $activeDiskBackups;

    public array $backupStatuses;

    protected static string $view = 'backup::pages.backups';

    public static function getNavigationLabel(): string
    {
        return __('Backups');
    }

    public static function getNavigationIcon(): ?string
    {
        return BackupPlugin::get()->getIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        return BackupPlugin::get()->getGroup();
    }

    public function getTitle(): string | Htmlable
    {
        return __('Backups');
    }

    public function mount(): void
    {
        abort_unless($this->plugin()->isVisible(), 403);
        $this->updateBackupStatuses();
        $this->updateActiveDiskBackups();
    }

    #[Computed]
    public function disks(): array
    {
        return array_map(fn (?array $backupStatus) => $backupStatus['disk'], $this->backupStatuses);
    }

    public function updateBackupStatuses(): void
    {
        Cache::remember('backup-statuses', now()->addSeconds(4), function () {
            $backupStatuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'))
                ->map(function (BackupDestinationStatus $backupDestinationStatus) {
                    return [
                        'name' => $backupDestinationStatus->backupDestination()->backupName(),
                        'disk' => $backupDestinationStatus->backupDestination()->diskName(),
                        'reachable' => $backupDestinationStatus->backupDestination()->isReachable(),
                        'healthy' => $backupDestinationStatus->isHealthy(),
                        'amount' => $backupDestinationStatus->backupDestination()->backups()->count(),
                        'newest' => $backupDestinationStatus->backupDestination()->newestBackup()
                            ? $backupDestinationStatus->backupDestination()->newestBackup()->date()->diffForHumans()
                            : __('No backups present'),
                        'usedStorage' => Format::humanReadableSize($backupDestinationStatus->backupDestination()->usedStorage()),
                    ];
                })
                ->values()
                ->toArray();

            if (! $this->activeDisk) {
                $this->activeDisk = $backupStatuses[0]['disk'];
            }

            $this->backupStatuses = $backupStatuses;
        });
    }

    public function updateActiveDiskBackups(): void
    {
        if (! $this->activeDisk) {
            return;
        }

        $backupDestination = BackupDestination::create($this->activeDisk, config('backup.backup.name'));

        $this->activeDiskBackups = Cache::remember(
            "backups-{$this->activeDisk}",
            now()->addSeconds(4),
            function () use ($backupDestination) {
                return $backupDestination
                    ->backups()
                    ->map(function (SpatieBackup $backup) {
                        $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

                        return [
                            'path' => $backup->path(),
                            'date' => $backup->date()->format('Y-m-d H:i:s'),
                            'size' => Format::humanReadableSize($size),
                        ];
                    })
                    ->toArray();
            }
        );
    }

    #[On('active-disk-updated')]
    public function updateActiveDisk(string $disk): void
    {
        $this->activeDisk = $disk;
        $this->updateActiveDiskBackups();
    }

    #[On('backup-deleted')]
    public function updateLists(): void
    {
        $this->updateActiveDiskBackups();
        $this->updateBackupStatuses();
    }

    public function createBackup(?string $option = ''): void
    {
        dispatch(new CreateBackupJob($option))
            ->onQueue(BackupPlugin::get()->getQueue());

        Notification::make()
            ->success()
            ->title(__('Successful'))
            ->body(__('Creating a new backup in the background...') . ($option ? '(' . $option . ')' : null))
            ->send();
    }

    public function plugin(): BackupPlugin
    {
        return BackupPlugin::get();
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('createDbBackup')
                    ->label(__('Create database backup'))
                    ->action(fn () => $this->createBackup('only-db')),
                Action::make('createFilesBackup')
                    ->label(__('Create files backup'))
                    ->action(fn () => $this->createBackup('only-files')),
            ])->icon('heroicon-m-ellipsis-horizontal'),
            Action::make('createBackup')
                ->label(__('Create Backup'))
                ->action(fn () => $this->createBackup()),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return BackupPlugin::get()->isVisible();
    }
}
