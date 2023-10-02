# A Filament plugin to backup your application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oriondevelops/filament-backup.svg?style=flat-square)](https://packagist.org/packages/oriondevelops/filament-backup)
[![Total Downloads](https://img.shields.io/packagist/dt/oriondevelops/filament-backup.svg?style=flat-square)](https://packagist.org/packages/oriondevelops/filament-backup)

This Filament plugin is a dedicated port of the [spatie/nova-backup-tool](https://github.com/spatie/nova-backup-tool), adapted to work seamlessly with [Filament](https://filamentphp.com/).

Much of the underlying codebase and functionality owe their origins to the work done by the contributors on the original Nova tool. This adaptation was undertaken with deep respect and acknowledgment of their effort.

The plugin lets you:

- List all backups
- Create a new backup
- Download a backup
- Delete a backup

Internally, it utilizes [spatie/laravel-backup](https://github.com/spatie/laravel-backup) to manage these backups.

## Requirements

Ensure that you meet the requirements for [spatie/laravel-backup](https://spatie.be/docs/laravel-backup/v8/requirements).

## Installation

Install [spatie/laravel-backup](https://docs.spatie.be/laravel-backup) into your Laravel app following its [installation instructions](https://spatie.be/docs/laravel-backup/v8/installation-and-setup).

You can install the package via composer:

```bash
composer require oriondevelops/filament-backup
```

## Usage

You need to register the plugin with your preferred Filament panel providers. This can be done inside of your `PanelProvider`, e.g. `AdminPanelProvider`.

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Orion\FilamentBackup\BackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(BackupPlugin::make());
    }
}
```

You can now click on the "Backups" menu item in your Filament app to see the backup plugin.

### Customizing visibility, download and delete permissions

Define who can view, download, or delete backups. Tailor access based on user permissions.

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Orion\FilamentBackup\BackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                BackupPlugin::make()
                    ->visible(fn() => auth()->user()->can('view backups'))
                    ->downloadable(fn() => auth()->user()->can('download backups'))
                    ->deletable(fn() => auth()->user()->can('delete backups')),
            );
    }
}
```

### Customizing the navigation item

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Orion\FilamentBackup\BackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                BackupPlugin::make()
                    ->slug('my-precious-backups')
                    ->label('Backups')
                    ->icon('heroicon-o-server-stack')
                    ->group('System')
                    ->sort(3),
            );
    }
}
```


### Customizing the polling interval

You can customise the polling interval or disable polling:

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Orion\FilamentBackup\BackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                BackupPlugin::make()
                    ->polling(enabled: true, interval: '10s'),
            );
    }
}
```

### Customizing the backup queue and page

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Orion\FilamentBackup\BackupPlugin;
use App\Filament\Pages\ExtendedBackupsPage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                BackupPlugin::make()
                    ->queue('custom-queue')
                    ->page(ExtendedBackupsPage::class),
            );
    }
}
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [Security Policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Nova Backup Tool Credits
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](https://github.com/spatie/nova-backup-tool/contributors)

## Credits

- [Mücahit Uğur](https://github.com/oriondevelops)
- [All Contributors](https://github.com/oriondevelops/filament-backup/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
