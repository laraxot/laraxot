<?php

declare(strict_types=1);

namespace Modules\Platform\ValueObjects;

/**
 * Anagrafica dell'azienda che possiede l'istanza (emittente/cedente su preventivi e fatture).
 *
 * Non esisteva un concetto centralizzato: questo VO lo rappresenta e viene risolto da
 * {@see \Modules\Platform\Company\CompanyProfileProvider} leggendo le impostazioni,
 * con fallback ai valori di default dell'installazione.
 */
final readonly class CompanyProfile
{
    public function __construct(
        public string $legalName,
        public string $vatNumber,
        public string $taxCode,
        public string $taxRegime,
        public string $street,
        public string $postalCode,
        public string $city,
        public string $province,
        public string $country,
        public string $email,
        public string $phone,
        public ?string $logoPath,
    ) {}

    /**
     * Costruisce il profilo da un array di impostazioni.
     *
     * Una chiave ASSENTE (o con valore non-stringa) eredita dal default: serve alla
     * retro-compatibilità dell'istanza principale quando non c'è ancora un profilo salvato.
     * Una chiave PRESENTE ma vuota resta vuota: un'anagrafica cliente completa (i seeder
     * salvano tutte le chiavi) non deve far trapelare i dati di default nei campi lasciati
     * vuoti (es. il C.F. di una ditta individuale).
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data, self $defaults): self
    {
        $string = static function (string $key, string $fallback) use ($data): string {
            if (array_key_exists($key, $data) && is_string($data[$key])) {
                return $data[$key];
            }

            return $fallback;
        };

        $logo = $data['logo_path'] ?? null;

        return new self(
            legalName: $string('legal_name', $defaults->legalName),
            vatNumber: $string('vat_number', $defaults->vatNumber),
            taxCode: $string('tax_code', $defaults->taxCode),
            taxRegime: $string('tax_regime', $defaults->taxRegime),
            street: $string('street', $defaults->street),
            postalCode: $string('postal_code', $defaults->postalCode),
            city: $string('city', $defaults->city),
            province: $string('province', $defaults->province),
            country: $string('country', $defaults->country),
            email: $string('email', $defaults->email),
            phone: $string('phone', $defaults->phone),
            logoPath: is_string($logo) && trim($logo) !== '' ? $logo : $defaults->logoPath,
        );
    }

    /**
     * Indirizzo su una riga per l'intestazione: "Via X 4 — 30033 Comune (PR) — Italia".
     */
    public function singleLineAddress(): string
    {
        $cityLine = trim($this->postalCode.' '.$this->city);
        if ($this->province !== '') {
            $cityLine = trim($cityLine.' ('.$this->province.')');
        }

        $parts = array_values(array_filter([
            $this->street !== '' ? $this->street : null,
            $cityLine !== '' ? $cityLine : null,
            $this->country !== '' ? $this->country : null,
        ]));

        return implode(' — ', $parts);
    }

    /**
     * Forma attesa dal template della fattura e del preventivo (chiave "supplier").
     *
     * @return array{legal_name: string, address: string, vat_number: string, tax_code: string, phone: string, email: string}
     */
    public function toSupplierArray(): array
    {
        return [
            'legal_name' => $this->legalName,
            'address' => $this->singleLineAddress(),
            'vat_number' => $this->vatNumber,
            'tax_code' => $this->taxCode,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }

    /**
     * Forma serializzabile per la persistenza nelle impostazioni.
     *
     * @return array{legal_name: string, vat_number: string, tax_code: string, tax_regime: string, street: string, postal_code: string, city: string, province: string, country: string, email: string, phone: string, logo_path: string|null}
     */
    public function toArray(): array
    {
        return [
            'legal_name' => $this->legalName,
            'vat_number' => $this->vatNumber,
            'tax_code' => $this->taxCode,
            'tax_regime' => $this->taxRegime,
            'street' => $this->street,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo_path' => $this->logoPath,
        ];
    }
}
