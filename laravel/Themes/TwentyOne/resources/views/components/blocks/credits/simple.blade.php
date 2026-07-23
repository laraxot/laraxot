@if(isset($profile))
    <div class="flex items-center px-2 space-x-2">
        <x-filament::icon 
            icon="predict-coin4"
            
            class="h-5 w-5 text-gray-500 dark:text-gray-400"
        />
        <div class="text-sm font-semibold">{{ $profile->credits}}</div>
    </div>
@endif
