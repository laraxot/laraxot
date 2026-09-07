<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Nwidart\Modules\Laravel\Module;

trait HasModules
{
    /**
     * @return array<string, Module>
     */
    public function getModules(): array
    {
        $modules = ModuleFacade::getOrdered();

        /** @var array<string, Module> $filteredModules */
<<<<<<< HEAD
<<<<<<< HEAD
        $filteredModules = Arr::where($modules, function (mixed $module, int|string $key): bool {
=======
        $filteredModules = Arr::where($modules, function ($module, $key) {
>>>>>>> 60a2c9a9 (.)
=======
        $filteredModules = Arr::where($modules, function ($module, $key) {
=======
        $filteredModules = Arr::where($modules, function (mixed $module, int|string $key): bool {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            // $name = $module->getName();
            $name = is_string($key) ? $key : (string) $key;
            $role_name = Str::of($name)->lower()->append('::admin')->toString();

            return $this->hasRole($role_name);
        });

        // Convert to array<string, Module> preserving string keys
        $result = [];
        foreach ($filteredModules as $key => $module) {
            if ($module instanceof Module) {
                $result[(string) $key] = $module;
            }
        }

        /* @var array<string, Module> $result */
        return $result;
    }
}
