<a href="#" class="userpart__userlist">
    <ul>
        @foreach($_theme->rankingProfilesByCredits() as $profile)
            <li class="d-flex align-items-center justify-content-between mb-1">
                <div class="userpart__rightside d-flex align-items-center gap-2 pb-1">
                    @php
                        $n_badge = $loop->index + 1;
                    @endphp
                    <span class="userpart__number">{{ $n_badge }}</span>
                    @php
                        if($profile->avatar == ''){
                            $url = $_theme->asset('pub_theme::assets/images/trader2.png');
                        }else{
                            $url = $profile->avatar;
                        }
                    @endphp
                    <img class="userpart__pimage" src="
                        {{-- {{ $_theme->asset('pub_theme::assets/images/volumebadge4.png') }} --}}
                        {{ $url }}
                        " alt="Image"
                        style="widht:30px;height:30px;aspect-ratio: 1/1;"
                        >
                    <span>{{ $profile->full_name }}</span>
                    @if($n_badge < 4)
                        <img class="userpart__badge ms-3 ms-md-4" src="{{ $_theme->asset('pub_theme::assets/images/icon/volumebadge'.$n_badge.'.png') }}" alt="Image">
                    @endif
                </div>
                <div class="userpart__leftside">
                    <span>{{ $profile->credits }}</span>
                </div>
            </li>
        @endforeach
    </ul>
</a>
