<div class="middle-area">
    <div class="row banner-bg mt30">
      <div class="col-lg-10 col-xl-7">
        <div class="banner overflow-hidden">
          <h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay=".5s">
            {{ $title }}
          </h1>
          <div class="btn_group mt40 d-flex flex-wrap gap-2 gap-md-3 wow fadeInUp" data-wow-duration="1s"
            data-wow-delay=".5s">
            <a class="button-10 f16" href="javascript:void(0)">Explore Now</a>
            <a class="button-10 f16" href="javascript:void(0)">Create a Account</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row position-relative mt20">
      <div class="card_slider p-0">
        @foreach($_theme->getFeaturedArticles() as $article)
            <div class="card_part">
                <div class="card_thumb mb28">
                    <a href="{{ $this->url('show', ['record' => $article]) }}"><img src="{{ $article->getMainImage() }}" alt="Image" style="aspect-ratio: 4/2;"></a>
                </div>
                <a href="{{ $this->url('show', ['record' => $article]) }}"><span class="f18 mb16 card_title">{{ $article->title }}</span></a>
                <div class="balance_group d-flex gap-2 gap-md-3">
                    @foreach($article->getOptionRatingsIdTitle() as $rating)
                        @if($loop->index == 0)
                            <span class="yes_style">{{ $rating }}</span>
                        @elseif($loop->index == 1)
                            <span class="no_style">{{ $rating }}</span>
                        @elseif($loop->index == 2)
                            <span class="card__yes d-block">More</span>
                        @endif
                    @endforeach
                    {{-- <span class="yes_style">Yes 14¢</span>
                    <span class="no_style">No 86¢</span> --}}
                </div>
            </div>
        @endforeach
        {{-- <div class="card_part">
          <div class="card_thumb mb28">
            <a href="javascript:void(0)"><img src="{{ $_theme->asset('pub_theme::assets/images/betcoin.png') }}" alt="Image"></a>
          </div>
          <a href="javascript:void(0)"><span class="f18 mb16 card_title">Will $BTC reach $32,500 by July
              14?</span></a>
          <div class="balance_group d-flex gap-2 gap-md-3">
            <span class="yes_style">Yes 14¢</span>
            <span class="no_style">No 86¢</span>
          </div>
        </div>
        <div class="card_part">
          <div class="card_thumb mb28">
            <a href="javascript:void(0)">
              <img src="{{ $_theme->asset('pub_theme::assets/images/betcoin.png') }}" alt="Image"></a>
          </div>
          <a href="javascript:void(0)">
            <span class="f18 mb16 card_title">Will $BTC reach $32,500 by July 14?</span></a>
          <div class="balance_group d-flex gap-2 gap-md-3">
            <span class="yes_style">Yes 14¢</span>
            <span class="no_style">No 86¢</span>
          </div>
        </div>
        <div class="card_part">
          <div class="card_thumb mb28">
            <a href="javascript:void(0)">
              <img src="{{ $_theme->asset('pub_theme::assets/images/betcoin.png') }}" alt="Image">
            </a>
          </div>
          <a href="javascript:void(0)">
            <span class="f18 mb16 card_title">Will Kane sign for Bayern Munich?</span>
          </a>
          <div class="balance_group d-flex gap-2 gap-md-3">
            <span class="yes_style">Yes 14¢</span>
            <span class="no_style">No 86¢</span>
          </div>
        </div>
        <div class="card_part">
          <div class="card_thumb mb28">
            <a href="javascript:void(0)">
              <img src="{{ $_theme->asset('pub_theme::assets/images/navak.png') }}" alt="Image"></a>
          </div>
          <a href="javascript:void(0)">
            <span class="f18 mb16 card_title">Will Novak Djokovic win Wimbledon?</span>
          </a>
          <div class="balance_group d-flex gap-2 gap-md-3">
            <span class="yes_style">Yes 14¢</span>
            <span class="no_style">No 86¢</span>
          </div>
        </div> --}}
      </div>
      <div class="arrow_button top-50 d-flex justify-content-between position-absolute">
        <div class="prev_button prev-feedback">
          <i id="icon1" class="icon material-symbols-outlined">
            chevron_left
          </i>
        </div>
        <div class="next_button next-feedback">
          <i id="icon2" class="icon material-symbols-outlined">
            chevron_right
          </i>
        </div>
      </div>
    </div>
  </div>