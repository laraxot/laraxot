<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Platform\Filament\Resources\AuditLogResource;

final class ViewAuditLog extends ViewRecord
{
    protected static string $resource = AuditLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('platform.audit_log.sections.summary'))
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('created_at')->label(__('platform.audit_log.fields.created_at'))->dateTime(),
                        \Filament\Infolists\Components\TextEntry::make('action')->label(__('platform.audit_log.fields.action'))->badge(),
                        \Filament\Infolists\Components\TextEntry::make('actor.email')->label(__('platform.audit_log.fields.actor'))->placeholder('—'),
                        \Filament\Infolists\Components\TextEntry::make('panel_id')->label(__('platform.audit_log.fields.panel'))->placeholder('—'),
                        \Filament\Infolists\Components\TextEntry::make('route')->label(__('platform.audit_log.fields.route'))->placeholder('—'),
                        \Filament\Infolists\Components\TextEntry::make('ip')->label(__('platform.audit_log.fields.ip'))->placeholder('—'),
                        \Filament\Infolists\Components\TextEntry::make('subject_type')
                            ->label(__('platform.audit_log.fields.subject'))
                            ->formatStateUsing(fn (?string $s): string => $s ? class_basename($s) : '—'),
                        \Filament\Infolists\Components\TextEntry::make('subject_id')->label(__('platform.audit_log.fields.subject_id'))->placeholder('—'),
                    ]),

                Section::make(__('platform.audit_log.sections.before_after'))
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\KeyValueEntry::make('before')->label(__('platform.audit_log.sections.before'))->columnSpan(1),
                        \Filament\Infolists\Components\KeyValueEntry::make('after')->label(__('platform.audit_log.sections.after'))->columnSpan(1),
                    ]),
            ]);
    }

    /** @return array<int, Action> */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('platform.audit_log.actions.back'))
                ->url(AuditLogResource::getUrl()),
        ];
    }
}

