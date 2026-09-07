<?php

declare(strict_types=1);


use Filament\Notifications\Actions\Action;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Livewire\Volt\Component;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Tenant\Services\TenantService;
>>>>>>> f548be94 (.)
=======
use Modules\Tenant\Services\TenantService;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

/** @var array */
//$middleware=TenantService::config('middleware');
//$base_middleware=Arr::get($middleware,'base',[]);
$base_middleware = [];

name('home');
middleware($base_middleware);

new class extends Component {};

?>

<x-layouts.marketing>
    <div>
        {!! $_theme->showPageContent('home') !!}
    </div>
</x-layouts.marketing>
