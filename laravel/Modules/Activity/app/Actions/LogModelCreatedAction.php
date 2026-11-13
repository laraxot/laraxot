<?php

declare(strict_types=1);

namespace Modules\Activity\Actions;

use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Models\Activity;
use Spatie\QueueableAction\QueueableAction;

/**
 * Log Model Created Action.
<<<<<<< HEAD
 *
 * Logs when a model is created using Queueable Actions
=======
 * Optimized for Laraxot architecture.
>>>>>>> 13cf1831 (.)
 */
class LogModelCreatedAction
{
    use QueueableAction;

    public function __construct(
<<<<<<< .merge_file_2BcBI6
        public Model $model,
=======
        public ?Model $model = null,
>>>>>>> .merge_file_sHkBRk
        public ?Model $user = null,
    ) {
<<<<<<< HEAD
        if ($user !== null) {
            // Type already narrowed to Model|null, assertion not needed
        }
    }

<<<<<<< .merge_file_2BcBI6
    public function execute(): Activity
    {
=======
    public function execute(?Model $model = null): Activity
    {
        $model = $model ?? $this->model;
        if (! $model instanceof Model) {
            throw new \InvalidArgumentException('A model is required');
        }
>>>>>>> .merge_file_sHkBRk
        // PHPStan Level 10: Explicit type guard for nullable Model
        $user = $this->user instanceof Model ? $this->user : null;

        $action = new LogActivityAction(
            type: 'created',
            user: $user,
<<<<<<< .merge_file_2BcBI6
            subject: $this->model,
            properties: ['attributes' => $this->model->getAttributes()],
            description: sprintf('%s created', class_basename($this->model))
=======
            subject: $model,
            properties: ['attributes' => $model->getAttributes()],
            description: sprintf('%s created', class_basename($model))
>>>>>>> .merge_file_sHkBRk
        );

        return $action->execute();
=======
    }

    /**
     * Execute the action.
     */
    public function execute(): Activity
    {
        /** @var array<string, mixed> $properties */
        $properties = $this->model->toArray();

        return (new LogActivityAction(
            type: 'created',
            user: $this->user,
            subject: $this->model,
            properties: $properties,
            description: sprintf('%s was created', class_basename($this->model)),
        ))->execute();
>>>>>>> 13cf1831 (.)
    }
}
