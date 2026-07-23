@props([
    'name' => '',
    'title' => '',
    'description' => '',
    'image' => '',
    'features' => [],
    'company_info' => [],
])

<div class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image -->
            <div class="relative">
                <div class="aspect-w-4/3 rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ $image }}"
                         alt="{{ $name }}"
                         class="w-full h-full object-cover"
                         loading="lazy">
                </div>
            </div>

            <!-- Content -->
            <div>
                <h2 class="text-3xl font-bold text-brand-blue mb-4">
                    {{ $name }}
                </h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-6">
                    {{ $title }}
                </h3>
                
                <div class="prose prose-lg text-gray-600 mb-8">
                    {{ nl2br($description) }}
                </div>

                <!-- Features -->
                @if(!empty($features))
                <div class="space-y-4 mb-8">
                    @foreach ($features as $feature)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <x-dynamic-component 
                                :component="'heroicon-o-' . $feature['icon']" 
                                class="w-6 h-6 text-brand-green" 
                            />
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">
                                {{ $feature['title'] }}
                            </h4>
                            <p class="text-gray-600">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Company Info -->
                @if(!empty($company_info))
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-building-office class="w-5 h-5 mr-2" />
                        Dati Aziendali
                    </h4>
                    <div class="space-y-2 text-sm">
                        @if($company_info['piva'] ?? null)
                        <p class="text-gray-600">
                            <strong>P.IVA:</strong> {{ $company_info['piva'] }}
                        </p>
                        @endif
                        @if($company_info['rea'] ?? null)
                        <p class="text-gray-600">
                            <strong>REA:</strong> {{ $company_info['rea'] }}
                        </p>
                        @endif
                        @if($company_info['pec'] ?? null)
                        <p class="text-gray-600">
                            <strong>PEC:</strong> {{ $company_info['pec'] }}
                        </p>
                        @endif
                        @if($company_info['address'] ?? null)
                        <p class="text-gray-600">
                            <strong>Sede:</strong> {{ $company_info['address'] }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>