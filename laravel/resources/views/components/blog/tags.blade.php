@php
    $tags = $tags ?? [
        ['name' => 'D.Lgs 101/2020', 'count' => 24, 'color' => 'blue'],
        ['name' => 'Radioprotezione', 'count' => 18, 'color' => 'green'],
        ['name' => 'IEC 62353', 'count' => 15, 'color' => 'orange'],
        ['name' => 'Sicurezza', 'count' => 32, 'color' => 'purple'],
        ['name' => 'Documentazione', 'count' => 28, 'color' => 'teal'],
        ['name' => 'Controlli', 'count' => 21, 'color' => 'indigo'],
        ['name' => 'Normativa', 'count' => 19, 'color' => 'red'],
        ['name' => 'Elettromedicali', 'count' => 16, 'color' => 'yellow'],
        ['name' => 'Veterinaria', 'count' => 14, 'color' => 'pink'],
        ['name' => 'Guide', 'count' => 25, 'color' => 'gray'],
        ['name' => 'Formazione', 'count' => 12, 'color' => 'cyan'],
        ['name' => 'Aggiornamenti', 'count' => 10, 'color' => 'emerald'],
    ];
    
    $style = $style ?? 'cloud'; // cloud, list, pills
    $maxTags = $maxTags ?? null;
    $showCount = $showCount ?? true;
    $selectedTags = $selectedTags ?? [];
@endphp

@if($style === 'cloud')
    {{-- Tag Cloud Style --}}
    <div class="flex flex-wrap gap-3" x-data="tagManager()">
        @foreach($tags as $tag)
            @php
                if ($maxTags && $loop->index >= $maxTags) break;
                
                $sizeClass = match(true) {
                    $tag['count'] >= 25 => 'text-2xl',
                    $tag['count'] >= 20 => 'text-xl',
                    $tag['count'] >= 15 => 'text-lg',
                    $tag['count'] >= 10 => 'text-base',
                    default => 'text-sm',
                };
                
                $colorClasses = match($tag['color'] ?? 'blue') {
                    'blue' => 'bg-blue-100 text-blue-700 hover:bg-blue-200 border-blue-200',
                    'green' => 'bg-green-100 text-green-700 hover:bg-green-200 border-green-200',
                    'orange' => 'bg-orange-100 text-orange-700 hover:bg-orange-200 border-orange-200',
                    'purple' => 'bg-purple-100 text-purple-700 hover:bg-purple-200 border-purple-200',
                    'teal' => 'bg-teal-100 text-teal-700 hover:bg-teal-200 border-teal-200',
                    'indigo' => 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200 border-indigo-200',
                    'red' => 'bg-red-100 text-red-700 hover:bg-red-200 border-red-200',
                    'yellow' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 border-yellow-200',
                    'pink' => 'bg-pink-100 text-pink-700 hover:bg-pink-200 border-pink-200',
                    'gray' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-200',
                    'cyan' => 'bg-cyan-100 text-cyan-700 hover:bg-cyan-200 border-cyan-200',
                    'emerald' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border-emerald-200',
                    default => 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-200',
                };
                
                $isSelected = in_array($tag['name'], $selectedTags);
            @endphp
            
            <button 
                @click="toggleTag('{{ $tag['name'] }}')"
                class="inline-flex items-center px-4 py-2 rounded-full font-medium transition-all duration-200 border {{ $colorClasses }} {{ $sizeClass }} {{ $isSelected ? 'ring-2 ring-offset-2 ring-blue-500 shadow-lg transform scale-105' : 'hover:shadow-md' }}"
                :class="{ 'ring-2 ring-offset-2 ring-blue-500 shadow-lg transform scale-105': selectedTags.includes('{{ $tag['name'] }}') }"
            >
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                </svg>
                {{ $tag['name'] }}
                @if($showCount)
                    <span class="ml-1.5 px-1.5 py-0.5 text-xs rounded-full bg-white/50 {{ $isSelected ? 'text-blue-700' : 'text-gray-600' }}">
                        {{ $tag['count'] }}
                    </span>
                @endif
            </button>
        @endforeach
        
        {{-- Show More Button --}}
        @if($maxTags && count($tags) > $maxTags)
            <button 
                @click="showAll = !showAll"
                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                {{ showAll ? 'Mostra meno' : 'Mostra altri' }}
            </button>
        @endif
    </div>

@elseif($style === 'list')
    {{-- List Style --}}
    <div class="space-y-2" x-data="tagManager()">
        @foreach($tags as $tag)
            @php
                if ($maxTags && $loop->index >= $maxTags) break;
                
                $colorClasses = match($tag['color'] ?? 'blue') {
                    'blue' => 'text-blue-600 hover:text-blue-700 hover:bg-blue-50',
                    'green' => 'text-green-600 hover:text-green-700 hover:bg-green-50',
                    'orange' => 'text-orange-600 hover:text-orange-700 hover:bg-orange-50',
                    'purple' => 'text-purple-600 hover:text-purple-700 hover:bg-purple-50',
                    'teal' => 'text-teal-600 hover:text-teal-700 hover:bg-teal-50',
                    'indigo' => 'text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50',
                    'red' => 'text-red-600 hover:text-red-700 hover:bg-red-50',
                    'yellow' => 'text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50',
                    'pink' => 'text-pink-600 hover:text-pink-700 hover:bg-pink-50',
                    'gray' => 'text-gray-600 hover:text-gray-700 hover:bg-gray-50',
                    'cyan' => 'text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50',
                    'emerald' => 'text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50',
                    default => 'text-gray-600 hover:text-gray-700 hover:bg-gray-50',
                };
                
                $isSelected = in_array($tag['name'], $selectedTags);
            @endphp
            
            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <div class="flex items-center">
                    <button 
                        @click="toggleTag('{{ $tag['name'] }}')"
                        class="flex items-center font-medium {{ $colorClasses }} transition-colors"
                        :class="{ 'font-bold': selectedTags.includes('{{ $tag['name'] }}') }"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $tag['name'] }}
                    </button>
                </div>
                
                <div class="flex items-center space-x-3">
                    @if($showCount)
                        <span class="text-sm text-gray-500">{{ $tag['count'] }} articoli</span>
                    @endif
                    
                    <button 
                        @click="toggleTag('{{ $tag['name'] }}')"
                        class="w-5 h-5 rounded border-2 transition-colors"
                        :class="selectedTags.includes('{{ $tag['name'] }}') ? 'bg-blue-600 border-blue-600' : 'border-gray-300 hover:border-blue-400'"
                    >
                        <svg x-show="selectedTags.includes('{{ $tag['name'] }}')" class="w-3 h-3 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

@else
    {{-- Default Pills Style --}}
    <div class="flex flex-wrap gap-2" x-data="tagManager()">
        @foreach($tags as $tag)
            @php
                if ($maxTags && $loop->index >= $maxTags) break;
                
                $colorClasses = match($tag['color'] ?? 'blue') {
                    'blue' => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                    'green' => 'bg-green-100 text-green-700 hover:bg-green-200',
                    'orange' => 'bg-orange-100 text-orange-700 hover:bg-orange-200',
                    'purple' => 'bg-purple-100 text-purple-700 hover:bg-purple-200',
                    'teal' => 'bg-teal-100 text-teal-700 hover:bg-teal-200',
                    'indigo' => 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
                    'red' => 'bg-red-100 text-red-700 hover:bg-red-200',
                    'yellow' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
                    'pink' => 'bg-pink-100 text-pink-700 hover:bg-pink-200',
                    'gray' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                    'cyan' => 'bg-cyan-100 text-cyan-700 hover:bg-cyan-200',
                    'emerald' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200',
                    default => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                };
                
                $isSelected = in_array($tag['name'], $selectedTags);
            @endphp
            
            <button 
                @click="toggleTag('{{ $tag['name'] }}')"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-all duration-200 {{ $colorClasses }} {{ $isSelected ? 'ring-2 ring-offset-2 ring-blue-500 shadow-lg' : 'hover:shadow-md' }}"
                :class="{ 'ring-2 ring-offset-2 ring-blue-500 shadow-lg': selectedTags.includes('{{ $tag['name'] }}') }"
            >
                #{{ $tag['name'] }}
                @if($showCount)
                    <span class="ml-1 text-xs opacity-75">({{ $tag['count'] }})</span>
                @endif
            </button>
        @endforeach
    </div>
@endif

<script>
function tagManager() {
    return {
        selectedTags: @json($selectedTags),
        showAll: false,
        
        toggleTag(tagName) {
            const index = this.selectedTags.indexOf(tagName);
            if (index > -1) {
                this.selectedTags.splice(index, 1);
            } else {
                this.selectedTags.push(tagName);
            }
            
            // Dispatch event for parent components
            this.$dispatch('tags-changed', { tags: this.selectedTags });
        },
        
        clearAll() {
            this.selectedTags = [];
            this.$dispatch('tags-changed', { tags: this.selectedTags });
        },
        
        selectAll() {
            this.selectedTags = @json(array_column($tags, 'name'));
            this.$dispatch('tags-changed', { tags: this.selectedTags });
        }
    }
}
</script>