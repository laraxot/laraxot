<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources\CommentResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Comment\Actions\Comment\ApproveCommentAction;
use Modules\Comment\Actions\Comment\RejectCommentAction;
use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Comment\Models\Comment;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Tabella moderazione commenti — SSOT colonne/filtri/azioni (pattern TicketsTable).
 */
class CommentsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable()
                ->searchable(),
            'original_text' => TextColumn::make('original_text')
                ->limit(80)
                ->searchable()
                ->wrap(),
            'commentator' => TextColumn::make('commentator_display')
                ->label(CommentResource::trans('fields.commentator.label'))
                ->state(static function (Comment $record): string {
                    $properties = $record->commentatorProperties();

                    return is_object($properties) && isset($properties->name) && is_string($properties->name)
                        ? $properties->name
                        : '—';
                }),
            'commentable' => TextColumn::make('commentable_type')
                ->label(CommentResource::trans('fields.commentable.label'))
                ->formatStateUsing(static fn (Comment $record): string => class_basename((string) $record->commentable_type)
                    .' #'.$record->commentable_id),
            'status' => TextColumn::make('moderation_status')
                ->label(CommentResource::trans('fields.status.label'))
                ->badge()
                ->state(static fn (Comment $record): string => $record->isApproved()
                    ? CommentResource::trans('fields.status.approved')
                    : CommentResource::trans('fields.status.pending'))
                ->color(static fn (string $state): string => $state === CommentResource::trans('fields.status.approved')
                    ? 'success'
                    : 'warning'),
            'parent_id' => TextColumn::make('parent_id')
                ->label(CommentResource::trans('fields.parent_id.label'))
                ->placeholder('—'),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'moderation' => SelectFilter::make('moderation')
                ->label(CommentResource::trans('filters.moderation.label'))
                ->options([
                    'pending' => CommentResource::trans('filters.moderation.pending'),
                    'approved' => CommentResource::trans('filters.moderation.approved'),
                ])
                ->query(static function (Builder $query, array $data): void {
                    $value = $data['value'] ?? null;
                    if ($value === 'pending') {
                        $query->whereNull('approved_at');
                    }
                    if ($value === 'approved') {
                        $query->whereNotNull('approved_at');
                    }
                }),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'approve' => Action::make('approve')
                ->label(CommentResource::trans('actions.approve.label'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(static fn (Comment $record): bool => $record->isPending())
                ->action(static fn (Comment $record): Comment => app(ApproveCommentAction::class)->execute($record)),
            'reject' => Action::make('reject')
                ->label(CommentResource::trans('actions.reject.label'))
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(static fn (Comment $record): Comment => app(RejectCommentAction::class)->execute($record)),
        ];
    }

    /**
     * @return array<string, BulkAction|BulkActionGroup>
     */
    public function getTableBulkActions(): array
    {
        return [
            'moderation' => BulkActionGroup::make([
                'approve' => BulkAction::make('approve')
                    ->label(CommentResource::trans('actions.approve_bulk.label'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(static function (Collection $records): void {
                        $records
                            ->filter(static fn (mixed $record): bool => $record instanceof Comment && $record->isPending())
                            ->each(static function (Comment $comment): void {
                                app(ApproveCommentAction::class)->execute($comment);
                            });
                    }),
                'reject' => BulkAction::make('reject')
                    ->label(CommentResource::trans('actions.reject_bulk.label'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(static function (Collection $records): void {
                        $records
                            ->filter(static fn (mixed $record): bool => $record instanceof Comment)
                            ->each(static function (Comment $comment): void {
                                app(RejectCommentAction::class)->execute($comment);
                            });
                    }),
            ]),
        ];
    }
}
