@php
    $categories = $categories ?? [
        'all' => ['name' => 'Tutti gli Articoli', 'count' => 0, 'color' => 'gray'],
        'radioprotezione' => ['name' => 'Radioprotezione', 'count' => 24, 'color' => 'blue'],
        'normativa' => ['name' => 'Normativa', 'count' => 18, 'color' => 'green'],
        'elettromedicali' => ['name' => 'Elettromedicali', 'count' => 15, 'color' => 'orange'],
        'veterinaria' => ['name' => 'Veterinaria', 'count' => 12, 'color' => 'purple'],
        'guide-pratiche' => ['name' => 'Guide Pratiche', 'count' => 32, 'color' => 'teal'],
        'novita' => ['name' => 'Novità', 'count' => 8, 'color' => 'indigo'],
    ];
    
    $selectedCategory = $selectedCategory ?? 'all';
    $style = $style ?? 'pills'; // pills, buttons, dropdown
@endphp

@if($style === 'dropdown')
    {{-- Dropdown Style --}}
    <div class="relative">
        <select 
            x-model="selectedCategory"
            @change="$dispatch('category-changed', { category: $event.target.value })"
            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white"
        >
            @foreach($categories as $key => $category)
                <option value="{{ $key }}" {{ $selectedCategory === $key ? 'selected' : '' }}>
                    {{ $category['name'] }} {{ $key !== 'all' ? "({$category['count']})" : '' }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

@elseif($style === 'buttons')
    {{-- Button Style --}}
    <div class="flex flex-wrap gap-3" x-data="{ selectedCategory: '{{ $selectedCategory }}' }">
        @foreach($categories as $key => $category)
            @php
                $isActive = $selectedCategory === $key;
                $colorClasses = match($category['color']) {
                    'blue' => $isActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50',
                    'green' => $isActive ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-600 border-green-600 hover:bg-green-50',
                    'orange' => $isActive ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-orange-600 border-orange-600 hover:bg-orange-50',
                    'purple' => $isActive ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-purple-600 border-purple-600 hover:bg-purple-50',
                    'teal' => $isActive ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-teal-600 border-teal-600 hover:bg-teal-50',
                    'indigo' => $isActive ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-indigo-600 border-indigo-600 hover:bg-indigo-50',
                    'gray' => $isActive ? 'bg-gray-600 text-white border-gray-600' : 'bg-white text-gray-600 border-gray-600 hover:bg-gray-50',
                    default => $isActive ? 'bg-gray-600 text-white border-gray-600' : 'bg-white text-gray-600 border-gray-600 hover:bg-gray-50',
                };
            @endphp
            
            <button 
                x-data="{ category: '{{ $key }}' }"
                @click="selectedCategory = category; $dispatch('category-changed', { category: category })"
                class="px-6 py-3 rounded-lg font-medium transition-all duration-200 border-2 {{ $colorClasses }} {{ $isActive ? 'shadow-lg transform scale-105' : 'hover:shadow-md' }}"
            >
                {{ $category['name'] }}
                @if($key !== 'all' && $category['count'] > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $category['count'] }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

@else
    {{-- Default Pills Style --}}
    <div class="flex flex-wrap items-center justify-center gap-2" x-data="{ selectedCategory: '{{ $selectedCategory }}' }">
        @foreach($categories as $key => $category)
            @php
                $isActive = $selectedCategory === $key;
                $colorClasses = match($category['color']) {
                    'blue' => $isActive ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                    'green' => $isActive ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200',
                    'orange' => $isActive ? 'bg-orange-600 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200',
                    'purple' => $isActive ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200',
                    'teal' => $isActive ? 'bg-teal-600 text-white' : 'bg-teal-100 text-teal-700 hover:bg-teal-200',
                    'indigo' => $isActive ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
                    'gray' => $isActive ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                    default => $isActive ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                };
            @endphp
            
            <button 
                x-data="{ category: '{{ $key }}' }"
                @click="selectedCategory = category; $dispatch('category-changed', { category: category })"
                class="px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $colorClasses }} {{ $isActive ? 'shadow-lg transform scale-105' : '' }}"
            >
                {{ $category['name'] }}
                @if($key !== 'all' && $category['count'] > 0)
                    <span class="ml-1 text-xs opacity-75">({{ $category['count'] }})</span>
                @endif
            </button>
        @endforeach
    </div>
@endif

{{-- Category Description --}}
@if($selectedCategory !== 'all' && isset($categories[$selectedCategory]))
    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="font-medium text-blue-900">{{ $categories[$selectedCategory]['name'] }}</h4>
                <p class="text-sm text-blue-700 mt-1">
                    {{ $categories[$selectedCategory]['count'] }} articoli trovati in questa categoria.
                </p>
            </div>
        </div>
    </div>
@endif