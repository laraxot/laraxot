<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		{{--  
		{{ $_theme->metatags() }}
		--}}
		{{-- <meta charset="utf-8">
		<meta name="application-name" content="{{ config('app.name') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('app.name') }}</title>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet"> --}}

		<style>
			[x-cloak] {
			display: none !important;
			}
		</style>
		@filamentStyles

		@vite(['resources/css/app.css'],'themes/TwentyOne')

		<!-- Matomo -->
		<script>
			var _paq = window._paq = window._paq || [];
			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function() {
			var u="//stats.<nome progetto>officina.it/";
			_paq.push(['setTrackerUrl', u+'matomo.php']);
			_paq.push(['setSiteId', '2']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<!-- End Matomo Code -->


	</head>
	<body class="antialiased">
        
		<x-section slug="header" />

        {{ $slot }}
        
		<x-section slug="footer" />


		<!-- mobile tabs -->
		@include('pub_theme::layouts.mobile_tabs')


		
        @livewire('notifications')

		@filamentScripts
    	@vite(['resources/js/app.js'],'themes/TwentyOne')
		

        @yield('scripts')

    </body>
</html>
