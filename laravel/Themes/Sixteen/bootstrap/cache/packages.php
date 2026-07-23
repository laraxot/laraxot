<?php

use AnourValar\EloquentSerialize\Facades\EloquentSerializeFacade;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Kirschbaum\PowerJoins\PowerJoinsServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Livewire;
use Carbon\Laravel\ServiceProvider;
use Termwind\Laravel\TermwindServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

return ['anourvalar/eloquent-serialize' => 
['aliases' => 
['EloquentSerialize' => EloquentSerializeFacade::class]], 'blade-ui-kit/blade-heroicons' => 
['providers' => 
[0 => BladeHeroiconsServiceProvider::class]], 'blade-ui-kit/blade-icons' => 
['providers' => 
[0 => BladeIconsServiceProvider::class]], 'filament/actions' => 
['providers' => 
[0 => ActionsServiceProvider::class]], 'filament/filament' => 
['providers' => 
[0 => FilamentServiceProvider::class]], 'filament/forms' => 
['providers' => 
[0 => FormsServiceProvider::class]], 'filament/infolists' => 
['providers' => 
[0 => InfolistsServiceProvider::class]], 'filament/notifications' => 
['providers' => 
[0 => NotificationsServiceProvider::class]], 'filament/support' => 
['providers' => 
[0 => SupportServiceProvider::class]], 'filament/tables' => 
['providers' => 
[0 => TablesServiceProvider::class]], 'filament/widgets' => 
['providers' => 
[0 => WidgetsServiceProvider::class]], 'kirschbaum-development/eloquent-power-joins' => 
['providers' => 
[0 => PowerJoinsServiceProvider::class]], 'livewire/livewire' => 
['providers' => 
[0 => LivewireServiceProvider::class], 'aliases' => 
['Livewire' => Livewire::class]], 'nesbot/carbon' => 
['providers' => 
[0 => ServiceProvider::class]], 'nunomaduro/termwind' => 
['providers' => 
[0 => TermwindServiceProvider::class]], 'ryangjchandler/blade-capture-directive' => 
['providers' => 
[0 => BladeCaptureDirectiveServiceProvider::class], 'aliases' => 
['BladeCaptureDirective' => 'RyanChandler\\BladeCaptureDirective\\Facades\\BladeCaptureDirective']]];
