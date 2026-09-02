<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources\CommentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

/**
 * Infolist admin commento — SSOT per XotBaseResource::infolist auto-discovery.
 */
class CommentInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'original_text' => TextEntry::make('original_text')->columnSpanFull(),
            'commentator' => TextEntry::make('commentator_display')
                ->state(static function (Comment $record): string {
                    $properties = $record->commentatorProperties();

                    return is_object($properties) && isset($properties->name) && is_string($properties->name)
                        ? $properties->name
                        : '—';
                }),
            'commentable' => TextEntry::make('commentable_type')
                ->formatStateUsing(static fn (Comment $record): string => class_basename((string) $record->commentable_type)
                    .' #'.$record->commentable_id),
            'moderation_status' => TextEntry::make('moderation_status')
                ->badge()
                ->state(static fn (Comment $record): string => $record->isApproved()
                    ? CommentResource::trans('fields.status.approved')
                    : CommentResource::trans('fields.status.pending'))
                ->color(static fn (string $state): string => $state === 'approved' ? 'success' : 'warning'),
            'parent_id' => TextEntry::make('parent_id')->placeholder('—'),
            'approved_at' => TextEntry::make('approved_at')->dateTime()->placeholder('—'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
