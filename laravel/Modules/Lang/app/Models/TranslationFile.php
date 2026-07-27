<?php

declare(strict_types=1);

/**
 * @see https://github.com/barryvdh/laravel-translation-manager/blob/master/src/Models/Translation.php
 */

namespace Modules\Lang\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Modules\Lang\Actions\GetAllTranslationAction;
use Modules\Lang\Database\Factories\TranslationFileFactory;
use Modules\Xot\Contracts\ProfileContract;

use function Safe\json_encode;

use Sushi\Sushi;

/**
 * @property string|null                  $key
 * @property string|null                  $path
 * @property string|null                  $id
 * @property string|null                  $name
 * @property array<array-key, mixed>|null $content
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 *
 * @method static TranslationFileFactory          factory($count = null, $state = [])
 * @method static Builder<static>|TranslationFile newModelQuery()
 * @method static Builder<static>|TranslationFile newQuery()
 * @method static Builder<static>|TranslationFile query()
 * @method static Builder<static>|TranslationFile whereContent($value)
 * @method static Builder<static>|TranslationFile whereId($value)
 * @method static Builder<static>|TranslationFile whereKey($value)
 * @method static Builder<static>|TranslationFile whereName($value)
 * @method static Builder<static>|TranslationFile wherePath($value)
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class TranslationFile extends BaseModel
{
    use Sushi;

    protected $fillable = [
        'id',
        'name',
        'path',
        'content',
    ];

    /** @var array<string, string> */
    protected array $form = [
        'key' => 'string',
        'path' => 'string',
        'id' => 'string',
        'name' => 'string',
        'content' => 'json',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getRows(): array
    {
        if ($this->isRunningIdeHelper()) {
            return [];
        }

        try {
            return $this->loadTranslationDataWithErrorHandling();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('TranslationFile::getRows failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [];
        }
    }

    /**
     * Carica i dati di traduzione con error handling robusto.
     *
     * @throws \Throwable
     *
     * @return array<int, array<string, mixed>>
     */
    private function loadTranslationDataWithErrorHandling(): array
    {
        $files = app(GetAllTranslationAction::class)->execute();

        /** @var array<int, array<string, mixed>> $result */
        $result = Arr::map($files, function ($item) {
            if (! is_array($item)) {
                return [];
            }

            $key = $item['key'] ?? null;
            $keyStr = is_string($key) ? $key : (string) $key;
            $item['id'] = isset($item['key']) ? $keyStr : '';

            $pathValue = $item['path'] ?? null;
            $pathStr = is_string($pathValue) ? $pathValue : (string) $pathValue;
            $item['name'] = isset($item['path']) ? basename($pathStr, '.php') : '';

            if (isset($item['path'])) {
                $path = $pathStr;
                if (File::exists($path)) {
                    $item['content'] = $this->loadFileContent($path);
                } else {
                    $item['content'] = '';
                }
            } else {
                $item['content'] = '';
            }

            return $item;
        });

        return $result;
    }

    /**
     * Carica il contenuto di un file di traduzione con fallback.
     */
    private function loadFileContent(string $path): string
    {
        try {
            $content = File::getRequire($path);

            return json_encode($content) ?: '';
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::debug('Failed to load translation file', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    private function isRunningIdeHelper(): bool
    {
        if (defined('PHPSTAN_RUNNING')) {
            return true;
        }

        $argv = $_SERVER['argv'] ?? [];

        return is_array($argv) && in_array('ide-helper:models', $argv, true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
