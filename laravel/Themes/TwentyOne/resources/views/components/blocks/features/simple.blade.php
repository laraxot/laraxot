<div class="features-grid grid grid-cols-1 md:grid-cols-3 gap-8 py-16">
  @foreach($items as $item)
  <div class="feature-card bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700 hover:border-indigo-400 transition-all">
    <div class="feature-icon w-16 h-16 rounded-full bg-indigo-500/10 flex items-center justify-center mb-6">
      <x-filament::icon :icon="$item['icon']" class="w-8 h-8 text-indigo-400" />
    </div>
    <h3 class="text-xl font-bold mb-3 text-white">{{ $item['title'] }}</h3>
    <p class="text-gray-400">{{ $item['description'] }}</p>
  </div>
  @endforeach
</div>
