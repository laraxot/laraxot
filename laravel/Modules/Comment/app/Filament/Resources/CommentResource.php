<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources;

use Modules\Comment\Filament\Resources\CommentResource\Pages\ListComments;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class CommentResource extends XotBaseResource
{
    protected static ?string $model = Comment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    #[Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
        ];
    }
}
