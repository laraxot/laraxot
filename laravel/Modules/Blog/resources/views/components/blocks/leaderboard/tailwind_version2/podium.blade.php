<a
href="#"
class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
>
    <div
        class="lg:contents relative flex justify-center max-lg:h-max"
        >
        <img
            src="{{ $profile->getAvatarUrl() }}"
            class="size-10 
                @if($loop->iteration == 2)
                    lg:size-[7.5rem] 
                @else
                    lg:size-20 
                @endif
                rounded-full shrink-0 object-cover"
            alt="{{ $profile->full_name }}"
            />
        <span
            class="
                @if($loop->iteration == 1)
                    bg-[#fce0be] text-[#8e4e00] 
                @elseif($loop->iteration == 2)
                    bg-[#00622e] text-[#bdffdc]
                @else
                    bg-[#8c0d00] text-[#ffcac5]
                @endif
                py-1.5 px-4 max-lg:absolute max-lg:-bottom-4 lg:-mt-4 rounded-full text-xs leading-none">
                @if($loop->iteration == 1)
                    2nd
                @elseif($loop->iteration == 2)
                    1st
                @else
                    3rd
                @endif
            </span>
    </div>
    <div class="flex flex-col lg:contents">
        <span class="mb-2 lg:mt-6 text-base lg:text-xl font-semibold"
            >{{ $profile->full_name }}</span
            >
        <span class="text-base leading-none">{{ $profile->credits }}</span>
    </div>
</a>

{{-- <!-- second -->
<a
    href="#"
    class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
    >
    <div
        class="lg:contents relative flex justify-center max-lg:h-max"
        >
        <img
            src="https://My_Company-media-production.s3.amazonaws.com/cache/b1/81/b1816fa03b437898ec58f5ea571c4d4a.jpg"
            class="size-10 lg:size-20 rounded-full shrink-0 object-cover"
            alt="deagol"
            />
        <span
            class="bg-[#fce0be] text-[#8e4e00] py-1.5 px-4 max-lg:absolute max-lg:-bottom-4 lg:-mt-4 rounded-full text-xs leading-none"
            >2nd</span
            >
    </div>
    <div class="flex flex-col lg:contents">
        <span class="mb-2 lg:mt-6 text-base lg:text-xl font-semibold"
            >deagol</span
            >
        <span class="text-base leading-none">1091227.02ø</span>
    </div>
</a>
<!-- first -->
<a
    href="#"
    class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
    >
    <div
        class="lg:contents relative flex justify-center max-lg:h-max"
        >
        <img
            src="https://graph.facebook.com/v2.8/10160322108422351/picture?height=128"
            class="size-10 lg:size-[7.5rem] rounded-full shrink-0 object-cover"
            alt="pedro_brito"
            />
        <span
            class="bg-[#00622e] text-[#bdffdc] py-1.5 px-4 max-lg:absolute max-lg:-bottom-4 lg:-mt-4 rounded-full text-xs leading-none"
            >1st</span
            >
    </div>
    <div class="flex flex-col lg:contents">
        <span class="mb-2 lg:mt-6 text-base lg:text-xl font-semibold"
            >pedro_brito</span
            >
        <span class="text-base leading-none">2246179.68ø</span>
    </div>
</a>
<!-- third -->
<a
    href="#"
    class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
    >
    <div
        class="lg:contents relative flex justify-center max-lg:h-max"
        >
        <img
            src="https://My_Company-media-production.s3.amazonaws.com/cache/1a/3f/1a3faf36f6b5cc0e6c26a2aeffa44e4a.jpg"
            class="size-10 lg:size-20 rounded-full shrink-0 object-cover"
            alt="Cleiton5656"
            />
        <span
            class="bg-[#8c0d00] text-[#ffcac5] py-1.5 px-4 max-lg:absolute max-lg:-bottom-4 lg:-mt-4 rounded-full text-xs leading-none"
            >3rd</span
            >
    </div>
    <div class="flex flex-col lg:contents">
        <span class="mb-2 lg:mt-6 text-base lg:text-xl font-semibold"
            >Cleiton5656</span
            >
        <span class="text-base leading-none">239748.90ø</span>
    </div>
</a> --}}
