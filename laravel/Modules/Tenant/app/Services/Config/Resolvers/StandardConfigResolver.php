<?php

declare(strict_types=1);

namespace Modules\Tenant\Services\Config\Resolvers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Modules\Tenant\Services\Config\Contracts\ConfigResolverInterface;
use Modules\Tenant\Services\TenantService;

/**
 * Resolves standard tenant configuration by merging original and tenant-specific configs.
 */
class StandardConfigResolver implements ConfigResolverInterface
{
    public function canResolve(string $key): bool
    {
        // This is the fallback resolver, it can handle any key
        return true;
    }

    /**
     * @param  string|int|array<string, mixed>|null  $default
     * @return float|int|string|array<mixed>|null
     */
    public function resolve(string $key, string|int|array|null $default = null): float|int|string|array|null
    {
        $group = $this->extractGroup($key);

        $originalConf = $this->getOriginalConfig($group);
        $extraConf = $this->getTenantConfig($group);

        // Handle database configuration specially
        if ($key === 'database') {
            $databaseResolver = new DatabaseConfigResolver();
            $resolvedDatabaseConfig = $databaseResolver->resolve($key, $extraConf);
            $extraConf = is_array($resolvedDatabaseConfig) ? $resolvedDatabaseConfig : [];
        }

        $mergedConf = collect($originalConf)->merge($extraConf)->all();
        Config::set($group, $mergedConf);

        $result = config($key);

        if ($result === null && $default !== null) {
            $this->handleMissingConfig($key, $group, $extraConf, $default);
        }

        if (! is_numeric($result) && ! is_string($result) && ! is_array($result) && $result !== null) {
            throw new Exception('Invalid configuration type for key: '.$key);
        }

        return $result;
    }

    private function extractGroup(string $key): string
    {
        return explode('.', $key)[0];
    }

    /**
     * @return array<string, mixed>
     */
    private function getOriginalConfig(string $group): array
    {
        $config = config($group);
        if (! is_array($config)) {
            return [];
        }

        /** @var array<string, mixed> $result */
        $result = [];
        foreach ($config as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    private function getTenantConfig(string $group): array
    {
        $tenantName = TenantService::getName();
        $configName = str_replace('/', '.', $tenantName).'.'.$group;
        $config = config($configName);
        if (! is_array($config)) {
            return [];
        }

        /** @var array<string, mixed> $result */
        $result = [];
        foreach ($config as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $extraConf
     * @param  string|int|array<string, mixed>|null  $default
     */
    private function handleMissingConfig(
        string $key,
        string $group,
        array $extraConf,
        string|int|array|null $default
    ): void {
        $index = Str::after($key, $group.'.');
        // Side-effect reserved for future persist of defaults into $extraConf
        Arr::set($extraConf, $index, $default);

        throw new Exception('Configuration key not found: '.$key);
    }
}
