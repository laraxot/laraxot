<section>
    <div class="container mx-auto sm:px-4 mb50 padding-top padding-bottoms">
      <!-- Profile heading name photo start-->
      <div class="flex flex-wrap  gape">
        @include('blog::livewire.profile.v1.heading_photo')

        <div class="lg:w-2/3 pr-4 pl-4">
          <div class="bioform wow fadeInDown">
            <h4>Account</h4>
            {{-- <form> --}}
            <x-filament-panels::form
              wire:submit="save"
              >
              <div class="flex flex-wrap  gape mt30">


                {{-- {{ $this->form }} --}}


                <div class="md:w-1/2 pr-4 pl-4">
                  <label class="f18 mb-1 md:mb-2" for="fname">First name:</label>
                  {{-- <input type="text" id="fname" name="fname" placeholder="Brian "> --}}
                  <x-filament::input
                      type="text"
                      wire:model="data.first_name"
                  />

                </div>
                <div class="md:w-1/2 pr-4 pl-4">
                  <label class="f18 mb-1 md:mb-2" for="lname">Last name:</label>
                  {{-- <input type="text" id="lname" name="lname" placeholder="Cumin"> --}}
                  <x-filament::input
                      type="text"
                      wire:model="data.last_name"
                  />
                </div>
  
                <div class="md:w-1/2 pr-4 pl-4">
                  <label class="f18 mb-1 md:mb-2" for="email">Email address</label>
                  {{-- <input type="email" id="email" name="email" placeholder="info@example.com"> --}}
                  <x-filament::input
                      type="text"
                      wire:model="data.email"
                  />
                </div>



                {{-- <div class="md:w-1/2 pr-4 pl-4">
                  <label class="f18 mb-1 md:mb-2" for="email">Profile Image</label>
                  <x-filament::input
                      type="file"
                      wire:model="data.photo_profile"
                  />
                </div> --}}



  








                <div class="md:w-1/2 pr-4 pl-4">
                  {{-- <label class="f18 mb-1 md:mb-2" for="location">Location</label>
                  <input type="text" id="location" name="location" placeholder="New york ny"> --}}
                </div>
                {{-- <div class="w-full">
                  <label class="f18 mb-1 md:mb-2" for="textarea">Location</label>
                  <textarea name="textarea" id="textarea" cols="30" rows="5"
                    placeholder="Say something about you......."></textarea>
                </div> --}}
                <div class="flex gap-2 gap-md-3 gap-xl-4">
                  {{-- <button class="button-2 f18" type="submit">Edit profile</button> --}}

                  <x-filament::button {{-- color="danger" --}} wire:click="save" class="button-2 f18">
                    Edit profile
                  </x-filament::button>



                  <button class="button-5 f18" type="submit">Cancel</button>
                </div>
              </div>
            {{-- </form> --}}
            </x-filament-panels::form>
          </div>
        </div>


        @include('blog::livewire.profile.v1.profile_stats')

        {{-- <div class="w-full">
          <div class="leaderboardtable block w-full overflow-auto scrolling-touch">
            <h4 class="mb-3 md:mb-6 leaderboardtable__heading-title wow fadeInDown">Recent activity</h4>
            <table class="w-full max-w-full mb-4 bg-transparent wow fadeInUp">
              <thead>
                <tr>
                  <th class="text-center lead-event">Event</th>
                  <th class="text-center lead-market">Market</th>
                  <th class="text-center">Outcome</th>
                  <th class="text-center lead-price">Price</th>
                  <th class="text-center lead-share">Shares</th>
                  <th class="text-center">Value</th>
                  <th class="text-center">Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable1.png" alt="Image">
                    <span class="table-image-text">Will Donald Trump be President of the USA on July 31,
                      2023?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">15,000</td>
                  <td class="text-center" data-column="Twitter">
                    $15,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    10 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable2.png" alt="Image">
                    <span class="table-image-text">Will Donald Trump be President of the USA on July 31,
                      2023?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">12,000</td>
                  <td class="text-center" data-column="Twitter">
                    $15,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    8 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable2.png" alt="Image">
                    <span class="table-image-text">Will Donald Trump be President of the USA on July 31,
                      2023?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">700</td>
                  <td class="text-center" data-column="Twitter">
                    $15,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    11 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable3.png" alt="Image">
                    <span class="table-image-text">Which party will win the 2024 United States
                      presidential election?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">15,000</td>
                  <td class="text-center" data-column="Twitter">
                    $15,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    10 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable2.png" alt="Image">
                    <span class="table-image-text">Will Donald Trump be President of the USA on July 31,
                      2023?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">1700</td>
                  <td class="text-center" data-column="Twitter">
                    $15,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    19 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
                <tr>
                  <td class="text-center" data-column="First Name">Buy</td>
                  <td class="text-center text-lg-start flex items-center" data-column="Last Name">
                    <img class="table-image me-2" src="assets/images/icon/boradtable2.png" alt="Image">
                    <span class="table-image-text">Will CZ be arrested by July 31?</span>
                  </td>
                  <td class="text-center" data-column="Job Title">
                    <span class="table-button f14">No</span>
                  </td>
                  <td class="text-center" data-column="Twitter">100¢</td>
                  <td class="text-center" data-column="Twitter">200</td>
                  <td class="text-center" data-column="Twitter">
                    $18,594.30
                  </td>
                  <td class="text-center gap-2" data-column="Twitter">
                    3 days ago
                    <img class="mpoint ms-2" src="assets/images/icon/share_shape.png" alt="Icon">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> --}}
      </div>
      <!-- Profile heading name photo end-->
    </div>
  </section>