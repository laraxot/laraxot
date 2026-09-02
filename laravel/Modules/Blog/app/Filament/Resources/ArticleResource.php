<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources;

use Filament\Schemas\Components\Component;
use Modules\Blog\Filament\Resources\ArticleResource\Schemas\ArticleFormSchema;
use Modules\Blog\Models\Article;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Webmozart\Assert\Assert;

class ArticleResource extends XotBaseResource
{
    // use Translatable; // Temporarily disabled until lara-zeus package is working

    protected static ?string $model = Article::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    // protected static \BackedEnum|string|null $navigationIcon = 'icon-article';

    /**
     * @return array<int, string>
     */
    public static function getTranslatableLocales(): array
    {
        return ['it', 'en'];
    }

    /**
     * @return array<int|string, Component>
     */
    public static function getFormFields(): array
    {
        return ArticleFormSchema::build();
    }

    /**
     * @return array<int|string, Component>
     */
    public static function getFormSchema(): array
    {
        /** @var array<int|string, Component> $fields */
        $fields = static::getFormFields();
        Assert::isArray($fields, 'getFormFields must return array');

        return $fields;
    }
}
