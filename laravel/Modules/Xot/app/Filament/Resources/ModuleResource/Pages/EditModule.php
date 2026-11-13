<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\Arr\SaveArrayAction;
use Modules\Xot\Filament\Resources\ModuleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Xot\Models\Module;

/**
 * @property Module $record
 */
class EditModule extends XotBaseEditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //    Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        $module = $this->record; // Ottiene il record corrente
        if (! ($module instanceof Model) || ! isset($module->path)) {
            return;
        }

        $config_path = $module->path.'/config/config.php';
        $loaded = File::getRequire($config_path);
        $data = $this->normalizeConfigArray(
            array_merge(
                is_array($loaded) ? $this->normalizeConfigArray($loaded) : [],
                $this->normalizeConfigArray($module->toArray()),
            ),
        );
        unset($data['path']);
        app(SaveArrayAction::class)->execute($data, $config_path);

        /*
         * $configPath = config_path('modules/colors.php');
         *
         * // Prepara l'array di colori
         * $colorsConfig = [
         * $module->name => [
         * 'colors' => $module->colors,
         * 'icon' => $module->icon,
         * ],
         * ];
         *
         * // Se il file di configurazione esiste già, unisci i colori
         * if (File::exists($configPath)) {
         * $existingConfig = include $configPath;
         * $colorsConfig = array_merge($existingConfig, $colorsConfig);
         * }
         *
         * // Salva il nuovo file di configurazione
         * File::put($configPath, '<?php return ' . var_export($colorsConfig, true) . ';');
         *
         * // Richiama il file di configurazione per essere sicuro che i colori siano caricati
         * Config::set('modules.colors', $colorsConfig);
         */
    }

    /**
<<<<<<< HEAD
     * @param  array<array-key, mixed>  $config
=======
<<<<<<< .merge_file_17mZ2B
     * @param  array<array-key, mixed>  $config
=======
     * <<<<<<< HEAD.
     *
     * @param array<array-key, mixed> $config
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< .merge_file_bxS1IY.
     * @param array<array-key, mixed> $config
     *                                        =======
     *                                        <<<<<<< .merge_file_avv74w.
     * @param array<array-key, mixed> $config
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param array<array-key, mixed> $config
     *                                        =======
     * @param array<mixed, mixed>     $config
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_1D6iYr
     *
     * >>>>>>> .merge_file_GjSVoo
     *
     * =======
     * <<<<<<< HEAD.
     * @param array<array-key, mixed> $config
     *                                        =======
     *                                        <<<<<<< .merge_file_R22bCP
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< .merge_file_bxS1IY.
     *                                        >>>>>>> .merge_file_gDEies.
     * @param array<array-key, mixed> $config
     *
     * <<<<<<< .merge_file_R22bCP
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param array<array-key, mixed> $config
     *                                        >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_gDEies
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<array-key, mixed> $config
     *                                        >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_LNNhuP
>>>>>>> 77b1e2748 (first)
     * @return array<string, mixed>
     */
    private function normalizeConfigArray(array $config): array
    {
        $normalized = [];

        foreach ($config as $key => $value) {
            $normalized[(string) $key] = $value;
        }

        return $normalized;
    }
}
