<?php

declare(strict_types=1);

namespace Modules\Platform\Settings;

use Illuminate\Support\Facades\Schema;
use Modules\Platform\Models\PlatformSetting;

final class SettingsRepository
{
    /**
     * @return array<string, mixed>|null
     */
    public function getJson(string $key): ?array
    {
        if (! Schema::hasTable('platform_settings')) {
            return null;
        }

        /** @var PlatformSetting|null $setting */
        $setting = PlatformSetting::query()->where('key', $key)->first();

        if (! ($setting instanceof PlatformSetting)) {
            return null;
        }

        $value = $setting->value_json;

        if (! is_array($value)) {
            return null;
        }

        $normalized = [];
        foreach ($value as $entryKey => $entryValue) {
            if (! is_string($entryKey)) {
                return null;
            }

            $normalized[$entryKey] = $entryValue;
        }

        return $normalized;
    }

    public function getString(string $key, ?string $default = null): ?string
    {
        $value = $this->getScalar($key);
        return is_string($value) ? $value : $default;
    }

    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->getScalar($key);
        return is_bool($value) ? $value : $default;
    }

    public function getInt(string $key, int $default = 0): int
    {
        $value = $this->getScalar($key);
        return is_int($value) ? $value : $default;
    }

    private function getScalar(string $key): mixed
    {
        if (! Schema::hasTable('platform_settings')) {
            return null;
        }

        /** @var PlatformSetting|null $setting */
        $setting = PlatformSetting::query()->where('key', $key)->first();

        if (! ($setting instanceof PlatformSetting)) {
            return null;
        }

        $value = $setting->value_json;

        if (is_array($value) && array_key_exists('value', $value)) {
            return $value['value'];
        }

        return $value;
    }
}

