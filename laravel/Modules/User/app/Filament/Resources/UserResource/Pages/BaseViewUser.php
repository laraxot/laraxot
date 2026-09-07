<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
=======
use Override;
use Filament\Infolists\Components\TextEntry;
>>>>>>> f548be94 (.)
=======
use Override;
use Filament\Infolists\Components\TextEntry;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\UserResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Base class for viewing user resources.
 *
 * This class provides the base configuration for viewing user resources
 * across the application. It should be extended by specific user type
 * view classes rather than used directly.
 */
abstract class BaseViewUser extends XotBaseViewRecord
{
    protected static string $resource = UserResource::class;
<<<<<<< HEAD

    /**
     * Define the infolist schema for the view.
     *
<<<<<<< HEAD
     * @return array<string, Component>
     */
    #[\Override]
=======
     * @return array<string, mixed>
     */
    #[Override]
>>>>>>> f548be94 (.)
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'email' => TextEntry::make('email'),
            'type' => TextEntry::make('type'),
            'state' => TextEntry::make('state'),
            'created_at' => TextEntry::make('created_at')
                ->dateTime(),
            'updated_at' => TextEntry::make('updated_at')
                ->dateTime(),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
