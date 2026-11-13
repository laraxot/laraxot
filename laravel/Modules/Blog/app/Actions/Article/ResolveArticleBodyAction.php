<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Spatie\QueueableAction\QueueableAction;

/**
 * Public article and event body: every editor block (ArticleContent: title, paragraph, image_spatie, images_gallery,
 * rating, chart, plus the legacy `image` used by seeders), normalised for the UI block renderer.
 *
 * - `image_spatie` becomes an `image` block with a real URL (media collection named by `img_uuid`). Image URLs are
 *   emitted as `src`, not `url`: the block renderer localizes `url` keys as page links (/it/...), which breaks images.
 * - One image is the lead figure: the block matching `main_image_url`, or the first image when there is none.
 * - A title block repeating the article title is dropped; heading levels are h2-h6 (the page owns the h1).
 * - Empty paragraphs and images without a URL are dropped; other block types pass through unchanged.
 *
 * Callers: Themes/Sixteen blocks/partials/article-body (blog and events detail).
 */
final class ResolveArticleBodyAction
{
    use QueueableAction;

    private const array HEADING_LEVELS = ['h2', 'h3', 'h4', 'h5', 'h6'];

    /**
     * @return array{
     *     figure: array{src: string, alt: string, caption: string, credit: string}|null,
     *     blocks: list<array{type: string, data: array<string, mixed>}>
     * }
     */
    public function execute(Article $article): array
    {
        $title = $article->getTitle();
        $raw = $article->getAttribute('content_blocks');
        $blocks = [];
        foreach (is_array($raw) ? $raw : [] as $block) {
            $normalized = is_array($block) ? $this->normalize($article, $block, $title) : null;
            if ($normalized !== null) {
                $blocks[] = $normalized;
            }
        }

        $mainImage = $article->getAttribute('main_image_url');
        $mainImage = is_string($mainImage) ? $mainImage : '';
        $figureIndex = $this->figureIndex($blocks, $mainImage);
        $figure = $mainImage !== '' ? ['src' => $mainImage, 'alt' => $title, 'caption' => '', 'credit' => ''] : null;
        if ($figureIndex !== null) {
            $data = $blocks[$figureIndex]['data'];
            $figure = [
                'src' => $this->text($data, 'src'),
                'alt' => $this->text($data, 'alt'),
                'caption' => $this->text($data, 'caption'),
                'credit' => $this->text($data, 'credit'),
            ];
            unset($blocks[$figureIndex]);
        }

        return ['figure' => $figure, 'blocks' => array_values($blocks)];
    }

    /**
     * @param  array<array-key, mixed>  $block
     * @return array{type: string, data: array<string, mixed>}|null
     */
    private function normalize(Article $article, array $block, string $title): ?array
    {
        $type = $block['type'] ?? null;
        $data = $this->stringKeyed($block['data'] ?? null);
        if (! is_string($type) || $type === '') {
            return null;
        }

        return match ($type) {
            'image', 'image_spatie' => $this->image($article, $data, $title),
            'paragraph' => $this->text($data, 'text') === '' ? null : ['type' => 'paragraph', 'data' => $data],
            'title' => $this->title($data, $title),
            default => ['type' => $type, 'data' => $data],
        };
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{type: string, data: array<string, mixed>}|null
     */
    private function image(Article $article, array $data, string $title): ?array
    {
        $url = $this->text($data, 'url');
        $uuid = $this->text($data, 'img_uuid');
        if ($url === '' && $uuid !== '' && $article->exists) {
            $url = $article->getFirstMediaUrl($uuid);
        }
        if ($url === '') {
            return null;
        }
        $caption = $this->text($data, 'caption');
        $alt = $this->text($data, 'alt');

        return ['type' => 'image', 'data' => [
            'src' => $url,
            'alt' => $alt !== '' ? $alt : ($caption !== '' ? $caption : $title),
            'caption' => $caption,
            'credit' => $this->text($data, 'credit'),
        ]];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{type: string, data: array<string, mixed>}|null
     */
    private function title(array $data, string $articleTitle): ?array
    {
        $text = trim($this->text($data, 'text'));
        if ($text === '' || mb_strtolower($text) === mb_strtolower(trim($articleTitle))) {
            return null;
        }
        $level = $this->text($data, 'level');
        $data['text'] = $text;
        $data['level'] = in_array($level, self::HEADING_LEVELS, true) ? $level : 'h2';

        return ['type' => 'title', 'data' => $data];
    }

    /**
     * @param  list<array{type: string, data: array<string, mixed>}>  $blocks
     */
    private function figureIndex(array $blocks, string $mainImage): ?int
    {
        foreach ($blocks as $index => $block) {
            if ($block['type'] === 'image' && ($mainImage === '' || $this->text($block['data'], 'src') === $mainImage)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function text(array $data, string $key): string
    {
        $value = $data[$key] ?? null;

        return is_string($value) ? $value : '';
    }

    /**
     * @return array<string, mixed>
     */
    private function stringKeyed(mixed $value): array
    {
        $result = [];
        foreach (is_array($value) ? $value : [] as $key => $item) {
            if (is_string($key)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
