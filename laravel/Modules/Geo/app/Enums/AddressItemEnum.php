<?php

declare(strict_types=1);

namespace Modules\Geo\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Traits\EnumTrait;

/**
 * Enum per i campi standard di indirizzo.
 *
 * I metadati UI arrivano da EnumTrait; le definizioni colonne restano
 * nell'hook getColumnDefinitions() consumato dal trait.
 */
enum AddressItemEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;
    case NAME = 'name';
    case ROUTE = 'route';
    case STREET_NUMBER = 'street_number';
    case POSTAL_CODE = 'postal_code';
    case LOCALITY = 'locality';
    case ADMINISTRATIVE_AREA_LEVEL_3 = 'administrative_area_level_3'; // comune
    case ADMINISTRATIVE_AREA_LEVEL_2 = 'administrative_area_level_2'; // provincia
    case ADMINISTRATIVE_AREA_LEVEL_1 = 'administrative_area_level_1'; // regione
    case COUNTRY = 'country'; // Stato/Paese
    case LATITUDE = 'latitude';
    case LONGITUDE = 'longitude';
    case NOTES = 'notes';

    /**
     * Internal map of standard address column definitions.
     *
     * @return array<string, \Closure(Blueprint):void>
     */
    public static function getColumnDefinitions(): array
    {
        return [
            self::NAME->value => static function (Blueprint $table): void {
                $table->string(self::NAME->value)
                    ->nullable()
                    ->comment('Location name');
            },
            self::ROUTE->value => static function (Blueprint $table): void {
                $table->string(self::ROUTE->value)
                    ->nullable()
                    ->comment('Street name (Via/Piazza)');
            },
            self::STREET_NUMBER->value => static function (Blueprint $table): void {
                $table->string(self::STREET_NUMBER->value)
                    ->nullable()
                    ->comment('Street number');
            },
            self::LOCALITY->value => static function (Blueprint $table): void {
                $table->string(self::LOCALITY->value)
                    ->nullable()
                    ->comment('City/Municipality');
            },
            self::ADMINISTRATIVE_AREA_LEVEL_3->value => static function (Blueprint $table): void {
                $table->string(self::ADMINISTRATIVE_AREA_LEVEL_3->value)
                    ->nullable()
                    ->comment('Comune');
            },
            self::ADMINISTRATIVE_AREA_LEVEL_2->value => static function (Blueprint $table): void {
                $table->string(self::ADMINISTRATIVE_AREA_LEVEL_2->value)
                    ->nullable()
                    ->comment('Provincia');
            },
            self::ADMINISTRATIVE_AREA_LEVEL_1->value => static function (Blueprint $table): void {
                $table->string(self::ADMINISTRATIVE_AREA_LEVEL_1->value)
                    ->nullable()
                    ->comment('Regione');
            },
            self::COUNTRY->value => static function (Blueprint $table): void {
                $table->string(self::COUNTRY->value)
                    ->nullable()
                    ->comment('Country/Stato');
            },
            self::POSTAL_CODE->value => static function (Blueprint $table): void {
                $table->string(self::POSTAL_CODE->value)
                    ->nullable()
                    ->comment('CAP/Postal Code');
            },
            self::LATITUDE->value => static function (Blueprint $table): void {
                $table->decimal(self::LATITUDE->value, 10, 8)
                    ->nullable()
                    ->comment('Latitude coordinate');
            },
            self::LONGITUDE->value => static function (Blueprint $table): void {
                $table->decimal(self::LONGITUDE->value, 11, 8)
                    ->nullable()
                    ->comment('Longitude coordinate');
            },
            self::NOTES->value => static function (Blueprint $table): void {
                $table->text(self::NOTES->value)
                    ->nullable()
                    ->comment('General notes');
            },
        ];
    }

    /**
     * Add standard address columns plus legacy compatibility columns.
     */
    public static function columnsWithLegacy(Blueprint $table, ?XotBaseMigration $migration = null): void
    {
        self::columns($table, $migration);
        self::addLegacyColumns($table, $migration);
    }

    /**
     * Ensure standard address columns plus legacy columns in UPDATE context.
     */
    public static function updateColumnsWithLegacy(Blueprint $table, XotBaseMigration $migration): void
    {
        self::columnsWithLegacy($table, $migration);
    }

    /**
     * Add legacy compatibility columns.
     *
     * These fields maintain compatibility with older code that expects
     * generic field names like 'address', 'city', 'province', etc.
     *
     * @param  Blueprint  $table  The table blueprint
     * @param  XotBaseMigration|null  $migration  XotBaseMigration instance for UPDATE context
     */
    private static function addLegacyColumns(Blueprint $table, ?XotBaseMigration $migration = null): void
    {
        $legacyColumns = [
            'address' => static function (Blueprint $table): void {
                $table->text('address')
                    ->nullable()
                    ->comment('Legacy full address field');
            },
            'city' => static function (Blueprint $table): void {
                $table->string('city')
                    ->nullable()
                    ->comment('Legacy city field');
            },
            'province' => static function (Blueprint $table): void {
                $table->string('province')
                    ->nullable()
                    ->comment('Legacy province field');
            },
            'region' => static function (Blueprint $table): void {
                $table->string('region')
                    ->nullable()
                    ->comment('Legacy region field');
            },
            'cap' => static function (Blueprint $table): void {
                $table->string('cap')
                    ->nullable()
                    ->comment('Legacy CAP field');
            },
        ];

        foreach ($legacyColumns as $name => $definition) {
            if ($migration === null || ! $migration->hasColumn($name)) {
                $definition($table);
            }
        }
    }
}
