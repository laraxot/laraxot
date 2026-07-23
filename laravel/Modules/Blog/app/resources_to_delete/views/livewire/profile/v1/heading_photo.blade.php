<div class="lg:w-1/3 pr-4 pl-4">
    <div class="boardprofile bio wow fadeInDown">
      <div class="boardprofile__thumb mb20 text-center">

        @php
            if($this->model->avatar = ''){
              $url = $_theme->asset('pub_theme::assets/images/bio_profile.png');
            }else{
              $url = $this->model->avatar;
            }
        @endphp


        <img src="
          {{-- {{ $this->model->getFirstMediaUrl('photo_profile') }} --}}
          {{-- {{ $_theme->asset('pub_theme::assets/images/bio_profile.png') }} --}}
          {{ $url }}
          " alt="Image"
          {{-- style="wight:180px;height:180px;aspect-ratio: 1/1;" --}}
          >
      </div>
      <div class="boardprofile__profile-text bio__dotted text-center">
        <span class="bio__name mb10 block">{{ $this->model->full_name }}</span>
        {{-- <div class="parent justify-center">
          <span class="tlt"></span>
          <span class="left">2xS7C70e458024e8F8DA......</span>
          <div class="right">
            <i class="material-symbols-outlined"> content_copy </i>
          </div>
        </div> --}}
        {{-- <p class="f14 mb-2 lg:mb-4">
          Nam congue gravida justo. Morbi sed rhoncus ipsum, nec ven.
        </p> --}}
      </div>
      <div class="bio__info mt20 pb-2 lg:pb-4 bio__dotted">
        <span class="mb10">BIO</span>
        <p class="f14">
          Nam congue gravida justo. Morbi sed rhoncus ipsum, nec ven.
        </p>
      </div>
      <div class="bio__location bio__dotted mt20 pb-2 lg:pb-4">
        <span class="mb-1 sm:mb-2 block">{{ $this->model->email }}</span>
        <span>LONDON, United Kingdom</span>
      </div>
      <div class="bio__member-date mt20">
        <span>Iscritto dal {{ $this->model->created_at->format('d/m/y') }}</span>
      </div>
    </div>
</div>