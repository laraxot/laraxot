<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class PublicProcurementTenderOpportunityForm extends XotBaseResourceForm
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'status' => Select::make('status')
                        ->label(__('public_procurement.fields.status'))
                        ->options(PublicProcurementTenderOpportunityResource::statuses())
                        ->default('new')
                        ->required(),
                    'tender_source_id' => Select::make('tender_source_id')
                        ->label(__('public_procurement.fields.source'))
                        ->options(fn (): array => PublicProcurementTenderSource::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    'customer_id' => Select::make('customer_id')
                        ->label(__('public_procurement.fields.customer'))
                        ->options(fn (): array => Customer::query()->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload(),
                ]),
                'title' => TextInput::make('title')->label(__('public_procurement.fields.title'))->required()->maxLength(255)->columnSpanFull(),
                'grid2' => Grid::make(3)->components([
                    'contracting_authority' => TextInput::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->required()->maxLength(255),
                    'cig' => TextInput::make('cig')->label(__('public_procurement.fields.cig'))->maxLength(255),
                    'cup' => TextInput::make('cup')->label(__('public_procurement.fields.cup'))->maxLength(255),
                    'cpv_code' => TextInput::make('cpv_code')->label(__('public_procurement.fields.cpv_code'))->maxLength(255),
                    'category' => TextInput::make('category')->label(__('public_procurement.fields.category'))->maxLength(255),
                    'territory' => TextInput::make('territory')->label(__('public_procurement.fields.territory'))->maxLength(255),
                ]),
                'grid3' => Grid::make(3)->components([
                    'estimated_amount' => TextInput::make('estimated_amount')->label(__('public_procurement.fields.estimated_amount'))->numeric(),
                    'published_at' => DateTimePicker::make('published_at')->label(__('public_procurement.fields.published_at')),
                    'submission_deadline_at' => DateTimePicker::make('submission_deadline_at')->label(__('public_procurement.fields.submission_deadline_at')),
                    'site_visit_due_at' => DateTimePicker::make('site_visit_due_at')->label(__('public_procurement.fields.site_visit_due_at')),
                    'source_url' => TextInput::make('source_url')->label(__('public_procurement.fields.source_url'))->url()->columnSpan(2),
                ]),
                'raw_excerpt' => Textarea::make('raw_excerpt')->label(__('public_procurement.fields.raw_excerpt'))->columnSpanFull(),
                'notes' => Textarea::make('notes')->label(__('public_procurement.fields.notes'))->columnSpanFull(),
            ]),
        ];
    }
}
