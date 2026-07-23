<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Arr;
use Modules\Xot\Datas\EnvData;

class EnvWidget extends XotBaseSchemaWidget
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var list<string> */
    public array $only = [];

    /** @var view-string */
    protected string $view = 'xot::filament.widgets.env';

    public function mount(): void
    {
        /** @var array<string, mixed> */
        $data = EnvData::make()->toArray();
        $this->data = $data;

        $this->form->fill($this->data);
    }

    public function submit(): void
    {
        if (! is_array($this->data)) {
            return;
        }
        EnvData::make()->update($this->data);
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        /*
         * dddx([
         * 'data' => $this->data,
         * // 'data1' => $this->form->getState(),
         * ]);
         */
    }

    /**
     * @return array<Component>
     */
    public function getFormSchema(): array
    {
        $all = [
            'app_url' => TextInput::make('app_url')
                ->placeholder('http://localhost')
                ->helperText('Required for file uploads and other internal configs')
                ->required(),
            'debugbar_enabled' => Toggle::make('debugbar_enabled')->helperText(
                'Enable/Disable debug mode to help debug errors',
            ),
            'google_maps_api_key' => TextInput::make('google_maps_api_key')
                ->placeholder('AIzaSyAuB_...')
                ->helperText('google maps api key'),
            'telegram_bot_token' => TextInput::make('telegram_bot_token')
                ->placeholder('AIzaSyAuB_...')
                ->helperText('telegram_bot_token'),
        ];
        $selected = [] === $this->only ? $all : Arr::only($all, $this->only);

        /** @var array<Component> $components */
        $components = array_values($selected);

        return $components;
    }
}
