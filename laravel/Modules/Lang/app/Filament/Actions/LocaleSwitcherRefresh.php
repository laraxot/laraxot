<?php

declare(strict_types=1);

namespace Modules\Lang\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;

class LocaleSwitcherRefresh extends Action
{
    public string $fullUrl = '#';

    public string $lang = '';

    protected function setUp(): void
    {
        parent::setUp();
        $languageOptions = [
            'en' => '🇬🇧 English',
            'it' => '🇮🇹 Italiano',
        ];
        $lang = session()->get('locale');
        if (! is_string($lang)) {
            $lang = 'it';
        }
        app()->setLocale($lang);
        $this->lang = app()->getLocale();
        $this->fullUrl = request()->fullUrl();
        $this->label($this->lang)
            ->schema([
                Select::make('locale')
                    ->label('Seleziona lingua')
                    ->options($languageOptions)
                    ->default($this->lang)
                    ->reactive()
                    ->required(),
            ])
            ->action(fn (array $data): RedirectResponse|Redirector => $this->applyLocale($data))
            ->modalHeading('Cambia lingua')
            // ->icon('heroicon-o-language')
            ->color('gray');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function applyLocale(array $data): RedirectResponse|Redirector
    {
        $locale = $data['locale'] ?? 'en';
        $locale = is_string($locale) ? $locale : 'en';

        session()->put('locale', $locale);
        App::setLocale($locale);

        $referer = request()->header('Referer');
        $target = is_string($referer) && $referer !== '' ? $referer : url()->current();

        return redirect()->to($target);
    }
}
