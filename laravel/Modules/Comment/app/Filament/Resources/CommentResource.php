<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources;

use Modules\Comment\Filament\Resources\CommentResource\Pages\ListComments;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentForm;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentInfolist;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class CommentResource extends XotBaseResource
{
    protected static ?string $model = Comment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    #[Override]
    public static function getFormSchema(): array
    {
        return CommentForm::getFormSchema();
    }

    #[Override]
    public static function getInfolistSchema(): array
    {
        return CommentInfolist::getInfolistSchema();
    }

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
