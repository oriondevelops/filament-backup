<?php

namespace Orion\FilamentBackup;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Orion\FilamentBackup\Pages\Backups;

class BackupPlugin implements Plugin
{
    protected bool | Closure $isHidden = false;

    protected bool | Closure $isVisible = true;

    protected bool | Closure $isDownloadable = true;

    protected bool | Closure $isDeletable = true;

    protected bool $polling = true;

    protected string $pollingInterval = '2s';

    protected ?string $navigationIcon = 'heroicon-o-server-stack';

    protected ?string $navigationGroup = null;

    protected string $page = Backups::class;

    protected ?string $queue = null;

    public function getId(): string
    {
        return 'backup';
    }

    public function page(string $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function hidden(bool | Closure $condition = true): static
    {
        $this->isHidden = $condition;

        return $this;
    }

    public function visible(bool | Closure $condition = true): static
    {
        $this->isVisible = $condition;

        return $this;
    }

    public function downloadable(bool | Closure $condition = true): static
    {
        $this->isDownloadable = $condition;

        return $this;
    }

    public function deletable(bool | Closure $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    public function icon(string $icon = 'heroicon-o-server-stack'): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function group(string $group = null): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function polling(bool $enabled = true, string $interval = '2s'): static
    {
        $this->polling = $enabled;
        $this->pollingInterval = $interval;

        return $this;
    }

    public function queue(string $name): static
    {
        $this->queue = $name;

        return $this;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function getIcon(): ?string
    {
        return $this->navigationIcon;
    }

    public function getGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function pollingEnabled(): bool
    {
        return $this->polling;
    }

    public function getPollingInterval(): string
    {
        return $this->pollingInterval;
    }

    public function isDownloadable(): bool
    {
        return $this->evaluate($this->isDownloadable);
    }

    public function isDeletable(): bool
    {
        return $this->evaluate($this->isDeletable);
    }

    public function isHidden(): bool
    {
        if ($this->evaluate($this->isHidden)) {
            return true;
        }

        return ! $this->evaluate($this->isVisible);
    }

    public function isVisible(): bool
    {
        return ! $this->isHidden();
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function evaluate($value)
    {
        if ($value instanceof \Closure) {
            return app()->call($value);
        }

        return $value;
    }

    public function register(Panel $panel): void
    {
        $panel->pages([$this->getPage()]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
