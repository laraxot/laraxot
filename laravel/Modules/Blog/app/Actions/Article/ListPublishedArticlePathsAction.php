<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Illuminate\Support\Collection;
use Modules\Blog\View\Composers\Queries\ThemeArticleQueries;
use Modules\Seo\Contracts\SitemapPathsContract;
use Spatie\QueueableAction\QueueableAction;

/**
 * Percorsi di dettaglio di post ed eventi pubblicati, per la sitemap di Seo (DCP-273).
 *
 * /{lang}/blog/{slug} e /{lang}/events/{slug} li serve la pagina Folio del tema `[container0]/[slug0]/index.blade.php` tramite
 * `Cms\ResolvePageAction`, che chiede a `ThemeArticleQueries` gli stessi contenuti: qui si riusa quella query, non se ne scrive una nuova.
 */
final class ListPublishedArticlePathsAction implements SitemapPathsContract
{
    use QueueableAction;

    /**
     * @return list<string>
     */
    public function execute(): array
    {
        $queries = app(ThemeArticleQueries::class);

        return [
            ...$this->paths('blog', $queries->publishedPostSlugs()),
            ...$this->paths('events', $queries->publishedEventSlugs()),
        ];
    }

    /**
     * @param Collection<int, mixed> $slugs
     *
     * @return list<string>
     */
    private function paths(string $section, Collection $slugs): array
    {
        $paths = [];
        foreach ($slugs as $slug) {
            if (is_string($slug) && $slug !== '') {
                $paths[] = $section.'/'.rawurlencode($slug);
            }
        }

        return $paths;
    }
}
