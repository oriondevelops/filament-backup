<?php

namespace Orion\FilamentBackup\Components;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BackupStatuses extends Component
{
    #[Reactive]
    public array $backupStatuses;

    public function render(): View
    {
        return view('backup::components.backup-statuses');
    }
}
