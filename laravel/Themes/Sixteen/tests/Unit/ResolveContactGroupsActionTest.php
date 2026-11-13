<?php

declare(strict_types=1);

use Modules\Tenant\Datas\EnteData;
use Tests\TestCase;
use Themes\Sixteen\Actions\Contacts\ResolveContactGroupsAction;

uses(TestCase::class);

/*
 * DCP-289: i riquadri "Contatta il comune" / "Hai bisogno di aiuto?" leggono i dati dell'ente da una sola sorgente. Una voce con la chiave
 * `ente` prende link e testo dalla sorgente e sparisce se il dato manca; una voce senza URL o con `#` sparisce (tranne nelle repliche di
 * prova); un gruppo senza voci, e un riquadro senza gruppi, spariscono. I valori sono sintetici.
 */
/**
 * @param  array<string, mixed>  $contacts
 * @return list<array{title: string, items: list<array<string, mixed>>}>
 */
function resolveContactGroupsForTest(array $contacts, EnteData $ente, bool $dropDeadLinks = true): array
{
    return (new ResolveContactGroupsAction)->execute($contacts, $ente, $dropDeadLinks);
}

const DCP289_HELP_ITEMS = [
    ['icon' => 'it-help-circle', 'label' => 'Leggi le domande frequenti', 'url' => '/domande-frequenti'],
    ['icon' => 'it-hearing', 'label' => 'Chiama il numero verde :value', 'ente' => 'toll_free'],
];

it('shows nothing when the ente gave no data and the items depend on it', function (): void {
    $groups = resolveContactGroupsForTest(['main' => ['title' => 'Contatta il comune', 'items' => [DCP289_HELP_ITEMS[1]]]], new EnteData);

    expect($groups)->toBe([]);
});

it('drops only the item that depends on the missing datum and keeps the others', function (): void {
    $groups = resolveContactGroupsForTest(['main' => ['title' => 'Contatta il comune', 'items' => DCP289_HELP_ITEMS]], new EnteData);

    expect($groups)->toHaveCount(1)
        ->and($groups[0]['title'])->toBe('Contatta il comune')
        ->and($groups[0]['items'])->toHaveCount(1)
        ->and($groups[0]['items'][0]['url'])->toBe('/domande-frequenti');
});

it('takes link and text from the ente and replaces :value in the label', function (): void {
    $ente = new EnteData(toll_free: '800 000 000', pec: 'ente@pec.example.test');
    $items = [
        DCP289_HELP_ITEMS[1],
        ['icon' => 'it-mail', 'label' => 'Scrivi alla PEC :value', 'ente' => 'pec', 'element' => 'contacts'],
    ];

    $groups = resolveContactGroupsForTest(['main' => ['title' => 'Aiuto', 'items' => $items]], $ente);

    expect($groups[0]['items'])->toEqual([
        ['icon' => 'it-hearing', 'label' => 'Chiama il numero verde 800 000 000', 'url' => 'tel:800000000'],
        ['icon' => 'it-mail', 'label' => 'Scrivi alla PEC ente@pec.example.test', 'url' => 'mailto:ente@pec.example.test', 'element' => 'contacts'],
    ]);
});

it('ignores an ente reference to a field that is not a link', function (): void {
    $items = [['icon' => 'it-pa', 'label' => ':value', 'ente' => 'address']];

    expect(resolveContactGroupsForTest(['main' => ['title' => 'x', 'items' => $items]], new EnteData(address: 'Via di prova 1')))->toBe([]);
});

it('drops items with an empty or hash url, unless asked to keep them for the design replicas', function (): void {
    $items = [
        ['label' => 'Vuota', 'url' => ''],
        ['label' => 'Senza url'],
        ['label' => 'Diesis', 'url' => '#'],
        ['label' => 'Vera', 'url' => '/tickets/create'],
    ];
    $contacts = ['main' => ['title' => 'x', 'items' => $items]];

    expect(array_column(resolveContactGroupsForTest($contacts, new EnteData)[0]['items'], 'label'))->toBe(['Vera'])
        ->and(array_column(resolveContactGroupsForTest($contacts, new EnteData, false)[0]['items'], 'label'))->toBe(['Vuota', 'Senza url', 'Diesis', 'Vera']);
});

it('drops a group without title or without items, and keeps main before problems', function (): void {
    $contacts = [
        'problems' => ['title' => 'Problemi in citta', 'items' => [['label' => 'Segnala', 'url' => '/tickets/create']]],
        'main' => ['title' => '', 'items' => [['label' => 'Senza titolo', 'url' => '/x']]],
    ];

    $groups = resolveContactGroupsForTest($contacts, new EnteData);

    expect($groups)->toHaveCount(1)
        ->and($groups[0]['title'])->toBe('Problemi in citta');

    expect(resolveContactGroupsForTest(['main' => ['title' => 'x', 'items' => []]], new EnteData))->toBe([])
        ->and(resolveContactGroupsForTest([], new EnteData))->toBe([]);
});

it('does not trust a hand-written tel url whose number is not numeric', function (): void {
    $contacts = ['main' => ['title' => 'x', 'items' => [
        ['label' => 'Cattiva', 'url' => 'tel:abc'],
        ['label' => 'Javascript', 'url' => 'javascript:alert(1)'],
        ['label' => 'Buona', 'url' => 'tel:800000000'],
    ]]];

    expect(array_column(resolveContactGroupsForTest($contacts, new EnteData)[0]['items'], 'label'))->toBe(['Buona']);
});
