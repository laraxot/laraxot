<footer class="bg-white" role="contentinfo">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Informazioni -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('theme::footer.about.title') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('theme::footer.about.description') }}
                </p>
            </div>

            <!-- Link Utili -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('theme::footer.links.title') }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('theme::footer.links.privacy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('theme::footer.links.terms') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('theme::footer.links.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contatti -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('theme::footer.contact.title') }}
                </h3>
                <ul class="space-y-2">
                    <li class="text-gray-600">
                        <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                        {{ config('mail.from.address') }}
                    </li>
                    <li class="text-gray-600">
                        <i class="fas fa-phone mr-2" aria-hidden="true"></i>
                        {{ config('app.phone') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-center text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('theme::footer.copyright') }}
            </p>
        </div>
    </div>
</footer>
