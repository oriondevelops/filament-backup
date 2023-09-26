<?php

namespace Orion\FilamentBackup\Components;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Orion\FilamentBackup\BackupPlugin;
use Orion\FilamentBackup\Rules\BackupDisk;
use Orion\FilamentBackup\Rules\PathToZip;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;

class BackupList extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $activeDisk;

    public array $disks;

    #[Reactive]
    public array $activeDiskBackups;

    public function updatedActiveDisk(): void
    {
        $this->dispatch('active-disk-updated', disk: $this->activeDisk);
    }

    public function downloadAction(): Action
    {
        return Action::make('download')
            ->icon('heroicon-m-arrow-down-tray')
            ->label(__('Download'))
            ->size('md')
            ->link()
            ->visible($this->plugin()->isDownloadable())
            ->url(function (array $arguments) {
                return route('download-backup', ['disk' => $this->activeDisk, 'path' => $arguments['path']]);
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-m-trash')
            ->label(__('Delete backup'))
            ->requiresConfirmation()
            ->link()
            ->visible($this->plugin()->isDeletable())
            ->disabled(count($this->activeDiskBackups) <= 1)
            ->action(function (array $arguments) {
                $this->deleteBackup($this->activeDisk, $arguments['path']);
            });
    }

    public function deleteBackup(string $disk, string $path): void
    {
        $validator = Validator::make(['disk' => $disk, 'path' => $path], [
            'disk' => new BackupDisk(),
            'path' => ['required', new PathToZip()],
        ]);

        $validated = $validator->validated();

        $backupDestination = BackupDestination::create($validated['disk'], config('backup.backup.name'));

        $backupDestination
            ->backups()
            ->first(function (Backup $backup) use ($validated) {
                return $backup->path() === $validated['path'];
            })
            ->delete();

        $this->dispatch('backup-deleted');

        Notification::make()
            ->icon('heroicon-m-trash')
            ->iconColor('success')
            ->title(__('Deleted successfully'))
            ->send();
    }

    public function plugin(): BackupPlugin
    {
        return BackupPlugin::get();
    }

    public function render(): View
    {
        return view('backup::components.backup-list');
    }
}
