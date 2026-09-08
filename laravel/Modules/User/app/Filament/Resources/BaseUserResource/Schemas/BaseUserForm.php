<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\BaseUserResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;

class BaseUserForm extends XotBaseResourceForm
{
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'section01' => Section::make([
                'name' => TextInput::make('name')->required(),
                'email' => TextInput::make('email')->required()->unique(ignoreRecord: true),
                'password' => TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state)) {
                            return;
                        }

                        return is_string($state) ? Hash::make($state) : null;
                    })
                    ->required(fn ($livewire) => $livewire instanceof CreateUser),
            ])->columnSpan(8),
            'section02' => Section::make([
                'created_at' => TextEntry::make('created_at')->state(static function ($record) {
                    if ($record === null || ! $record instanceof Model) {
                        return new HtmlString('&mdash;');
                    }

                    if (! isset($record->created_at) || ! ($record->created_at instanceof \DateTimeInterface)) {
                        return new HtmlString('&mdash;');
                    }

                    $createdAt = $record->created_at;

                    return $createdAt instanceof CarbonInterface ? $createdAt->diffForHumans() : $createdAt->format('Y-m-d H:i:s');
                }),
            ])->columnSpan(4),
        ];
    }
}
