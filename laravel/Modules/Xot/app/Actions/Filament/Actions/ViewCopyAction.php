<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament\Actions;

use Modules\Xot\Filament\Actions\XotBaseAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action for copying views in Filament.
 */
class ViewCopyAction extends XotBaseAction
{
    use QueueableAction;

    /**
     * Create a new instance of the action.
     */
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'view_copy');
        $actionClass = static::class;

        return $action
            ->label('Copy View')
            ->icon('heroicon-o-document-duplicate')
            ->requiresConfirmation()
            ->modalHeading('Copy View')
            ->modalDescription('Are you sure you want to copy this view?')
            ->action(static function (array $arguments, array $data) use ($actionClass): void {
                /** @var self $service */
                $service = app($actionClass);
                $service->execute(
                    array_filter($arguments, 'is_string', ARRAY_FILTER_USE_KEY),
                    array_filter($data, 'is_string', ARRAY_FILTER_USE_KEY),
                );
            });
    }

    /**
<<<<<<< HEAD
     * @param  array<string, mixed>  $arguments
     * @param  array<string, mixed>  $data
=======
<<<<<<< .merge_file_o26QPR
     * @param  array<string, mixed>  $arguments
     * @param  array<string, mixed>  $data
=======
     * <<<<<<< HEAD.
     *
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $data
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $data
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $data
     *                                        =======
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $data
     *                                        >>>>>>> laraxot/dev
     *                                        =======
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $data
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_PmxrqW
>>>>>>> 77b1e2748 (first)
     */
    public function execute(array $arguments, array $data): void
    {
        // TODO: Implement view copying logic
        // This should copy the view file and any related assets
    }
}
