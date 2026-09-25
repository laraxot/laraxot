<div
    class="max-h-[100rem] bg-white rounded-2xl p-6 overflow-auto [scrollbar-width:none]">
    <div
        x-cloak
        class="lg:mb-10 flex flex-col lg:flex-row lg:items-end lg:justify-between"
        >

        @php
            $profiles_by_credits = $_theme->rankingProfilesByCredits();
            $podium_profiles = $profiles_by_credits->take(3);
            $no_podium_profiles = $profiles_by_credits->skip(3);

            $primiDueElementi = $podium_profiles->take(2);
            $primiDueElementiInvertiti = $primiDueElementi->reverse();
            $podium_profiles = $primiDueElementiInvertiti->merge($podium_profiles->slice(2));
        @endphp

        @foreach($podium_profiles as $profile)
            @include('blog::components.blocks.leaderboard.tailwind_version2.podium')
        @endforeach

    </div>
    <hr class="my-4 lg:hidden bg-transparent border-2 border-neutral-2" />
    <div class="space-y-6">
        <div class="flex gap-4 flex-wrap justify-between">
            <table class="w-full divide-y divide-gray-300">
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($no_podium_profiles as $profile)
                        @php
                            $pos = $loop->iteration + 3;
                        @endphp

                        @include('blog::components.blocks.leaderboard.tailwind_version2.no_podium')
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="flex justify-center">
            <button
                class="text-blue-6 hover:text-[#0f79c8] font-semibold text-base max-w-[320px] w-[90%]"
                >
            Load more
            </button>
        </div> --}}
    </div>
</div>