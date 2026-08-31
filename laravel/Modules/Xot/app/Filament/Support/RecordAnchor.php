<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Support;

/**
 * Ancora HTML di una riga di elenco.
 *
 * Chi torna a una lista dopo avere aperto un record deve ritrovare la riga senza
 * scorrere: la riga espone `id="record-{chiave}"` e i link di ritorno appendono il
 * frammento corrispondente.
 *
 * Il prefisso serve: un id che inizia per cifra non e' selezionabile con un selettore
 * CSS ne' con `querySelector('#123')`. Il frammento va sempre in coda all'URL, dopo la
 * query string, altrimenti finisce dentro al valore del parametro.
 */
final class RecordAnchor
{
    public const string PREFIX = 'record-';

    /**
     * Valore dell'attributo id da mettere nel DOM.
     */
    public static function id(int|string $key): string
    {
        return self::PREFIX.$key;
    }

    /**
     * Frammento da appendere all'URL della lista.
     */
    public static function fragment(int|string $key): string
    {
        return '#'.self::id($key);
    }

    /**
     * Appende il frammento a un URL, senza toccare la query string.
     */
    public static function appendTo(string $url, int|string $key): string
    {
        if (str_contains($url, '#')) {
            return $url;
        }

        return $url.self::fragment($key);
    }
}
