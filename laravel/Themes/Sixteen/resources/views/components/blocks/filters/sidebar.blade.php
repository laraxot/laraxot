{{--
    Filters Sidebar Block - Design Comuni Style with Tailwind CSS
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
--}}
@props(['title' => 'Filtro', 'filters' => [], 'resultsCount' => 0])

<div class="lg:hidden mb-6">
    <!-- Mobile filters would go here -->
</div>

<div class="hidden lg:block">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 uppercase">{{ $title }}</h3>

        <div class="space-y-3">
            @foreach($filters as $filter)
            <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500"
                       id="{{ $filter['id'] }}" name="category" value="{{ $filter['value'] ?? '' }}">
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-900">{{ $filter['icon'] ?? '' }} {{ $filter['label'] }}</span>
                    <span class="text-xs text-gray-500 block">{{ $filter['count'] ?? 0 }}</span>
                </div>
            </label>
            @endforeach
        </div>

        {{-- Results Button --}}
        <div class="mt-6">
            <button class="w-full bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition font-medium">
                {{ $resultsCount }} Risultati
            </button>
        </div>
    </div>
</div>
