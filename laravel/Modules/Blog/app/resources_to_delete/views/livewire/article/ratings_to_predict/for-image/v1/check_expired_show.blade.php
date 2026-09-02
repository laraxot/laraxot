<div class="flex flex-col border divide-y rounded-lg overflow-clip" {{-- x-show="!isloggedIn" --}}>
    <div class="flex justify-between w-full px-3 py-3 font-bold text-white bg-blue-1" style="color:white">
        <span>Your bet</span>
    </div>
    <div class="p-4">
        <div class="p-2 border border-red-400 rounded-lg bg-red-400/20">
            <span>{{ (string) __('blog::article.expired') }}</span>
        </div>
    </div>
</div>


