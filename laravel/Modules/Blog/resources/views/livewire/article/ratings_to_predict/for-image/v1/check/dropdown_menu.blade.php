<button
	id="dropdownHoverButton"
	data-dropdown-toggle="dropdownHover"
	data-dropdown-trigger="hover"
	class="bg-transparent hover:bg-white focus:ring-4 focus:outline-none focus:bg-white font-medium rounded-lg text-sm px-3 py-2.5 text-center text-black inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
	type="button"
	>
	<span {{-- x-text="selection" --}}></span>
	<svg
		class="w-2.5 h-2.5 ms-3"
		aria-hidden="true"
		xmlns="http://www.w3.org/2000/svg"
		fill="none"
		viewBox="0 0 10 6"
		>
		<path
			stroke="currentColor"
			stroke-linecap="round"
			stroke-linejoin="round"
			stroke-width="2"
			d="m1 1 4 4 4-4"
			/>
	</svg>
</button>


<div
    id="dropdownHover"
    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
    >
    <ul
        class="py-2 text-sm text-gray-700 dark:text-gray-200"
        aria-labelledby="dropdownHoverButton"
        >
        {{-- {{ dddx(get_defined_vars()) }} --}}
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



@php
    /*



<div class="">
	<button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover" data-dropdown-trigger="hover" 
		class="bg-transparent hover:bg-white focus:ring-4 focus:outline-none focus:bg-white font-medium rounded-lg text-sm px-3 py-2.5 text-center text-black inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
		<span></span>
		<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
			<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"></path>
		</svg>
	</button>
	<!-- Dropdown menu -->
	<div id="dropdownHover" 
		class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 block" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(712px, 200px);" data-popper-placement="bottom">
		<ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
			<!--[if BLOCK]><![endif]-->            
			<li>
				<a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
				cccc
				</a>
			</li>
			<li>
				<a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
				ssss
				</a>
			</li>
			<!--[if ENDBLOCK]><![endif]-->
		</ul>
	</div>
</div>


<div class="">
	<button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover" data-dropdown-trigger="hover" 
		class="bg-transparent hover:bg-white focus:ring-4 focus:outline-none focus:bg-white font-medium rounded-lg text-sm px-3 py-2.5 text-center text-black inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
		<span></span>
		<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
			<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"></path>
		</svg>
	</button>
	<!-- Dropdown menu -->
	<div id="dropdownHover" 
		class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1152px, 1107px);" data-popper-placement="bottom">
		<ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
			<!--[if BLOCK]><![endif]-->            
			<li>
				<a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
				cccc
				</a>
			</li>
			<li>
				<a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
				ssss
				</a>
			</li>
			<!--[if ENDBLOCK]><![endif]-->
		</ul>
	</div>
</div>


    */
@endphp
