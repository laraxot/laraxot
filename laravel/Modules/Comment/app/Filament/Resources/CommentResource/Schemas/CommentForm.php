<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources\CommentResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\Section;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Form admin moderazione commento — SSOT schema (XotBaseResource::form auto-discovery).
 */
class CommentForm extends XotBaseResourceForm
{
    /**
     * @return array<string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return [
            'content' => Section::make()
                ->schema([
                    'original_text' => Textarea::make('original_text')
                        ->disabled()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
            'meta' => Section::make()
                ->schema([
                    'commentable_type' => TextInput::make('commentable_type')->disabled(),
                    'commentable_id' => TextInput::make('commentable_id')->disabled(),
                    'parent_id' => TextInput::make('parent_id')->disabled(),
                    'approved_at' => DateTimePicker::make('approved_at')->disabled(),
                ])
                ->columns(2),
        ];
    }
}
