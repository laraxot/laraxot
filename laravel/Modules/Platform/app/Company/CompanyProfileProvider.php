<?php

declare(strict_types=1);

namespace Modules\Platform\Company;

use Modules\Platform\Settings\SettingsRepository;
use Modules\Platform\ValueObjects\CompanyProfile;
use Safe\Exceptions\FilesystemException;

use function Safe\file_get_contents;

/**
 * Risolve l'anagrafica dell'azienda emittente leggendo le impostazioni (chiave "company.profile"),
 * con fallback ai default dell'installazione. Consumato dai PDF di preventivo e fattura.
 */
final class CompanyProfileProvider
{
    /** @var string */
    public const SETTINGS_KEY = 'company.profile';

    public function __construct(private readonly SettingsRepository $settings) {}

    public function current(): CompanyProfile
    {
        $stored = $this->settings->getJson(self::SETTINGS_KEY) ?? [];

        return CompanyProfile::fromArray($stored, self::defaults());
    }

    /**
     * Default dell'installazione principale (retro-compatibili con l'intestazione storica,
     * prima cablata nel codice del PDF fattura).
     */
    public static function defaults(): CompanyProfile
    {
        return new CompanyProfile(
            legalName: 'Davide Cavallini',
            vatNumber: 'IT04914550274',
            taxCode: 'CVLDVD87M23L736P',
            taxRegime: 'RF19',
            street: 'Via del Musonetto 4',
            postalCode: '30033',
            city: 'Noale',
            province: 'VE',
            country: 'Italia',
            email: 'g.florian@cavalliniservice.com',
            phone: '+39 39 11 35 25 26',
            logoPath: null,
        );
    }

    /**
     * Logo come data URI (base64) pronto per dompdf, oppure null se non impostato o illeggibile.
     */
    public function logoDataUri(): ?string
    {
        $path = $this->current()->logoPath;

        if ($path === null || ! is_file($path) || ! is_readable($path)) {
            return null;
        }

        try {
            $contents = file_get_contents($path);
        } catch (FilesystemException) {
            return null;
        }

        $mime = match (strtolower((string) pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
