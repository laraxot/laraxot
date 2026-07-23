{{--
    Contact Section - FAQ / Contact
    TailwindCSS + DaisyUI
--}}

@php
    $contactItems = $contacts['contacts'] ?? [];
@endphp

@if (count($contactItems) > 0)
<section class="bg-white border-t border-gray-200 mt-12 py-12">
    <div class="container mx-auto px-4 lg:px-6 xl:px-8">
        <div class="max-w-4xl mx-auto text-center">
            
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                {{ $contacts['contact_title'] ?? __('fixcity::ticket.contacts.block_title.label') }}
            </h2>
            
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($contactItems as $contact)
                    <a href="{{ $contact['url'] ?? '#' }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-900 font-medium transition-colors">
                        @if ($contact['icon'] ?? false)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-italia-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                        {{ __($contact['label']) }}
                    </a>
                @endforeach
            </div>
            
        </div>
    </div>
</section>
@endif
