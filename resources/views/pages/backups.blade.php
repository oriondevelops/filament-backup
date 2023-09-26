<x-filament-panels::page>
    <div @if($this->plugin()->pollingEnabled()) wire:poll.{{$this->plugin()->getPollingInterval()}}="updateLists" @endif>
        @livewire(\Orion\FilamentBackup\Components\BackupStatuses::class, ['backupStatuses' => $backupStatuses])
        @if($activeDisk)
            @livewire(\Orion\FilamentBackup\Components\BackupList::class, [
                'activeDisk' => $activeDisk,
                'disks' => $this->disks(),
                'activeDiskBackups' => $activeDiskBackups
            ])
        @endif
    </div>
</x-filament-panels::page>
