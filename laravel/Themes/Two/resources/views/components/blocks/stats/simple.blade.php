@props(['stats' => []])

<section class="py-16 bg-[#0d2d4d] text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($stats as $stat)
                <div class="text-center group">
                    <div class="text-4xl lg:text-5xl font-bold text-[#2D8659] mb-2 group-hover:scale-110 transition-transform duration-300">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-sm lg:text-base font-medium text-gray-300 tracking-wide uppercase">
                        {{ $stat['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
