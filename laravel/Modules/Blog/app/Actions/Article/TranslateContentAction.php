<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Modules\Xot\Actions\GetModelByModelTypeAction;

use function Safe\json_encode;

use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class TranslateContentAction
{
    use QueueableAction;

    /**
     * Esegue la traduzione dei contenuti di un articolo.
     *
     * @param list<string>        $locales
     * @param array<string,mixed> $data
     * @param class-string        $class
     */
    public function execute(string $modelClass, string $articleId, array $locales, array $data, string $class): void
    {
        $model = app(GetModelByModelTypeAction::class)->execute($modelClass, $articleId);
        Assert::isInstanceOf($model, $class, '['.__LINE__.']['.__FILE__.']');
        /** @var Article $model */

        /** @var array<string, mixed> $modelContents */
        $modelContents = $model->toArray();

        $this->translateJsonBlock($model, $modelContents, $locales, $data, 'content_blocks');
        $this->translateArrayBlock($model, $modelContents, $locales, $data, 'sidebar_blocks');
        $this->translateArrayBlock($model, $modelContents, $locales, $data, 'footer_blocks');

        $model->update();
    }

    /**
     * @param array<string, mixed> $modelContents
     * @param list<string>         $locales
     * @param array<string, mixed> $data
     */
    private function translateJsonBlock(Article $model, array $modelContents, array $locales, array $data, string $field): void
    {
        if (! ($data[$field] ?? false)) {
            return;
        }

        /** @var array<mixed> $modelContent */
        $modelContent = $modelContents[$field];
        $modelContent = $this->fillMissingLocales($modelContent, $locales, 'it', '');
        $model->{$field} = json_encode($modelContent, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<string, mixed> $modelContents
     * @param list<string>         $locales
     * @param array<string, mixed> $data
     */
    private function translateArrayBlock(Article $model, array $modelContents, array $locales, array $data, string $field): void
    {
        if (! ($data[$field] ?? false)) {
            return;
        }

        /** @var array<string, mixed> $modelContent */
        $modelContent = $modelContents[$field];
        $modelContent = $this->fillMissingLocales($modelContent, $locales, 'it', []);
        $model->{$field} = $modelContent;
    }

    /**
     * @param array<mixed> $modelContent
     * @param list<string> $locales
     *
     * @return array<mixed>
     */
    private function fillMissingLocales(array $modelContent, array $locales, string $sourceLocale, mixed $default): array
    {
        foreach ($locales as $locale) {
            if (! isset($modelContent[$locale])) {
                $modelContent[$locale] = $modelContent[$sourceLocale] ?? $default;
            }
        }

        return $modelContent;
    }
}
