<div class="right-side-area">
    <div class="hide-list mt30 d-flex gap-3 justify-content-end">
      <button class="none_two slide-toggle trader-list d-center gap-1">
        <i class="material-symbols-outlined mat-icon"> tune </i>
        <span>Buy & Sell</span>
      </button>
    </div>
    <div class="tow_area box"
      style="width:400px"
      {{-- non so se va bene per tutti gli schermi --}}
      >
      <div class="buysell wo fadeInDown" data-wow-duration="1s" data-wow-delay=".5s">
        {{-- <div class="buysell__markets d-flex align-items-center justify-content-between mb16">
          <div class="buysell__right">
            <span id="changePriceButton1" class="buysell__buy activate-form active-btn">Buy</span>
            <span id="changePriceButton2" class="buysell__sell activate-form">Sell</span>
          </div>
          <div class="buysell__left">
            <select>
              <option data-display="Market">Market</option>
              <option value="1">Some</option>
              <option value="2">Another</option>
              <option value="3" disabled>disabled</option>
              <option value="4">Potato</option>
            </select>
          </div>
        </div> --}}
        <div class="buysell__outcome d-flex align-items-center justify-content-between">
          <span class="buysell__come-text mb10">Outcome</span>
          {{-- <div class="buysell__setting d-center gap-2">
            <a href="javascript:void(0)"><i class="material-symbols-outlined"> sync </i></a>
            <a href="settings.html"><i class="material-symbols-outlined"> settings </i></a>
          </div> --}}
        </div>


        <form
                wire:submit="save"
                >

            {{-- {{ dddx($this) }} --}}
            {{-- {{ $this->form }} --}}
            <div class="buysell__login mb20">
                <div class="input_item">

                {{ $this->form }}
                {{-- {{ dddx(get_defined_vars()) }}
                <x-filament::input
                    type="number"
                    wire:model="rating"
                    class="form-control mb10 buysell__login-input"
                /> --}}



                </div>
            </div>


            {{-- <x-filament::input.wrapper>
                <x-filament::input
                type="text"
                wire:model="name"
            />
            </x-filament::input.wrapper> --}}


            {{-- Actions replaced with explicit buttons --}}

            @if(Auth::check())
                <x-filament::button color="danger" wire:click="save" class="button-big-3 w-100">
                    Add Rating
                </x-filament::button>
            @else
                <x-filament::button color="danger" href="{{ url('login') }}" class="button-big-3 w-100">
                    Login
                </x-filament::button>
            @endif

        </form>





        {{-- <div class="buysell__btngroup d-flex justify-content-between align-items-center gap-2 mb20">
          <a class="button-3 price" href="javascript:void(0)">Buy Yes 75¢</a>
          <a class="button-4 price_two" href="javascript:void(0)">Buy No 29¢</a>
        </div>
        <div class="buysell__login mb20">
          <form>
            <div class="input_item">
              <label class="form-label mb10">Amount</label>
              <input type="number" class="form-control mb10 buysell__login-input" placeholder="$0">
            </div>
            <button type="submit" class="button-big-3 w-100">
              Login
            </button>
          </form>
        </div> --}}
        {{-- <div class="buysell__amount">
          <div class="buysell__avg d-flex justify-content-between align-items-center">
            <span>Avg price</span>
            <span class="buysell__credit">0.0¢</span>
          </div>
          <div class="buysell__avg d-flex justify-content-between align-items-center">
            <span>Shares</span>
            <span>0.00</span>
          </div>
          <div class="buysell__avg d-flex justify-content-between align-items-center">
            <span>Potential return</span>
            <span class="buysell__credit-tow">$0.00 (0.00%)</span>
          </div>
        </div> --}}
      </div>
    </div>
</div>