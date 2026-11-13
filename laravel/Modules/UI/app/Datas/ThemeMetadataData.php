<?php

declare(strict_types=1);

namespace Modules\UI\Datas;

<<<<<<< HEAD
=======
use InvalidArgumentException;
>>>>>>> f5ffd8b4 (.)
use Spatie\LaravelData\Data;

/**
 * Metadati tema compilati: colori brand, scale spaziatura, breakpoint da design token.
 */
class ThemeMetadataData extends Data
{
    /**
     * @param  array<string, string>  $spacingUnits
     * @param  array<string, string>  $breakpoints
     */
    public function __construct(
        public readonly string $primaryColorHex,
        public readonly string $secondaryColorHex,
        public readonly array $spacingUnits = ['sm' => '1rem', 'md' => '2rem', 'lg' => '4rem'],
        public readonly array $breakpoints = [
            'sm' => '640px',
            'md' => '768px',
            'lg' => '1024px',
        ],
    ) {}

    /**
<<<<<<< HEAD
<<<<<<< .merge_file_xf6q6A
<<<<<<< .merge_file_589eRW
     * @throws \InvalidArgumentException se la chiave non esiste
=======
     * @throws InvalidArgumentException se la chiave non esiste
>>>>>>> .merge_file_sbJ7OM
=======
     * @throws InvalidArgumentException se la chiave non esiste
>>>>>>> .merge_file_RQp3rP
=======
     * @throws \InvalidArgumentException se la chiave non esiste.
>>>>>>> f5ffd8b4 (.)
     */
    public function getSpacing(string $key): string
    {
        if (! isset($this->spacingUnits[$key])) {
<<<<<<< HEAD
            throw new \InvalidArgumentException("Invalid spacing unit key: {$key}");
=======
            throw new InvalidArgumentException("Invalid spacing unit key: {$key}");
>>>>>>> f5ffd8b4 (.)
        }

        return $this->spacingUnits[$key];
    }
}
