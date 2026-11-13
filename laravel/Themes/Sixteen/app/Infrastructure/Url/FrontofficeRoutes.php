<?php

declare(strict_types=1);

namespace Themes\Sixteen\Infrastructure\Url;

/**
 * Builder URL FO su rotte Folio `container0.*` — un solo posto riusabile.
 *
 * Caller: search-modal, FAQ, ticket-list/detail, header area personale, CTA.
 * Nessun altro file espone helper tipizzati per questi path (ex inline route()).
 * Schema: solo named route Laravel `container0.*` (nessuna scrittura DB).
 * User: «devi sempre cercare di creare componenti riutilizzabili… bmad + second brain + filament blueprint» / «procedi».
 * Religione: docs/wiki/concepts/fo-folio-links-multilingua.md
 */
final class FrontofficeRoutes
{
    public static function index(string $container0, bool $absolute = true): string
    {
        return route('container0.index', ['container0' => $container0], $absolute);
    }

    public static function view(string $container0, string $slug0, bool $absolute = true): string
    {
        return route('container0.view', [
            'container0' => $container0,
            'slug0' => $slug0,
        ], $absolute);
    }

    public static function ticketsIndex(bool $absolute = true): string
    {
        return self::index('tickets', $absolute);
    }

    public static function ticketsCreate(bool $absolute = true): string
    {
        return self::view('tickets', 'create', $absolute);
    }

    public static function ticketsTrack(bool $absolute = true): string
    {
        return self::view('tickets', 'track', $absolute);
    }

    public static function ticketsShow(string|int $ticketId, bool $absolute = true): string
    {
        return self::view('tickets', (string) $ticketId, $absolute);
    }

    /** Ricerca globale del sito (`/{lang}/ricerca?q=`, CMS ricerca.index.json, SearchFrontOfficeAction). */
    public static function searchIndex(bool $absolute = true): string
    {
        return self::index('ricerca', $absolute);
    }

    public static function faqIndex(bool $absolute = true): string
    {
        return self::index('domande-frequenti', $absolute);
    }

    public static function blogIndex(bool $absolute = true): string
    {
        return self::index('blog', $absolute);
    }

    public static function eventsIndex(bool $absolute = true): string
    {
        return self::index('events', $absolute);
    }

    public static function servicesIndex(bool $absolute = true): string
    {
        return self::index('services', $absolute);
    }

    public static function personalArea(string $slug0, bool $absolute = true): string
    {
        return self::view('area-personale', $slug0, $absolute);
    }

    public static function personalAreaPratiche(bool $absolute = true): string
    {
        return self::personalArea('pratiche', $absolute);
    }

    public static function personalAreaNotifiche(bool $absolute = true): string
    {
        return self::personalArea('notifiche', $absolute);
    }

    public static function personalAreaImpostazioni(bool $absolute = true): string
    {
        return self::personalArea('impostazioni', $absolute);
    }

    public static function personalAreaSeguite(bool $absolute = true): string
    {
        return self::personalArea('seguite', $absolute);
    }
}
