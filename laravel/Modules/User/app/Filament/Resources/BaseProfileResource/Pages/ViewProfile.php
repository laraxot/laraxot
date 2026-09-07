<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\BaseProfileResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Filament\Infolists\Infolist;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components;
use Filament\Schemas\Components\Flex;

>>>>>>> f548be94 (.)
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Support\Components\Component;
<<<<<<< HEAD
use Modules\User\Filament\Resources\BaseProfileResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
=======
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Modules\User\Filament\Resources\BaseProfileResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Filament\Resources\BaseProfileResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class ViewProfile extends XotBaseViewRecord
{
    protected static string $resource = BaseProfileResource::class;
<<<<<<< HEAD

    /**
<<<<<<< HEAD
     * @return array<string, Component>
     */
    #[\Override]
    public function getInfolistSchema(): array
    {
        return [
            'profile_info' => Section::make()->schema([
                'profile_flex' => Flex::make([
                    'profile_grid' => Grid::make(2)->schema([
                        'profile_group' => Group::make([
                            'email' => TextEntry::make('email'),
                            'first_name' => TextEntry::make('first_name'),
                            'last_name' => TextEntry::make('last_name'),
                            'created_at' => TextEntry::make('created_at')
=======
     * @return array<int, Component>
     */
    #[Override]
    public function getInfolistSchema(): array
    {
        return [
            Section::make()->schema([
                Flex::make([
                    Grid::make(2)->schema([
                        Group::make([
                            TextEntry::make('email'),
                            TextEntry::make('first_name'),
                            TextEntry::make('last_name'),
                            TextEntry::make('created_at')
>>>>>>> f548be94 (.)
                                ->badge()
                                ->date()
                                ->color('success'),
                        ]),
<<<<<<< HEAD
                    ]),
                    'image' => ImageEntry::make('image')->hiddenLabel()->grow(false),
                ])->from('lg'),
            ]),
            'content' => Section::make('Content')
                ->schema([
                    'content_text' => TextEntry::make('content')
=======
                        /*
                         * Components\Group::make([
                         * Components\TextEntry::make('author.name'),
                         * Components\TextEntry::make('category.name'),
                         * Components\TextEntry::make('tags')
                         * ->badge()
                         * ->getStateUsing(fn () => ['one', 'two', 'three', 'four']),
                         * ]),
                         */
                    ]),
                    ImageEntry::make('image')->hiddenLabel()->grow(false),
                ])->from('lg'),
            ]),
            Section::make('Content')
                ->schema([
                    TextEntry::make('content')
>>>>>>> f548be94 (.)
                        ->prose()
                        ->markdown()
                        ->hiddenLabel(),
                ])
                ->collapsible(),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
