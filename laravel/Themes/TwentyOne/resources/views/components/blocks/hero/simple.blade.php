<div class="hero-gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-500/10"></div>
  <div class="container relative z-10 py-16 text-center">
    <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white drop-shadow-lg">
      {{ $title }}
    </h1>
    <p class="text-xl max-w-2xl mx-auto mb-8 text-white/90">
      {{ $subtitle }}
    </p>
    <a href="{{ $cta_link }}" class="btn-neon px-8 py-4 text-lg font-medium">
      {{ $cta_text }}
    </a>
  </div>
</div>
