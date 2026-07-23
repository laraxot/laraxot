<?php

declare(strict_types=1);

/**
 * @see https://github.com/barryvdh/laravel-translation-manager/blob/master/src/Translator.php
 */

namespace Modules\Lang\Actions;

use Illuminate\Events\Dispatcher;
use Illuminate\Translation\Translator as LaravelTranslator;
use Modules\Lang\Models\Translation;
use Spatie\QueueableAction\QueueableAction;

class TranslatorAction extends LaravelTranslator
{
    use QueueableAction;

    /** @var Dispatcher */
    protected $events;

    /**
     * Get the translation for the given key.
     *
     * @param array<string, mixed> $replace
     *
     * @return string|array<array-key, mixed>
     */
    public function get(mixed $key, array $replace = [], mixed $locale = null, mixed $fallback = true): string|array
    {
        $result = parent::get($key, $replace, $locale, $fallback);
        if ($result === $key) {
            $this->notifyMissingKey($key);
            $result = parent::get($key, $replace, $locale, $fallback);
        }

        if (is_array($result)) {
            return $result;
        }

        if (! is_string($result)) {
            return (string) $key;
        }

        return $result;
    }

    public function execute(): void
    {
    }

    protected function notifyMissingKey(string $key): void
    {
        $lang = app()->getLocale();
        [$namespace, $group, $item] = $this->parseKey($key);
        $data = [
            'lang' => $lang,
            'namespace' => $namespace,
            'group' => $group,
            'item' => $item,
        ];
        Translation::firstOrCreate($data);
    }
}
