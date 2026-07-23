<div class="volume-area mb20">
    <div class="liclick">
      <span class="d-flex align-items-center">
        <a href="javascript:void(0);" class="mclick d-flex align-items-center w-100 justify-content-between">
          <span class="d-flex d-flex align-items-center">
            {{ $title }}
          </span>
          <span class="d-flex side_arrow align-items-center">
            <i class="material-symbols-outlined plus">
              arrow_drop_down
            </i>
          </span>
        </a>
      </span>
      <div class="menucontent">
        <ul>
          <li>
            <label class="dropdown-option select-checkbox mt20">
              All
              <input id="selectAll" type="checkbox" name="dropdown-group" value="Selection 1">
            </label>
            <ul class="mt8">
              <li>
                <label class="ms-2 dropdown-option">
                  Chat Bots
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>

          @foreach($_theme->getArticlesType($type) as $item)
            <li>
                <label class="dropdown-option select-checkbox mb8">
                {{ $item->title }}
                <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
                </label>
                <ul>
                    @foreach($item->children as $child)
                        <li>
                            <label class="ms-2 dropdown-option">
                            {{ $child->title }}
                            <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                            </label>
                        </li>
                    @endforeach
                {{-- <li>
                    <label class="ms-2 dropdown-option">
                    Billionaires
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Commodity prices
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Fed interest rates
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Finance
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Forex
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Inflation
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Tech
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Travel
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                </li>
                <li>
                    <label class="ms-2 dropdown-option">
                    Unemployment
                    <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                    </label>
                    <hr class="left-side-border2">
                </li> --}}
                </ul>
            </li>
          @endforeach
          {{-- <li>
            <label class="dropdown-option select-checkbox mb8">
              Business
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Billionaires
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Commodity prices
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Fed interest rates
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Finance
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Forex
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Inflation
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Tech
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Travel
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Unemployment
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>
          <li>
            <label class="dropdown-option select-checkbox mb8">
              Coronavirus
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Cases
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Vaccinations
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Variants
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>

          <li>
            <label class="dropdown-option select-checkbox mb8">
              Crypto
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Airdrops
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Exchanges
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Exploits
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Market caps
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Prices
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Stablecoins
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Exploits
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>
          <li>
            <label class="dropdown-option select-checkbox mb8">
              NFTs
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Floor prices
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>
          <li>
            <label class="dropdown-option select-checkbox mb8">
              Politics
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Biden
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Bills & Legislation
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Courts
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Elections
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Global Politics
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Polls
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  US politics
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
                <hr class="left-side-border2">
              </li>
            </ul>
          </li>
          <li>
            <label class="dropdown-option select-checkbox mb8">
              Sports
              <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 2">
            </label>
            <ul>
              <li>
                <label class="ms-2 dropdown-option">
                  Baseball
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Basketball
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Biking
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Boxing/MMA
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Chess
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  Olympics
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
              <li>
                <label class="ms-2 dropdown-option">
                  US politics
                  <input class="checkbox" type="checkbox" name="dropdown-group" value="Selection 1">
                </label>
              </li>
            </ul>
          </li> --}}
        </ul>
      </div>
    </div>
  </div>
  <hr class="left-side-border">