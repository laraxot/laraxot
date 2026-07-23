@props(['benefits' => [], 'title' => 'Perché Sceglierci'])

<section class="py-20 bg-[#f8fafc]">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-4 text-gray-900">{{ $title }}</h2>
            <p class="text-lg text-gray-600">I vantaggi concreti di affidarsi alla nostra esperienza.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($benefits as $benefit)
                <div class="flex items-start gap-4 bg-white p-6 rounded-lg border border-gray-100 hover:border-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">{{ $benefit['title'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $benefit['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
