<?php

namespace Orion\FilamentBackup\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BackupDisk implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $configuredBackupDisks = config('backup.backup.destination.disks');

        if (! in_array($value, $configuredBackupDisks)) {
            $fail('This disk is not configured as a backup disk.');
        }
    }
}
