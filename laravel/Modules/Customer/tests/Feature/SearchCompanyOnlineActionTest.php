<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Actions\SearchCompanyOnlineAction;
use Modules\Customer\Lookup\CompanyLookupProviderInterface;
use Modules\Customer\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/Customers/SearchCompanyOnlineTest.php.
 * Namespace adattato: Modules\Customers\Application\{Actions,Lookup} -> Modules\Customer\{Actions,Lookup}.
 * Nessun accesso DB: verifica solo la normalizzazione dei candidati del provider.
 */
uses(TestCase::class);

/**
 * Deliberately typed as `list<mixed>`: some tests feed malformed candidates
 * (a bare string, missing keys) to exercise SearchCompanyOnlineAction's
 * defensive `is_array($candidate)` / legal-name guards.
 *
 * @param  list<mixed>  $candidates
 */
function customerFakeCompanyProvider(array $candidates): CompanyLookupProviderInterface
{
    return new class($candidates) implements CompanyLookupProviderInterface
    {
        /** @param list<mixed> $candidates */
        public function __construct(private array $candidates) {}

        /**
         * @return list<mixed>
         */
        public function search(string $query): array
        {
            return $this->candidates;
        }
    };
}

it('normalizes provider candidates into typed results, dropping the invalid ones', function (): void {
    $action = new SearchCompanyOnlineAction(customerFakeCompanyProvider([
        ['legal_name' => 'Idraulica Rossi SRL', 'vat_number' => 'IT01234567890', 'city' => 'Trento', 'phone' => ''],
        ['legal_name' => '', 'vat_number' => 'IT999'], // no name -> dropped
        'garbage',                                      // not an array -> dropped
        ['vat_number' => 'IT000'],                      // missing name -> dropped
    ]));

    $results = $action->execute('rossi idraulica');

    expect($results)->toHaveCount(1);
    expect($results[0]->legalName)->toBe('Idraulica Rossi SRL');
    expect($results[0]->vatNumber)->toBe('IT01234567890');
    expect($results[0]->city)->toBe('Trento');
    expect($results[0]->phone)->toBeNull(); // empty string normalized to null
    expect($results[0]->toArray())->toHaveKey('postal_code');
});

it('returns an empty list for a blank query without hitting the provider', function (): void {
    $provider = new class() implements CompanyLookupProviderInterface
    {
        public bool $called = false;

        public function search(string $query): array
        {
            $this->called = true;

            return [];
        }
    };

    $results = (new SearchCompanyOnlineAction($provider))->execute('   ');

    expect($results)->toBe([]);
    expect($provider->called)->toBeFalse();
});

it('caps the number of returned candidates at five', function (): void {
    $many = [];
    for ($i = 0; $i < 9; $i++) {
        $many[] = ['legal_name' => "Azienda {$i}"];
    }

    $results = (new SearchCompanyOnlineAction(customerFakeCompanyProvider($many)))->execute('azienda');

    expect($results)->toHaveCount(5);
});
