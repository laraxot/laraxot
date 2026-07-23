{{-- System Info Sidebar - Theme Two --}}
<div class="bg-gray-50 rounded p-4 mb-4">
    @if(isset($title))
        <h3 class="font-semibold text-gray-900 mb-3">{{ $title }}</h3>
    @endif

    @if(isset($info) && is_array($info))
        <dl class="space-y-2">
            @foreach($info as $item)
                <div class="flex justify-between text-sm">
                    @if(isset($item['label']))
                        <dt class="text-gray-600">{{ $item['label'] }}:</dt>
                    @endif
                    @if(isset($item['value']))
                        <dd class="text-gray-900 font-mono text-xs">
                            @if(str_contains($item['value'], '{{') && str_contains($item['value'], '}}'))
                                {!! \Illuminate\Support\Facades\Blade::render($item['value']) !!}
                            @else
                                {{ $item['value'] }}
                            @endif
                        </dd>
                    @endif
                </div>
            @endforeach
        </dl>
    @endif

    <div class="mt-3 pt-3 border-t border-gray-300">
        <div class="flex items-center text-xs text-gray-500">
            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
            Sistema Attivo
        </div>
    </div>
</div>
