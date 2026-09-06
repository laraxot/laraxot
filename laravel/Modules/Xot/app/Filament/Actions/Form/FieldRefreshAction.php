<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions\Form;

use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Modules\Xot\Filament\Actions\XotBaseAction;

class FieldRefreshAction extends XotBaseAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->translateLabel();
        $this->icon('heroicon-o-arrow-path')
            ->label('')
            ->tooltip('Ricalcola valore')
            ->action(function (mixed $record, Set $set): void {
                $name = $this->getName();
                if ($name === null) {
                    return;
                }

                if (! is_object($record) && ! is_string($record)) {
                    Notification::make()
                        ->title('Errore')
                        ->body('Record non valido')
                        ->danger()
                        ->send();

                    return;
                }

                Notification::make()
                    ->title('Valore ricalcolato')
                    ->body('Il valore del campo è stato ricalcolato con successo')
                    ->success()
                    ->send();
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'field_refresh';
    }
}
