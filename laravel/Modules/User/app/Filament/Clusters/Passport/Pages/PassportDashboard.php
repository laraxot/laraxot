<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Passport\Pages;

use Filament\Actions\Action;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Clusters\Cluster;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Filament\Clusters\Cluster;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Modules\User\Filament\Clusters\Passport;
use Modules\Xot\Actions\ExecuteArtisanCommandAction;
use Modules\Xot\Filament\Pages\XotBasePage;

class PassportDashboard extends XotBasePage
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    protected static ?string $cluster = Passport::class;

    protected string $view = 'user::filament.pages.passport-dashboard';

<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public bool $hasPublicKey = false;

    public bool $hasPrivateKey = false;

<<<<<<< HEAD
<<<<<<< HEAD
    /** @var list<string> */
    public array $output = [];

=======
=======
>>>>>>> 87273113 (.)
    public array $output = [];

    public string $currentCommand = '';

<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    /** @var list<string> */
    public array $output = [];

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public string $status = '';

    public bool $isRunning = false;

<<<<<<< HEAD
<<<<<<< HEAD
    public string $currentCommand = '';

    /**
     * @var class-string<Cluster>
     */
    protected static ?string $cluster = Passport::class;

    protected string $view = 'user::filament.pages.passport-dashboard';
=======
=======
>>>>>>> 87273113 (.)
    /** @var array<string, string> */
    protected $listeners = [
        'refresh-component' => '$refresh',
        'artisan-command.started' => 'handleCommandStarted',
        'artisan-command.output' => 'handleCommandOutput',
        'artisan-command.completed' => 'handleCommandCompleted',
        'artisan-command.failed' => 'handleCommandFailed',
        'artisan-command.error' => 'handleCommandError',
    ];
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    public string $currentCommand = '';

    /**
     * @var class-string<Cluster>
     */
    protected static ?string $cluster = Passport::class;

    protected string $view = 'user::filament.pages.passport-dashboard';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function executeCommand(string $command): void
    {
        $this->reset(['output', 'status']);
        $this->currentCommand = $command;
        $this->isRunning = true;

        try {
            app(ExecuteArtisanCommandAction::class)->execute($command);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error executing command')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->isRunning = false;
        }
    }

    #[On('artisan-command.started')]
    public function handleCommandStarted(string $command): void
    {
        $this->isRunning = true;
    }

    #[On('artisan-command.output')]
    public function handleCommandOutput(string $command, string $output): void
    {
        $this->output[] = $output;
        $this->dispatch('terminal-update');
    }

    public function mount(): void
    {
        $this->checkKeys();
    }

    public function checkKeys(): void
    {
        $this->hasPublicKey = file_exists(storage_path('oauth-public.key'));
        $this->hasPrivateKey = file_exists(storage_path('oauth-private.key'));
    }

    #[On('artisan-command.completed')]
    public function onCommandCompleted(string $command): void
    {
        if ($this->currentCommand === $command) {
            $this->isRunning = false;
            $this->status = 'completed';
            $this->checkKeys();
        }

        Notification::make()
            ->title('Command completed successfully')
            ->success()
            ->send();
    }

    #[On('artisan-command.failed')]
    public function handleCommandFailed(string $command, string $error): void
    {
        $this->status = 'failed';
        $this->isRunning = false;
        $this->output[] = "[ERROR] {$error}";

        Notification::make()
            ->title('Command failed')
            ->body($error)
            ->danger()
            ->send();
    }

    #[On('artisan-command.error')]
    public function handleCommandError(string $command, string $error): void
    {
        $this->status = 'failed';
        $this->isRunning = false;
        $this->output[] = "[ERROR] {$error}";

        Notification::make()
            ->title('Command error')
            ->body($error)
            ->danger()
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'hasPublicKey' => $this->hasPublicKey,
            'hasPrivateKey' => $this->hasPrivateKey,
            'publicKeyLabel' => static::trans('status.public_key'),
            'privateKeyLabel' => static::trans('status.private_key'),
            'presentLabel' => static::trans('status.present'),
            'missingLabel' => static::trans('status.missing'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'passport_install' => Action::make('passport_install')
=======
            Action::make('passport_install')
                ->label(static::trans('actions.install.label'))
>>>>>>> 60a2c9a9 (.)
=======
            Action::make('passport_install')
                ->label(static::trans('actions.install.label'))
=======
            'passport_install' => Action::make('passport_install')
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->disabled(fn () => $this->isRunning)
                ->requiresConfirmation()
                ->modalDescription(static::trans('actions.install.modal_description'))
                ->action(fn () => $this->executeCommand('passport:install --uuids')),

<<<<<<< HEAD
<<<<<<< HEAD
            'passport_keys' => Action::make('passport_keys')
=======
            Action::make('passport_keys')
                ->label(static::trans('actions.generate_keys.label'))
>>>>>>> 60a2c9a9 (.)
=======
            Action::make('passport_keys')
                ->label(static::trans('actions.generate_keys.label'))
=======
            'passport_keys' => Action::make('passport_keys')
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->icon('heroicon-o-key')
                ->color('primary')
                ->disabled(fn () => $this->isRunning)
                ->requiresConfirmation()
                ->action(fn () => $this->executeCommand('passport:keys')),

<<<<<<< HEAD
<<<<<<< HEAD
            'passport_purge' => Action::make('passport_purge')
=======
            Action::make('passport_purge')
                ->label(static::trans('actions.purge_tokens.label'))
>>>>>>> 60a2c9a9 (.)
=======
            Action::make('passport_purge')
                ->label(static::trans('actions.purge_tokens.label'))
=======
            'passport_purge' => Action::make('passport_purge')
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->disabled(fn () => $this->isRunning)
                ->requiresConfirmation()
                ->modalDescription(static::trans('actions.purge_tokens.modal_description'))
                ->action(fn () => $this->executeCommand('passport:purge')),

<<<<<<< HEAD
<<<<<<< HEAD
            'passport_hash' => Action::make('passport_hash')
=======
            Action::make('passport_hash')
                ->label(static::trans('actions.hash_secrets.label'))
>>>>>>> 60a2c9a9 (.)
=======
            Action::make('passport_hash')
                ->label(static::trans('actions.hash_secrets.label'))
=======
            'passport_hash' => Action::make('passport_hash')
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->disabled(fn () => $this->isRunning)
                ->requiresConfirmation()
                ->modalDescription(static::trans('actions.hash_secrets.modal_description'))
                ->action(fn () => $this->executeCommand('passport:hash')),
        ];
    }
}
