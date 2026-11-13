<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Contacts;

use Modules\Tenant\Datas\EnteData;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_match;

/**
 * Gruppi dei riquadri contatti ("Contatta il comune", "Hai bisogno di aiuto?") pronti da stampare (DCP-289).
 *
 * Input: il nodo `contacts` del JSON del blocco, con i gruppi `main` e `problems`, ciascuno `title` e `items[]` (`icon`, `label`, `url`,
 * `element`). Una voce puo' dichiarare `"ente": "<campo>"` (`toll_free`, `phone`, `email`, `pec`, `dpo_email`): link e testo vengono da
 * {@see EnteData} e `:value` nell'etichetta diventa il testo del dato; se il dato manca la voce sparisce.
 * Una voce scritta a mano sparisce se non ha URL o ha `#` (salvo `$dropDeadLinks = false`, per le repliche di prova del riferimento) o se
 * l'URL non e' sicuro (ammessi: percorso, http, https, ancora, mailto, tel numerico). Un gruppo senza titolo o senza voci sparisce:
 * se non resta nessun gruppo il chiamante non stampa nemmeno il riquadro.
 */
class ResolveContactGroupsAction
{
    use QueueableAction;

    /**
     * @param  array<array-key, mixed>  $contacts
     * @return list<array{title: string, items: list<array<string, mixed>>}>
     */
    public function execute(array $contacts, EnteData $ente, bool $dropDeadLinks = true): array
    {
        $groups = [];
        foreach (['main', 'problems'] as $key) {
            $group = $contacts[$key] ?? null;
            if (! is_array($group)) {
                continue;
            }

            $title = $group['title'] ?? null;
            $items = $this->items($group['items'] ?? null, $ente, $dropDeadLinks);
            if (is_string($title) && trim($title) !== '' && $items !== []) {
                $groups[] = ['title' => $title, 'items' => $items];
            }
        }

        return $groups;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function items(mixed $items, EnteData $ente, bool $dropDeadLinks): array
    {
        if (! is_array($items)) {
            return [];
        }

        $resolved = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $item = $this->resolveItem($item, $ente, $dropDeadLinks);
            if ($item !== null) {
                $resolved[] = $item;
            }
        }

        return $resolved;
    }

    /**
     * @param  array<array-key, mixed>  $item
     * @return array<string, mixed>|null
     */
    private function resolveItem(array $item, EnteData $ente, bool $dropDeadLinks): ?array
    {
        $normalized = [];
        foreach ($item as $key => $value) {
            $normalized[(string) $key] = $value;
        }

        $field = $normalized['ente'] ?? null;
        unset($normalized['ente']);

        if (is_string($field) && $field !== '') {
            $link = $ente->link($field);
            if ($link === null) {
                return null;
            }

            $label = is_string($normalized['label'] ?? null) ? $normalized['label'] : ':value';
            $normalized['label'] = str_replace(':value', $link['label'], $label);
            $normalized['url'] = $link['href'];

            return $normalized;
        }

        $url = trim(is_string($normalized['url'] ?? null) ? $normalized['url'] : '');
        if ($url === '' || $url === '#') {
            return $dropDeadLinks ? null : $normalized;
        }

        return $this->isSafeUrl($url) ? $normalized : null;
    }

    private function isSafeUrl(string $url): bool
    {
        if (str_starts_with($url, 'tel:')) {
            return preg_match('/^tel:\+?\d{3,15}$/', $url) === 1;
        }

        return preg_match('~^(/(?!/)|https?://|#[^\s]|mailto:)~i', $url) === 1;
    }
}
