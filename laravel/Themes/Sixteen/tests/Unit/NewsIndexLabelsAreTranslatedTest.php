<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

/**
 * Build the theme translation catalogue without booting the database-backed app test case.
 *
 * @return Closure(string): Translator
 */
$translatorFor = static function (string $locale): Translator {
    $langPath = dirname(__DIR__, 2).'/lang';
    $loader = new FileLoader(new Filesystem, $langPath);
    $loader->addNamespace('pub_theme', $langPath);

    return new Translator($loader, $locale);
};

/**
 * Audit front office 2026-10-27: `/en|de|es/blog` mostrava in italiano "Cerca", "Invio", "N notizie trovate" e la
 * categoria di ripiego "NOTIZIE"; `/en|de|es/events` la categoria "EVENTI". I fallback erano stringhe scritte nei
 * template: vivono nel file lang del tema, uno per lingua, cosi' una lingua nuova non eredita l'italiano.
 */
const NEWS_INDEX_KEYS = [
    'default_category', 'default_event_category', 'search_label', 'search_placeholder', 'search_submit', 'result_one', 'result_many',
    'feedback.title', 'feedback.legend', 'feedback.star_label', 'feedback.thanks',
    'feedback.positive.question', 'feedback.positive.answers.clear', 'feedback.positive.answers.complete',
    'feedback.positive.answers.progress', 'feedback.positive.answers.technical', 'feedback.positive.answers.other',
    'feedback.negative.question', 'feedback.negative.answers.clear', 'feedback.negative.answers.complete',
    'feedback.negative.answers.progress', 'feedback.negative.answers.technical', 'feedback.negative.answers.other',
    'feedback.detail.question', 'feedback.detail.label', 'feedback.detail.help', 'feedback.back', 'feedback.next',
];

it('translates the blog and events index labels in every language', function (string $locale, string $key) use ($translatorFor): void {
    $italian = $translatorFor('it')->get('pub_theme::news.index.'.$key, ['count' => 5]);
    $translated = $translatorFor($locale)->get('pub_theme::news.index.'.$key, ['count' => 5]);

    expect($translated)->not->toBe('pub_theme::news.index.'.$key)->and($translated)->not->toBe($italian);
})->with(function (): iterable {
    foreach (['en', 'de', 'es'] as $locale) {
        foreach (NEWS_INDEX_KEYS as $key) {
            yield "$locale $key" => [$locale, $key];
        }
    }
});

it('puts the number of results in the count message', function (string $locale) use ($translatorFor): void {
    expect($translatorFor($locale)->get('pub_theme::news.index.result_many', ['count' => 18]))->toContain('18');
})->with(['it', 'en', 'de', 'es']);
