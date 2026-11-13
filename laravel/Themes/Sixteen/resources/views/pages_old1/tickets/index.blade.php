@extends('sixteen::layouts.app')

@section('title', __('pub_theme::legacy_prototypes.listing.page_title.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Comunale -->
    <header class="bg-gradient-to-r from-blue-800 to-blue-900 text-white">
        <!-- Top Bar -->
        <div class="bg-blue-900 py-2">
            <div class="container mx-auto px-4 flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <span>{{ __('pub_theme::legacy_prototypes.chrome.region.label') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <span>{{ __('pub_theme::legacy_prototypes.chrome.dark_mode.label') }}</span>
                    </button>
                    <button class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ __('pub_theme::legacy_prototypes.chrome.language_code.label') }}</span>
                    </button>
                    <a href="#" class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ __('pub_theme::legacy_prototypes.chrome.personal_area.label') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-12 h-12 bg-white rounded border-2 border-purple-500 flex items-center justify-center">
                            <span class="text-blue-800 font-bold text-lg">it</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">{{ __('pub_theme::legacy_prototypes.chrome.municipality.title') }}</h1>
                            <p class="text-sm text-blue-200">{{ __('pub_theme::legacy_prototypes.chrome.tagline.label') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">{{ __('pub_theme::legacy_prototypes.chrome.follow_us.label') }}</span>
                        <div class="flex space-x-2">
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">X</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">f</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">YT</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">IG</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">in</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="{{ __('pub_theme::legacy_prototypes.chrome.search.placeholder') }}" class="px-3 py-2 rounded text-gray-800 text-sm w-64">
                        <button class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
                </div>

        <!-- Navigation -->
        <nav class="bg-blue-700">
            <div class="container mx-auto px-4">
                <div class="flex space-x-8 py-3">
                    <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_administration.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_news.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_services.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_community.label') }}</a>
                </div>
                <div class="flex space-x-6 py-2 border-t border-blue-600">
                    <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_enrollments.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_summer.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_local_police.label') }}</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_all_topics.label') }} ></a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="#" class="hover:text-blue-600">{{ __('pub_theme::tickets_list.breadcrumb.home.label') }}</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">{{ __('pub_theme::tickets_list.page.title.title') }}</span>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('pub_theme::tickets_list.page.title.title') }}</h1>
            <p class="text-lg text-gray-600">{!! __('pub_theme::legacy_prototypes.listing.resolved.message', ['months' => 12, 'count' => '<span class="font-semibold text-green-600">73</span>']) !!}</p>
                </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Categories -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('pub_theme::tickets_list.filters.category.title') }}</h3>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::legacy_prototypes.listing.category_water_sewage.label') }} <span class="text-blue-600 font-medium">(21)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.environment.label') }} <span class="text-blue-600 font-medium">(14)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.street_furniture.label') }} <span class="text-blue-600 font-medium">(7)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::legacy_prototypes.listing.category_pest_control.label') }} <span class="text-blue-600 font-medium">(208)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.urban_hygiene.label') }} <span class="text-blue-600 font-medium">(321)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.buildings.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.public_order.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.parks.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.municipal_services.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.safety.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('pub_theme::tickets_list.categories.roads.label') }} <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Content - Map/List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border">
                    <!-- Results Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <h2 class="text-xl font-semibold text-gray-900">{{ __('pub_theme::tickets_list.filters.results.label', ['count' => 645]) }}</h2>
                                <div class="flex space-x-1">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-l-md font-medium">{{ __('pub_theme::tickets_list.view_toggle.map.label') }}</button>
                                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-r-md font-medium hover:bg-gray-200">{{ __('pub_theme::tickets_list.view_toggle.list.label') }}</button>
                                </div>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">{{ __('pub_theme::legacy_prototypes.listing.clear_filters.label') }}</a>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div class="h-96 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-gray-500">{{ __('pub_theme::legacy_prototypes.listing.map_placeholder.label') }}</p>
                            <p class="text-sm text-gray-400 mt-2">{{ __('pub_theme::legacy_prototypes.listing.map_location.label') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection