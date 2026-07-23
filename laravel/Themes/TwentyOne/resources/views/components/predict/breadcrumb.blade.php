@props([
    'container0' => 'predicts',
    'title' => '',
])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
        <li>
            <a href="{{ url('/' . app()->getLocale()) }}" 
               class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                Home
            </a>
        </li>
        <li>
            <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
        </li>
        <li>
            <a href="{{ url('/' . app()->getLocale() . '/' . $container0) }}" 
               class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                {{ ucfirst($container0) }}
            </a>
        </li>
        <li>
            <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
        </li>
        <li aria-current="page" class="text-slate-900 dark:text-white font-medium truncate max-w-md">
            {{ Str::limit($title, 60) }}
        </li>
    </ol>
</nav>
