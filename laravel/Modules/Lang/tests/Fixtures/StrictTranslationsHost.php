<?php

declare(strict_types=1);

namespace Modules\Lang\Tests\Fixtures;

use Modules\Lang\Models\BaseModel;
use Modules\Lang\Models\Traits\HasStrictTranslations;

final class StrictTranslationsHost extends BaseModel
{
    use HasStrictTranslations;

    /** @var list<string> */
    public array $translatable = ['title'];

    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'translations';

    public mixed $forcedTranslation = null;

    protected function spatieGetTranslation(string $key, string $locale, bool $useFallbackLocale = true): mixed
    {
        unset($key, $locale, $useFallbackLocale);

        return $this->forcedTranslation;
    }
}
