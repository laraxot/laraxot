<ul class="flex flex-wrap items-center gap-1">
    <template x-for="filter in filters">
        <li>
            <button @click="currentFilter = filter"
                class="px-4 py-2 text-sm font-semibold rounded"
                :class="currentFilter === filter ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800'"
                x-text="filter"></button>
        </li>
    </template>
</ul>