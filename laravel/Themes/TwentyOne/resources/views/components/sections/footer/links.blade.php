<div class="flex justify-end gap-6 md:gap-12">

    @php
        $links = $_theme->getMenu('footer');
        $primiCinqueElementi = array_slice($links, 0, 5);
        $arraySenzaPrimiCinque = array_values(array_slice($links, 5));
    @endphp

    <div>
        <ul class="space-y-2" 
            {{-- x-data="{links:['Sign up now','Tags','About My_Company','FAQ','We\'re hiring']}" --}}
            >
            @foreach($primiCinqueElementi as $menu)
                <li>
                    <a href="
                        {{-- route('page_slug.show',['lang'=>$lang,'page_slug'=>$menu['slug']]) --}}
                        {{-- {{ $menu['type'] == 'internal' ? route('page_slug.show', ['lang'=>$lang,'page_slug' => $menu['url'] ]) : $menu['url']}} --}}
                        {{ $_theme->getMenuUrl($menu) }}
                        "
                    class="text-sm text-gray-300 hover:text-white"
                        >
                        {{ $menu['title'] }}
                    </a>
                </li>
            @endforeach
            {{-- <template x-for="link in links">
                <li>
                    <a href="#" class="text-sm text-gray-300 hover:text-white"
                        x-text="link">
                    </a>
                </li>
            </template> --}}
        </ul>
    </div>
    <div>
        <ul class="space-y-2"
            {{-- x-data="{links:['Community','Suggest a Market','Report a bug','Contact Us','Terms and Conditions','Privacy Policy']}" --}}
            >

            @foreach($arraySenzaPrimiCinque as $menu)
                <li>
                    <a href="
                        {{-- route('page_slug.show',['lang'=>$lang,'page_slug'=>$menu['slug']]) --}}
                        {{ $menu['type'] == 'internal' ? route('page_slug.view', ['lang'=>$lang,'slug' => $menu['url'] ]) : $menu['url']}}
                        "
                    class="text-sm text-gray-300 hover:text-white"
                        >
                        {{ $menu['title'] }}
                    </a>
                </li>
            @endforeach


            {{-- <template x-for="link in links">
                <li>
                    <a href="#"
                         class="text-sm text-gray-300 hover:text-white"
                        x-text="link">
                    </a>
                </li>
            </template> --}}
        </ul>
    </div>
</div>
