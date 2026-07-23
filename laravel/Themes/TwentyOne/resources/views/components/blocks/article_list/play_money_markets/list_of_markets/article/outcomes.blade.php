<div class="flex flex-nowrap overflow-x-auto lg:grid lg:grid-cols-3 gap-2 pl-6 lg:pl-0">
    {{-- <template x-for="outcome in market.outcomes"> --}}
        @foreach($datas as $data)
            <div {{-- wire:click="something" --}}
                class="p-1.5 flex flex-col justify-between gap-10 w-[128px] lg:w-auto min-w-[128px] isolate relative overflow-hidden rounded-lg group/outcome">
                <div class="absolute inset-0 -z-[1]">
                    <img
                        class="object-cover h-full w-full"
                        alt="{{ $data['title'] }}"
                        title="{{ $data['title'] }}"
                        src="{{ $data['image'] }}"
                        sizes="144px"
                        />
                    <div class="absolute inset-0 group-hover/outcome:bg-blue-1/50"></div>
                </div>
                <div class="bg-neutral-5 h-8 w-11 rounded-sm flex items-center justify-center text-white">
                    <span>66%</span>
                </div>
                <p class="text-sm font-medium text-white leading-[1.1]">
                    {{ $data['title'] }}
                </p>
            </div>
        @endforeach
    {{-- </template> --}}
</div>