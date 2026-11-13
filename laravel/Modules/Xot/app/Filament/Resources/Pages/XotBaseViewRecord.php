<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ViewRecord as FilamentViewRecord;
<<<<<<< HEAD
use Modules\Xot\Filament\Links\RecordAnchor;
=======
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
>>>>>>> 77b1e2748 (first)

abstract class XotBaseViewRecord extends FilamentViewRecord
{
    use HasFiltersForm;

<<<<<<< HEAD
=======
    final public function infolist(Schema $schema): Schema
    {
        return $schema->components($this->getInfolistSchema());
    }

    /**
     * Restituisce lo schema dell'infolist per la visualizzazione dei dettagli del record.
     * Questo metodo deve sempre restituire un array con chiavi di tipo stringa.
     *
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [];
    }

>>>>>>> 77b1e2748 (first)
    /**
     * Get the header actions.
     *
     * @return array<string, Action|ActionGroup>
     */
    protected function getHeaderActions(): array
    {
<<<<<<< HEAD
        return [
            'back' => Action::make('back')
<<<<<<< .merge_file_cFMRkg
                ->label(__('xot::actions.back.label'))
=======
                // Filament non ha una traduzione per il ritorno all'elenco: la chiave filament-panels::...back.label
                // compariva grezza sulla pagina. `xot::buttons.back` e' tradotta in it/en/de.
                ->label(__('xot::buttons.back'))
>>>>>>> .merge_file_Wt8v6w
                ->icon('heroicon-o-arrow-left')
                ->url(function (): string {
                    $record = $this->getRecord();
                    $returnTo = request()->query(RecordAnchor::RETURN_TO_PARAMETER);
                    $key = $record->getKey();

                    if (! is_string($returnTo) || (! is_int($key) && ! is_string($key))) {
                        return $this->getResourceUrl();
                    }

                    $returnTo = RecordAnchor::resolveReturnTo($returnTo, $key);

                    return $returnTo ?? $this->getResourceUrl();
                }),
        ];
=======
        return [];
>>>>>>> 77b1e2748 (first)
    }
}
