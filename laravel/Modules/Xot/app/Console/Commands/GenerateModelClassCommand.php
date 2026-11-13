<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\GeneratorCommand;

use function Safe\realpath;

class GenerateModelClassCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xot:generate-model-class {model_class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ModelClass.';

<<<<<<< HEAD
    protected function getStub(): string
=======
    protected function getStub()
>>>>>>> 77b1e2748 (first)
    {
        return realpath(__DIR__.'/../stubs/model.stub');
    }

<<<<<<< HEAD
    /**
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
=======
    protected function getDefaultNamespace($rootNamespace)
>>>>>>> 77b1e2748 (first)
    {
        return $rootNamespace.'\Models';
    }

<<<<<<< HEAD
    /**
     * @param  string  $stub
     * @param  string  $name
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

=======
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        // Do string replacement
>>>>>>> 77b1e2748 (first)
        return str_replace('{{service_name}}', $class, $stub);
    }
}
