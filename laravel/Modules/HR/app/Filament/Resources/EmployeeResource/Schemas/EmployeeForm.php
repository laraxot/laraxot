<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class EmployeeForm extends XotBaseResourceForm
{
    protected static string $resource = EmployeeResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->components([
                'name' => Grid::make(2)->components([
                    'first_name' => TextInput::make('first_name')
                        ->label(__('hr.fields.first_name'))
                        ->required()
                        ->maxLength(255),
                    'last_name' => TextInput::make('last_name')
                        ->label(__('hr.fields.last_name'))
                        ->required()
                        ->maxLength(255),
                ]),
                'contact' => Grid::make(2)->components([
                    'email' => TextInput::make('email')
                        ->label(__('hr.fields.email'))
                        ->email()
                        ->maxLength(255),
                    'phone' => TextInput::make('phone')
                        ->label(__('hr.fields.phone'))
                        ->tel()
                        ->maxLength(255),
                ]),
                'dates' => Grid::make(2)->components([
                    'hired_at' => DatePicker::make('hired_at')
                        ->label(__('hr.fields.hired_at')),
                    'terminated_at' => DatePicker::make('terminated_at')
                        ->label(__('hr.fields.terminated_at')),
                ]),
                'user_id' => Select::make('user_id')
                    ->label(__('hr.fields.user'))
                    ->options(fn (): array => User::query()
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (User $user): array => [$user->id => $user->name])
                        ->all())
                    ->searchable()
                    ->preload(),
                'notes' => Textarea::make('notes')
                    ->label(__('hr.fields.notes'))
                    ->columnSpanFull(),
                'is_active' => Toggle::make('is_active')
                    ->label(__('hr.fields.is_active'))
                    ->default(true),
            ])->columnSpanFull()->columns(2),
        ];
    }
}
