<div
    id="dropdownHover"
    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
    >
    <ul
        class="py-2 text-sm text-gray-700 dark:text-gray-200"
        aria-labelledby="dropdownHoverButton"
        >

        @foreach($article_ratings as $key => $rating)
            <li>
                <a
                    href="#"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    wire:click="updateRating({{ $key }}, '{{ $rating }}')"
                    >
                    {{ $rating }}
                </a>
            </li>
        @endforeach

        {{-- <li>
            <a
                href="#"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                >Yes</a
                >
        </li>
        <li>
            <a
                href="#"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                >No</a
                >
        </li> --}}
    </ul>
</div>