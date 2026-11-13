<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-blue-600 text-sm">← {{ __('pub_theme::ui.mobile.home') }}</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2">{{ __('pub_theme::ui.mobile.new_report_title') }}</h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 pt-16">
        <form action="#" method="POST" class="space-y-4">
            <div>
                {{-- Mobile: campi a 16px (text-base: sotto, iOS zooma al focus), label associata con for/id, altezza >= 44px (py-3). --}}
                <label for="ticket-title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('pub_theme::ui.mobile.title') }}</label>
                <input type="text" id="ticket-title" name="title" autocomplete="off" enterkeyhint="next" placeholder="{{ __('pub_theme::ui.mobile.describe_problem') }}" class="w-full min-h-11 border border-gray-300 rounded-lg px-3 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="ticket-category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('pub_theme::ui.mobile.category') }}</label>
                <select id="ticket-category" name="category" class="w-full min-h-11 border border-gray-300 rounded-lg px-3 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('pub_theme::ui.mobile.select_category') }}</option>
                    <option value="road_maintenance">{{ __('pub_theme::ui.mobile.categories.road_maintenance') }}</option>
                    <option value="street_lighting">{{ __('pub_theme::ui.mobile.categories.street_lighting') }}</option>
                    <option value="waste">{{ __('pub_theme::ui.mobile.categories.waste') }}</option>
                    <option value="public_green">{{ __('pub_theme::ui.mobile.categories.public_green') }}</option>
                    <option value="vandalism">{{ __('pub_theme::ui.mobile.categories.vandalism') }}</option>
                    <option value="signage">{{ __('pub_theme::ui.mobile.categories.signage') }}</option>
                    <option value="transport">{{ __('pub_theme::ui.mobile.categories.transport') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pub_theme::ui.mobile.photo') }}</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <div class="text-4xl mb-2">📷</div>
                    <p class="text-sm text-gray-500">{{ __('pub_theme::ui.mobile.take_photo') }}</p>
                    <button type="button" class="mt-2 min-h-11 px-4 text-sm text-blue-600 font-medium">{{ __('pub_theme::ui.mobile.open_camera') }}</button>
                </div>
            </div>

            <div>
                <label for="ticket-location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('pub_theme::ui.mobile.location') }}</label>
                <div class="border border-gray-300 rounded-lg p-3 flex items-center gap-2">
                    <div class="text-lg" aria-hidden="true">📍</div>
                    <input type="text" id="ticket-location" name="location" placeholder="{{ __('pub_theme::ui.mobile.gps_location') }}" class="flex-1 min-w-0 border-0 p-0 text-base focus:ring-0" readonly>
                    <button type="button" class="min-h-11 min-w-11 px-2 text-blue-600 text-sm font-medium">{{ __('pub_theme::ui.mobile.detect') }}</button>
                </div>
            </div>

            <div>
                <label for="ticket-description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('pub_theme::ui.mobile.description') }}</label>
                <textarea id="ticket-description" name="description" rows="4" placeholder="{{ __('pub_theme::ui.mobile.describe_detail') }}" class="w-full border border-gray-300 rounded-lg px-3 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full min-h-12 bg-blue-600 text-white font-semibold py-3 rounded-lg text-base">{{ __('pub_theme::ui.mobile.submit_report') }}</button>
        </form>
    </div>
</x-mobile::layout>
