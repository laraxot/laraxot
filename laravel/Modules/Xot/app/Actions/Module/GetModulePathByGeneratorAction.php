<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Module;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
<<<<<<< HEAD
=======
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
>>>>>>> laraxot/dev

class GetModulePathByGeneratorAction
{
    public function execute(string $moduleName, string $generatorPath): string
    {
        $relativePath = Config::string('modules.paths.generator.'.$generatorPath.'.path');
        try {
<<<<<<< HEAD
            $res = module_path($moduleName, $relativePath);
            if ($res !== '') {
                return $res;
            }
        } catch (\Exception|\Error $e) {
            // Fallback: costruisci path manualmente per graceful degradation
            $modulePath = base_path('Modules/'.$moduleName);
            $fullPath = $modulePath.'/'.$relativePath;

=======
            return module_path($moduleName, $relativePath);
        } catch (ModuleNotFoundException $exception) {
            $fullPath = base_path('Modules/'.$moduleName).'/'.$relativePath;
>>>>>>> laraxot/dev
            if (File::exists($fullPath)) {
                return $fullPath;
            }

<<<<<<< HEAD
            // Se path non esiste e non è opzionale, lancia eccezione
            throw new \Exception('Module path not found: 
            name:['.$moduleName.'] 
            generatorPath:['.$generatorPath.']
            relativePath:['.$relativePath.']
            error_message:['.$e->getMessage().']');
        }

        throw new \Exception('Module path not found: 
        name:['.$moduleName.'] 
        generatorPath:['.$generatorPath.']
        relativePath:['.$relativePath.']');
=======
            throw new \RuntimeException('Module path not found: name:['.$moduleName.'] generatorPath:['.$generatorPath.'] relativePath:['.$relativePath.']', 0, $exception);
        }
>>>>>>> laraxot/dev
    }
}
