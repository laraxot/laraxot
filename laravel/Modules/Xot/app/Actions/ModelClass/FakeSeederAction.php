<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\QueueableAction\QueueableAction;

class FakeSeederAction
{
    use QueueableAction;

    private const int MAX_RECORDS = 200;

    private const int CHUNK_SIZE = 50;

    /**
     * Execute the fake data seeding process.
     *
<<<<<<< HEAD
     * @param  class-string<Model>  $modelClass  The fully qualified model class name
     * @param  int<1, max>  $qty  Number of records to generate
=======
<<<<<<< .merge_file_RjyXTc
     * @param  class-string<Model>  $modelClass  The fully qualified model class name
     * @param  int<1, max>  $qty  Number of records to generate
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *                                        =======
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *                                        >>>>>>> laraxot/dev
     *                                        =======
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_ni32kn
>>>>>>> 77b1e2748 (first)
     *
     * @throws \InvalidArgumentException When model class is invalid
     */
    public function execute(string $modelClass, int $qty): void
    {
        if (
            ! class_exists($modelClass)
                || ! is_subclass_of($modelClass, Model::class)
                || ! in_array(HasFactory::class, class_uses_recursive($modelClass), strict: true)
        ) {
            throw new \InvalidArgumentException("Invalid model class or missing HasFactory trait: {$modelClass}");
        }

        $qtyToDo = min($qty, self::MAX_RECORDS);

        $factory = $this->getModelFactory($modelClass);
        /** @var Collection<int, Model> $rows */
        $rows = $factory->count($qtyToDo)->make();

        /** @var Collection<int, Collection<int, Model>> $chunks */
        $chunks = $rows->chunk(self::CHUNK_SIZE);

        $chunks->each(function (Collection $chunk) use ($modelClass): void {
            $data = $chunk->map(fn (Model $item) => $item->getAttributes())->all();
            $modelClass::insert($data);
        });

        $this->sendNotification($modelClass, $qtyToDo);

        if ($qty > self::MAX_RECORDS) {
            $this->queueRemainingRecords($modelClass, $qty);
        }
    }

    /**
     * Get the model factory.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_RjyXTc
>>>>>>> 77b1e2748 (first)
     * @param  class-string<Model>  $modelClass
     * @return Factory<Model>
     * @return Factory<Model>
     *
     * @throws \RuntimeException
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     * @param class-string<Model> $modelClass
     * @param class-string<Model> $modelClass
     * @param class-string<Model> $modelClass
     * @param class-string<Model> $modelClass
     *
     * @throws \RuntimeException
     *                           =======
     *                           <<<<<<< HEAD
     * @throws \RuntimeException
     * @throws \RuntimeException
     *                           =======
     * @throws \RuntimeException
     * @throws \RuntimeException
     *                           >>>>>>> laraxot/dev
     *
     * @return Factory<Model>
     * @return Factory<Model>
     * @return Factory<Model>
     *                        =======
     *                        <<<<<<< HEAD
     *                        <<<<<<< HEAD
     * @return Factory<Model>
     * @return Factory<Model>
     * @return Factory<Model>
     *                        >>>>>>> laraxot/dev
     *                        =======
     * @return Factory<Model>
     * @return Factory<Model>
     *                        >>>>>>> laraxot/dev
     *                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_ni32kn
>>>>>>> 77b1e2748 (first)
     */
    private function getModelFactory(string $modelClass): Factory
    {
        if (method_exists($modelClass, 'factory')) {
            /** @var Factory<Model> $factory */
            $factory = $modelClass::factory();

            return $factory;
        }

        throw new \RuntimeException("Unable to create factory for model: {$modelClass}");
    }

    /**
     * Send a notification about the seeding completion.
     *
<<<<<<< HEAD
     * @param  class-string<Model>  $modelClass
     * @param  int<1, max>  $count
=======
<<<<<<< .merge_file_RjyXTc
     * @param  class-string<Model>  $modelClass
     * @param  int<1, max>  $count
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     *                                        =======
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     *                                        >>>>>>> laraxot/dev
     *                                        =======
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_ni32kn
>>>>>>> 77b1e2748 (first)
     */
    private function sendNotification(string $modelClass, int $count): void
    {
        $title = sprintf('Created %d %s !', $count, $modelClass);
        Notification::make()
            ->title($title)
            ->success()
            ->send();
    }

    /**
     * Queue remaining records for processing.
     *
<<<<<<< HEAD
     * @param  class-string<Model>  $modelClass
     * @param  int<1, max>  $qty
=======
<<<<<<< .merge_file_RjyXTc
     * @param  class-string<Model>  $modelClass
     * @param  int<1, max>  $qty
=======
     * <<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     *                                        =======
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     *                                        >>>>>>> laraxot/dev
     *                                        =======
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_ni32kn
>>>>>>> 77b1e2748 (first)
     */
    private function queueRemainingRecords(string $modelClass, int $qty): void
    {
        if ($qty <= self::MAX_RECORDS) {
            return;
        }
        app(self::class)->onQueue()->execute($modelClass, $qty - self::MAX_RECORDS);
    }
    /*
        private function getTableName(string $modelClass): string
        {
            Assert::classExists($modelClass, 'La classe del modello deve esistere');

            //@var Model
            $model = app($modelClass);

            return $model->getTable();
        }
            */
}
