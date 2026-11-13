@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600 dark:text-red-400">{{ __('pub_theme::components_forms.validation.error_heading.message') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
