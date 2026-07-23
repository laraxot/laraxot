<div
	x-data="{loggedIn:true}"
	class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12 font-roboto"
	>
	<div class="py-10">
		<h1 class="text-[2rem] mb-4 font-roboto font-semibold text-neutral-5">
			{{ $page->title }}
		</h1>
		{{-- <div class="flex items-center gap-2.5">
			<p class="text-base font-roboto text-neutral-5">
				Our ranking consider the profit and loss only
			</p>
			<div class="flex items-center gap-2.5">
				<div
					x-data="{
					open: false,
					toggle() {
					if (this.open) {
					return this.close()
					}
					this.$refs.button.focus()
					this.open = true
					},
					close(focusAfter) {
					if (! this.open) return
					this.open = false
					focusAfter && focusAfter.focus()
					}
					,
					options:['This Month','Last Month','All Time'],
					selected:'All Time'
					}"
					x-on:keydown.escape.prevent.stop="close($refs.button)"
					x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
					x-id="['dropdown-button']"
					class="relative"
					>
					<button
						x-ref="button"
						x-on:click="toggle()"
						:aria-expanded="open"
						:aria-controls="$id('dropdown-button')"
						type="button"
						class="flex items-center gap-2 font-bold text-sm text-neutral-5 rounded-lg transition-colors duration-200 [&>svg]:aria-expanded:rotate-180"
						>
						<span x-text="selected"></span>
						<svg
							class="transition-transform duration-200"
							width="11px"
							height="7px"
							fillcolor="currentColor"
							viewBox="0 0 12 8"
							xmlns="http://www.w3.org/2000/svg"
							>
							<path
								d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
								fill="currentColor"
								></path>
						</svg>
					</button>
					<div
						x-ref="panel"
						x-show="open"
						x-transition.origin.top.left
						x-on:click.outside="close($refs.button)"
						:id="$id('dropdown-button')"
						style="display: none"
						class="absolute right-0 mt-2.5 rounded-md bg-white shadow-md min-w-[270px]"
						>
						<template x-for="option in options">
							<button
								@click="selected = option; close($refs.button)"
								type="button"
								class="flex items-center justify-between gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md p-4 text-left text-sm hover:bg-blue-7 text-[#212121] transition-colors duration-75 ease-out whitespace-nowrap font-medium hover:text-blue-6"
								>
								<span x-text="option"></span>
								<svg
									x-show="selected === option"
									width="18"
									height="18"
									viewBox="0 0 18 18"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
									>
									<path
										d="M9 17.3333C4.3975 17.3333 0.666667 13.6025 0.666667 8.99996C0.666667 4.39746 4.3975 0.666626 9 0.666626C13.6025 0.666626 17.3333 4.39746 17.3333 8.99996C17.3333 13.6025 13.6025 17.3333 9 17.3333ZM9 15.6666C10.7681 15.6666 12.4638 14.9642 13.714 13.714C14.9643 12.4638 15.6667 10.7681 15.6667 8.99996C15.6667 7.23185 14.9643 5.53616 13.714 4.28591C12.4638 3.03567 10.7681 2.33329 9 2.33329C7.23189 2.33329 5.5362 3.03567 4.28595 4.28591C3.03571 5.53616 2.33333 7.23185 2.33333 8.99996C2.33333 10.7681 3.03571 12.4638 4.28595 13.714C5.5362 14.9642 7.23189 15.6666 9 15.6666ZM8.16917 12.3333L4.63333 8.79746L5.81167 7.61913L8.16917 9.97663L12.8825 5.26246L14.0617 6.44079L8.16917 12.3333Z"
										fill="#1591ED"
										></path>
								</svg>
							</button>
						</template>
					</div>
				</div>
				<span>in</span>
				<div
					x-data="{
					open: false,
					toggle() {
					if (this.open) {
					return this.close()
					}
					this.$refs.button.focus()
					this.open = true
					},
					close(focusAfter) {
					if (! this.open) return
					this.open = false
					focusAfter && focusAfter.focus()
					}
					,
					options:['All','Business & Finance','Entertainment','Politics','Science','Sports'],
					selected:'All'
					}"
					x-on:keydown.escape.prevent.stop="close($refs.button)"
					x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
					x-id="['dropdown-button']"
					class="relative"
					>
					<button
						x-ref="button"
						x-on:click="toggle()"
						:aria-expanded="open"
						:aria-controls="$id('dropdown-button')"
						type="button"
						class="flex items-center gap-2 font-bold text-sm text-neutral-5 rounded-lg transition-colors duration-200 [&>svg]:aria-expanded:rotate-180"
						>
						<span x-text="selected"></span>
						<svg
							class="transition-transform duration-200"
							width="11px"
							height="7px"
							fillcolor="currentColor"
							viewBox="0 0 12 8"
							xmlns="http://www.w3.org/2000/svg"
							>
							<path
								d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
								fill="currentColor"
								></path>
						</svg>
					</button>
					<div
						x-ref="panel"
						x-show="open"
						x-transition.origin.top.left
						x-on:click.outside="close($refs.button)"
						:id="$id('dropdown-button')"
						style="display: none"
						class="absolute right-0 mt-2.5 rounded-md bg-white shadow-md min-w-[270px]"
						>
						<template x-for="option in options">
							<button
								@click="selected = option; close($refs.button)"
								type="button"
								class="flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md p-4 text-left text-sm hover:bg-blue-6 text-[#212121] transition-colors duration-75 ease-out whitespace-nowrap font-medium hover:text-blue-7"
								>
								<span x-text="option"></span>
								<svg
									x-show="selected === option"
									width="18"
									height="18"
									viewBox="0 0 18 18"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
									>
									<path
										d="M9 17.3333C4.3975 17.3333 0.666667 13.6025 0.666667 8.99996C0.666667 4.39746 4.3975 0.666626 9 0.666626C13.6025 0.666626 17.3333 4.39746 17.3333 8.99996C17.3333 13.6025 13.6025 17.3333 9 17.3333ZM9 15.6666C10.7681 15.6666 12.4638 14.9642 13.714 13.714C14.9643 12.4638 15.6667 10.7681 15.6667 8.99996C15.6667 7.23185 14.9643 5.53616 13.714 4.28591C12.4638 3.03567 10.7681 2.33329 9 2.33329C7.23189 2.33329 5.5362 3.03567 4.28595 4.28591C3.03571 5.53616 2.33333 7.23185 2.33333 8.99996C2.33333 10.7681 3.03571 12.4638 4.28595 13.714C5.5362 14.9642 7.23189 15.6666 9 15.6666ZM8.16917 12.3333L4.63333 8.79746L5.81167 7.61913L8.16917 9.97663L12.8825 5.26246L14.0617 6.44079L8.16917 12.3333Z"
										fill="#1591ED"
										></path>
								</svg>
							</button>
						</template>
					</div>
				</div>
			</div>
		</div> --}}
	</div>
	<div class="grid grid-cols-1 lg:grid-cols-[21.25rem,1fr] gap-4">
		<div class="space-y-6">
			<div>
				<div
					x-show="loggedIn"
					x-cloak
					class="bg-white rounded-2xl text-sm space-y-10 p-6"
					>
					<div class="flex items-center gap-4">
						<div
							class="size-[75px] bg-neutral-2 flex items-center justify-center rounded-full text-2xl text-white"
							>
							U
						</div>
						<p class="text-base font-semibold text-neutral-5">username</p>
					</div>
					<div class="space-y-6">
						<div class="flex justify-between items-center">
							<p class="text-neutral-3 text-sm font-medium font-roboto">
								Forecaster rank
							</p>
							<span class="text-sm text-neutral-4 font-semibold">#</span>
						</div>
						<div class="flex justify-between items-center">
							<p class="text-neutral-3 text-sm font-medium font-roboto">
								Lifetime profit
							</p>
							<p class="text-sm">
								<span class="text-neutral-4 font-semibold">0.00</span>
								<span class="text-neutral-3 font-medium">Ooms</span>
							</p>
						</div>
					</div>
					<div class="text-center">
						<a href="#" class="text-sm font-semibold text-blue-6"
							>Go to my profile</a
							>
					</div>
				</div>
				<div
					x-show="!loggedIn"
					x-cloak
					class="bg-white rounded-2xl text-sm space-y-10 p-6"
					>
					<p class="text-base font-semibold">
						Sign up or login to participate
					</p>
					<div class="flex gap-4">
						<button
							type="button"
							class="border border-blue-6 font-semibold hover:bg-blue-6 hover:text-white text-blue-6 focus:ring-4 focus:outline-none focus:ring-blue rounded-lg text-sm px-10 h-10 flex items-center justify-center text-center"
							>
						Sign Up
						</button>
						<button
							type="button"
							class="bg-blue-6 text-white hover:bg-[#061989] focus:ring-4 focus:outline-none focus:ring-blue font-semibold rounded-lg text-sm px-10 h-10 flex items-center justify-center text-center"
							>
						Login
						</button>
					</div>
				</div>
			</div>
			<!-- hot markets -->

			<x-page side="sidebar" :slug="$page->slug" />

			{{-- <div class="space-y-4">
				<h2 class="font-semibold text-2xl text-neutral-5">Hot markets</h2>
				<div class="space-y-4">
					<!-- Market 4: Will Joe Biden finish his presidential term? -->
					<article class="bg-white hover:bg-[#c2ced1] rounded-lg p-4">
						<a
							href="#will-joe-biden-finish-his-presidential-term"
							class="text-neutral-5 text-base font-semibold"
							>
						Will Joe Biden finish his presidential term?
						</a>
						<div class="mt-2.5 flex items-center gap-4">
							<div class="flex items-center gap-1">
								<svg
									version="1.1"
									xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 24 24"
									class="question-card__person-icon"
									fill="#292929"
									width="13px"
									>
									<path
										fill-rule="evenodd"
										d="M11.5 4C9.02 4 7 6.02 7 8.5S9.02 13 11.5 13 16 10.98 16 8.5 13.98 4 11.5 4M8.744 14.386A6.51 6.51 0 0 1 5 8.5C5 4.916 7.916 2 11.5 2S18 4.916 18 8.5a6.51 6.51 0 0 1-3.744 5.886C18.156 15.508 21 18.946 21 23h-2c0-3.97-3.309-7-7.5-7S4 19.03 4 23H2c0-4.055 2.845-7.492 6.744-8.614z"
										></path>
								</svg>
								<span class="text-sm text-[#666666]">3212</span>
							</div>
							<div class="flex items-center gap-1">
								<svg
									viewBox="0 0 16 17"
									fillcolor="#292929"
									width="13px"
									xmlns="http://www.w3.org/2000/svg"
									>
									<path
										d="M0.5 9H3.83333V16.5H0.5V9ZM12.1667 5.66667H15.5V16.5H12.1667V5.66667ZM6.33333 0.666668H9.66667V16.5H6.33333V0.666668Z"
										fill="#6B6A6A"
										></path>
								</svg>
								<span class="text-sm text-[#666666]">3364798 ø</span>
							</div>
							<!-- Bet end date -->
							<div class="flex items-center gap-1">
								<span>
									<svg
										fill="none"
										width="16px"
										viewBox="0 0 14 14"
										xmlns="http://www.w3.org/2000/svg"
										>
										<path
											d="M7 13.6667C6.21207 13.6667 5.43185 13.5115 4.7039 13.2099C3.97594 12.9084 3.31451 12.4665 2.75736 11.9093C2.2002 11.3522 1.75825 10.6907 1.45672 9.96276C1.15519 9.23481 0.999997 8.45459 0.999997 7.66666C0.999997 6.87873 1.15519 6.09852 1.45672 5.37056C1.75825 4.64261 2.2002 3.98117 2.75736 3.42402C3.31451 2.86687 3.97594 2.42491 4.7039 2.12339C5.43185 1.82186 6.21207 1.66666 7 1.66666C8.5913 1.66666 10.1174 2.2988 11.2426 3.42402C12.3679 4.54924 13 6.07536 13 7.66666C13 9.25796 12.3679 10.7841 11.2426 11.9093C10.1174 13.0345 8.5913 13.6667 7 13.6667ZM7 12.3333C7.61283 12.3333 8.21967 12.2126 8.78585 11.9781C9.35204 11.7436 9.86649 11.3998 10.2998 10.9665C10.7332 10.5332 11.0769 10.0187 11.3114 9.45252C11.546 8.88633 11.6667 8.2795 11.6667 7.66666C11.6667 7.05383 11.546 6.44699 11.3114 5.88081C11.0769 5.31462 10.7332 4.80017 10.2998 4.36683C9.86649 3.93349 9.35204 3.58975 8.78585 3.35522C8.21967 3.1207 7.61283 3 7 3C5.76232 3 4.57534 3.49166 3.70017 4.36683C2.825 5.242 2.33333 6.42899 2.33333 7.66666C2.33333 8.90434 2.825 10.0913 3.70017 10.9665C4.57534 11.8417 5.76232 12.3333 7 12.3333ZM7.66666 7.66666H9.66666V9H6.33333V4.33333H7.66666V7.66666ZM0.16333 3.18866L2.52066 0.831329L3.46333 1.774L1.10666 4.13133L0.163997 3.18866H0.16333ZM11.4767 0.831329L13.834 3.18866L12.8913 4.13133L10.534 1.774L11.4767 0.831329Z"
											fill="#292929"
											></path>
									</svg>
								</span>
								<span class="text-sm text-[#666666]">2025-01-20</span>
							</div>
						</div>
					</article>
					<!-- Market 4: Will Joe Biden finish his presidential term? -->
					<article class="bg-white hover:bg-[#c2ced1] rounded-lg p-4">
						<a
							href="#will-joe-biden-finish-his-presidential-term"
							class="text-neutral-5 text-base font-semibold"
							>
						Will Joe Biden finish his presidential term?
						</a>
						<div class="mt-2.5 flex items-center gap-4">
							<div class="flex items-center gap-1">
								<svg
									version="1.1"
									xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 24 24"
									class="question-card__person-icon"
									fill="#292929"
									width="13px"
									>
									<path
										fill-rule="evenodd"
										d="M11.5 4C9.02 4 7 6.02 7 8.5S9.02 13 11.5 13 16 10.98 16 8.5 13.98 4 11.5 4M8.744 14.386A6.51 6.51 0 0 1 5 8.5C5 4.916 7.916 2 11.5 2S18 4.916 18 8.5a6.51 6.51 0 0 1-3.744 5.886C18.156 15.508 21 18.946 21 23h-2c0-3.97-3.309-7-7.5-7S4 19.03 4 23H2c0-4.055 2.845-7.492 6.744-8.614z"
										></path>
								</svg>
								<span class="text-sm text-[#666666]">3212</span>
							</div>
							<div class="flex items-center gap-1">
								<svg
									viewBox="0 0 16 17"
									fillcolor="#292929"
									width="13px"
									xmlns="http://www.w3.org/2000/svg"
									>
									<path
										d="M0.5 9H3.83333V16.5H0.5V9ZM12.1667 5.66667H15.5V16.5H12.1667V5.66667ZM6.33333 0.666668H9.66667V16.5H6.33333V0.666668Z"
										fill="#6B6A6A"
										></path>
								</svg>
								<span class="text-sm text-[#666666]">3364798 ø</span>
							</div>
							<!-- Bet end date -->
							<div class="flex items-center gap-1">
								<span>
									<svg
										fill="none"
										width="16px"
										viewBox="0 0 14 14"
										xmlns="http://www.w3.org/2000/svg"
										>
										<path
											d="M7 13.6667C6.21207 13.6667 5.43185 13.5115 4.7039 13.2099C3.97594 12.9084 3.31451 12.4665 2.75736 11.9093C2.2002 11.3522 1.75825 10.6907 1.45672 9.96276C1.15519 9.23481 0.999997 8.45459 0.999997 7.66666C0.999997 6.87873 1.15519 6.09852 1.45672 5.37056C1.75825 4.64261 2.2002 3.98117 2.75736 3.42402C3.31451 2.86687 3.97594 2.42491 4.7039 2.12339C5.43185 1.82186 6.21207 1.66666 7 1.66666C8.5913 1.66666 10.1174 2.2988 11.2426 3.42402C12.3679 4.54924 13 6.07536 13 7.66666C13 9.25796 12.3679 10.7841 11.2426 11.9093C10.1174 13.0345 8.5913 13.6667 7 13.6667ZM7 12.3333C7.61283 12.3333 8.21967 12.2126 8.78585 11.9781C9.35204 11.7436 9.86649 11.3998 10.2998 10.9665C10.7332 10.5332 11.0769 10.0187 11.3114 9.45252C11.546 8.88633 11.6667 8.2795 11.6667 7.66666C11.6667 7.05383 11.546 6.44699 11.3114 5.88081C11.0769 5.31462 10.7332 4.80017 10.2998 4.36683C9.86649 3.93349 9.35204 3.58975 8.78585 3.35522C8.21967 3.1207 7.61283 3 7 3C5.76232 3 4.57534 3.49166 3.70017 4.36683C2.825 5.242 2.33333 6.42899 2.33333 7.66666C2.33333 8.90434 2.825 10.0913 3.70017 10.9665C4.57534 11.8417 5.76232 12.3333 7 12.3333ZM7.66666 7.66666H9.66666V9H6.33333V4.33333H7.66666V7.66666ZM0.16333 3.18866L2.52066 0.831329L3.46333 1.774L1.10666 4.13133L0.163997 3.18866H0.16333ZM11.4767 0.831329L13.834 3.18866L12.8913 4.13133L10.534 1.774L11.4767 0.831329Z"
											fill="#292929"
											></path>
									</svg>
								</span>
								<span class="text-sm text-[#666666]">2025-01-20</span>
							</div>
						</div>
					</article>
				</div>
			</div> --}}
		</div>


		<div
			class="max-h-[100rem] bg-white rounded-2xl p-6 overflow-auto [scrollbar-width:none]">
			<div
				x-cloak
				class="lg:mb-10 flex flex-col lg:flex-row lg:items-end lg:justify-between"
				>
				<!-- second -->
				<a
					href="#"
					class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
					>
					<div
						class="lg:contents relative flex justify-center max-lg:h-max"
						>
						<img
							src="https://My-Company-media-production.s3.amazonaws.com/cache/b1/81/b1816fa03b437898ec58f5ea571c4d4a.jpg"
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
							src="https://My-Company-media-production.s3.amazonaws.com/cache/1a/3f/1a3faf36f6b5cc0e6c26a2aeffa44e4a.jpg"
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
				</a>
			</div>
			<hr class="my-4 lg:hidden bg-transparent border-2 border-neutral-2" />
			<div class="space-y-6">
				<div class="flex gap-4 flex-wrap justify-between">



					<table class="w-full divide-y divide-gray-300">
						<tbody class="divide-y divide-gray-200 bg-white">
							<tr>
								<td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
									<div class="text-gray-900">1°</div>
								</td>
								<td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
									<div class="flex items-center">
									<div class="h-11 w-11 flex-shrink-0">
										<img class="h-11 w-11 rounded-full" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
									</div>
									<div class="ml-4">
										<div class="font-medium text-gray-900">Lindsay Walton</div>
									</div>
									</div>
								</td>
								<td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
									<span class="text-base leading-none">239748.90ø</span>
								</td>
							</tr>

							<!-- More people... -->
							<tr>
								<td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
									<div class="text-gray-900">1°</div>
								</td>
								<td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
									<div class="flex items-center">
									<div class="h-11 w-11 flex-shrink-0">
										<img class="h-11 w-11 rounded-full" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
									</div>
									<div class="ml-4">
										<div class="font-medium text-gray-900">Lindsay Walton</div>
									</div>
									</div>
								</td>
								<td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
									<span class="text-base leading-none">239748.90ø</span>
								</td>
							</tr>
						</tbody>
					</table>


















					{{-- <a
						href="#"
						class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
						>
						<div
							class="lg:contents relative flex justify-center max-lg:h-max"
							>
							<img
								src="https://My-Company-media-production.s3.amazonaws.com/cache/1a/3f/1a3faf36f6b5cc0e6c26a2aeffa44e4a.jpg"
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
					</a>
					<a
						href="#"
						class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
						>
						<div
							class="lg:contents relative flex justify-center max-lg:h-max"
							>
							<img
								src="https://My-Company-media-production.s3.amazonaws.com/cache/1a/3f/1a3faf36f6b5cc0e6c26a2aeffa44e4a.jpg"
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
					</a>
					<a
						href="#"
						class="flex max-lg:gap-4 max-lg:py-2 lg:flex-col lg:items-center text-neutral-5 hover:text-[#1e70bf]"
						>
						<div
							class="lg:contents relative flex justify-center max-lg:h-max"
							>
							<img
								src="https://My-Company-media-production.s3.amazonaws.com/cache/1a/3f/1a3faf36f6b5cc0e6c26a2aeffa44e4a.jpg"
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
				</div>
				<div class="flex justify-center">
					<button
						class="text-blue-6 hover:text-[#0f79c8] font-semibold text-base max-w-[320px] w-[90%]"
						>
					Load more
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
