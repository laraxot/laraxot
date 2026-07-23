<div class="hidden lg:block">
	<button id="markets-dropdown-button" data-dropdown-toggle="markets-dropdown"
		class="flex flex-col items-center p-2 capitalize transition-all ease-out bg-transparent rounded-lg hover:bg-white text-neutral-3 focus:bg-white">
		<x-heroicon-o-globe-europe-africa class="w-6 h-6"/>
		<span class="flex items-center gap-1 navlink-text">
			<span>Markets</span>
            <x-fas-chevron-down class="h-3" />
		</span>
	</button>

	<div id="markets-dropdown"
		class="absolute z-10 hidden p-10 overflow-hidden text-sm bg-white border border-gray-100 rounded-lg shadow-md">
		<ul class="grid grid-cols-[repeat(5,180px)] gap-6">

            @foreach($_theme->categories() as $category)
                <li class="flex flex-col gap-6">
                    <a href="#category/123/business-finance">
                        <div class="flex items-center gap-2">
                            {{-- <span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
                                <svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.0048 2.00299C15.8348 2.00212 17.6098 2.62866 19.0337 3.77812C20.4576 4.92758 21.4444 6.53047 21.8295 8.31946C22.2146 10.1085 21.9747 11.9754 21.1499 13.6089C20.325 15.2425 18.9651 16.5438 17.2968 17.296C16.7636 18.4749 15.9524 19.5069 14.9327 20.3034C13.9131 21.1 12.7154 21.6373 11.4425 21.8693C10.1696 22.1014 8.85934 22.0212 7.62421 21.6357C6.38908 21.2502 5.26588 20.5708 4.35096 19.6559C3.43603 18.741 2.75668 17.6178 2.37116 16.3826C1.98564 15.1475 1.90547 13.8373 2.13751 12.5644C2.36955 11.2914 2.90688 10.0938 3.70342 9.07413C4.49996 8.05447 5.53194 7.24323 6.71085 6.70999C7.34499 5.30688 8.37043 4.11649 9.66419 3.2816C10.9579 2.4467 12.4651 2.00275 14.0048 2.00299ZM10.0048 8.00299C8.41355 8.00299 6.88742 8.63513 5.76221 9.76035C4.63699 10.8856 4.00485 12.4117 4.00485 14.003C4.00485 15.5943 4.63699 17.1204 5.76221 18.2456C6.88742 19.3709 8.41355 20.003 10.0048 20.003C11.5961 20.003 13.1223 19.3709 14.2475 18.2456C15.3727 17.1204 16.0048 15.5943 16.0048 14.003C16.0048 12.4117 15.3727 10.8856 14.2475 9.76035C13.1223 8.63513 11.5961 8.00299 10.0048 8.00299ZM11.0048 9.00299V10.003H13.0048V12.003H9.00485C8.8799 12.0028 8.7594 12.0493 8.66706 12.1335C8.57473 12.2177 8.51726 12.3334 8.50597 12.4578C8.49468 12.5822 8.53039 12.7064 8.60607 12.8058C8.68174 12.9052 8.7919 12.9727 8.91485 12.995L9.00485 13.003H11.0048C11.6679 13.003 12.3038 13.2664 12.7726 13.7352C13.2415 14.2041 13.5048 14.84 13.5048 15.503C13.5048 16.166 13.2415 16.8019 12.7726 17.2708C12.3038 17.7396 11.6679 18.003 11.0048 18.003V19.003H9.00485V18.003H7.00485V16.003H11.0048C11.1298 16.0032 11.2503 15.9567 11.3426 15.8725C11.435 15.7883 11.4924 15.6726 11.5037 15.5482C11.515 15.4237 11.4793 15.2996 11.4036 15.2002C11.328 15.1007 11.2178 15.0333 11.0948 15.011L11.0048 15.003H9.00485C8.34181 15.003 7.70592 14.7396 7.23708 14.2708C6.76824 13.8019 6.50485 13.166 6.50485 12.503C6.50485 11.84 6.76824 11.2041 7.23708 10.7352C7.70592 10.2664 8.34181 10.003 9.00485 10.003V9.00299H11.0048ZM14.0048 4.00299C13.1574 4.00186 12.3193 4.18072 11.5461 4.52775C10.7729 4.87477 10.0823 5.38204 9.51985 6.01599C10.6501 5.94738 11.7821 6.11949 12.8409 6.52094C13.8996 6.92239 14.8612 7.54404 15.6618 8.34477C16.4624 9.14551 17.084 10.1071 17.4853 11.1659C17.8866 12.2248 18.0586 13.3567 17.9898 14.487C18.8991 13.6786 19.541 12.6129 19.8307 11.4313C20.1203 10.2497 20.0438 9.00791 19.6115 7.87071C19.1791 6.73352 18.4113 5.75463 17.4098 5.06387C16.4083 4.37312 15.2205 4.00313 14.0038 4.00299H14.0048Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span> --}}
                            <span class="max-w-[100px] font-semibold text-base leading-[14px]">{{ $category->title }}</span>
                        </div>
                    </a>
                    @foreach($category->children as $child)
                        <a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/2129/big-companies">
                            {{ $child->title }}
                        </a>
                    @endforeach
                    {{-- <a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/2129/big-companies">
                    Big Companies </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
                        href="#category/2669/crypto">
                    Crypto </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
                        href="#category/1308/economic-indicators">
                    Economic Indicators </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
                        href="#category/1040/stock-market-indices">
                    Stock market indices </a><a class="text-sm font-semibold text-blue-1 hover:text-blue-3"
                        href="#category/123/business-finance">View More</a> --}}
                </li>
            @endforeach


			{{-- <!-- business -->
			<li class="flex flex-col gap-6">
				<a href="#category/123/business-finance">
					<div class="flex items-center gap-2">
						<span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
							<svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M14.0048 2.00299C15.8348 2.00212 17.6098 2.62866 19.0337 3.77812C20.4576 4.92758 21.4444 6.53047 21.8295 8.31946C22.2146 10.1085 21.9747 11.9754 21.1499 13.6089C20.325 15.2425 18.9651 16.5438 17.2968 17.296C16.7636 18.4749 15.9524 19.5069 14.9327 20.3034C13.9131 21.1 12.7154 21.6373 11.4425 21.8693C10.1696 22.1014 8.85934 22.0212 7.62421 21.6357C6.38908 21.2502 5.26588 20.5708 4.35096 19.6559C3.43603 18.741 2.75668 17.6178 2.37116 16.3826C1.98564 15.1475 1.90547 13.8373 2.13751 12.5644C2.36955 11.2914 2.90688 10.0938 3.70342 9.07413C4.49996 8.05447 5.53194 7.24323 6.71085 6.70999C7.34499 5.30688 8.37043 4.11649 9.66419 3.2816C10.9579 2.4467 12.4651 2.00275 14.0048 2.00299ZM10.0048 8.00299C8.41355 8.00299 6.88742 8.63513 5.76221 9.76035C4.63699 10.8856 4.00485 12.4117 4.00485 14.003C4.00485 15.5943 4.63699 17.1204 5.76221 18.2456C6.88742 19.3709 8.41355 20.003 10.0048 20.003C11.5961 20.003 13.1223 19.3709 14.2475 18.2456C15.3727 17.1204 16.0048 15.5943 16.0048 14.003C16.0048 12.4117 15.3727 10.8856 14.2475 9.76035C13.1223 8.63513 11.5961 8.00299 10.0048 8.00299ZM11.0048 9.00299V10.003H13.0048V12.003H9.00485C8.8799 12.0028 8.7594 12.0493 8.66706 12.1335C8.57473 12.2177 8.51726 12.3334 8.50597 12.4578C8.49468 12.5822 8.53039 12.7064 8.60607 12.8058C8.68174 12.9052 8.7919 12.9727 8.91485 12.995L9.00485 13.003H11.0048C11.6679 13.003 12.3038 13.2664 12.7726 13.7352C13.2415 14.2041 13.5048 14.84 13.5048 15.503C13.5048 16.166 13.2415 16.8019 12.7726 17.2708C12.3038 17.7396 11.6679 18.003 11.0048 18.003V19.003H9.00485V18.003H7.00485V16.003H11.0048C11.1298 16.0032 11.2503 15.9567 11.3426 15.8725C11.435 15.7883 11.4924 15.6726 11.5037 15.5482C11.515 15.4237 11.4793 15.2996 11.4036 15.2002C11.328 15.1007 11.2178 15.0333 11.0948 15.011L11.0048 15.003H9.00485C8.34181 15.003 7.70592 14.7396 7.23708 14.2708C6.76824 13.8019 6.50485 13.166 6.50485 12.503C6.50485 11.84 6.76824 11.2041 7.23708 10.7352C7.70592 10.2664 8.34181 10.003 9.00485 10.003V9.00299H11.0048ZM14.0048 4.00299C13.1574 4.00186 12.3193 4.18072 11.5461 4.52775C10.7729 4.87477 10.0823 5.38204 9.51985 6.01599C10.6501 5.94738 11.7821 6.11949 12.8409 6.52094C13.8996 6.92239 14.8612 7.54404 15.6618 8.34477C16.4624 9.14551 17.084 10.1071 17.4853 11.1659C17.8866 12.2248 18.0586 13.3567 17.9898 14.487C18.8991 13.6786 19.541 12.6129 19.8307 11.4313C20.1203 10.2497 20.0438 9.00791 19.6115 7.87071C19.1791 6.73352 18.4113 5.75463 17.4098 5.06387C16.4083 4.37312 15.2205 4.00313 14.0038 4.00299H14.0048Z"
									fill="currentColor"></path>
							</svg>
						</span>
						<span class="max-w-[100px] font-semibold text-base leading-[14px]">Business &amp; Finance</span>
					</div>
				</a>
				<a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/2129/big-companies">
				Big Companies </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/2669/crypto">
				Crypto </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/1308/economic-indicators">
				Economic Indicators </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/1040/stock-market-indices">
				Stock market indices </a><a class="text-sm font-semibold text-blue-1 hover:text-blue-3"
					href="#category/123/business-finance">View More</a>
			</li>
			<!-- entertainment -->
			<li class="flex flex-col gap-6">
				<a href="#category/555/entertainment">
					<div class="flex items-center gap-2">
						<span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
							<svg
								width="20px" viewBox="0 0 20 18" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M18 2H14.83L13 0H7L5.17 2H2C1.46957 2 0.960859 2.21071 0.585786 2.58579C0.210714 2.96086 0 3.46957 0 4V16C0 16.5304 0.210714 17.0391 0.585786 17.4142C0.960859 17.7893 1.46957 18 2 18H18C18.5304 18 19.0391 17.7893 19.4142 17.4142C19.7893 17.0391 20 16.5304 20 16V4C20 3.46957 19.7893 2.96086 19.4142 2.58579C19.0391 2.21071 18.5304 2 18 2ZM18 16H2V4H6.05L7.88 2H12.12L13.95 4H18V16ZM10 5C8.67392 5 7.40215 5.52678 6.46447 6.46447C5.52678 7.40215 5 8.67392 5 10C5 11.3261 5.52678 12.5979 6.46447 13.5355C7.40215 14.4732 8.67392 15 10 15C11.3261 15 12.5979 14.4732 13.5355 13.5355C14.4732 12.5979 15 11.3261 15 10C15 8.67392 14.4732 7.40215 13.5355 6.46447C12.5979 5.52678 11.3261 5 10 5ZM10 13C9.20435 13 8.44129 12.6839 7.87868 12.1213C7.31607 11.5587 7 10.7956 7 10C7 9.20435 7.31607 8.44129 7.87868 7.87868C8.44129 7.31607 9.20435 7 10 7C10.7956 7 11.5587 7.31607 12.1213 7.87868C12.6839 8.44129 13 9.20435 13 10C13 10.7956 12.6839 11.5587 12.1213 12.1213C11.5587 12.6839 10.7956 13 10 13Z"
									fill="currentColor"></path>
							</svg>
						</span>
						<span class="max-w-[100px] font-semibold text-base leading-[14px]">Entertainment</span>
					</div>
				</a>
				<a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/1500/awards">Awards</a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/1060/literature">Literature</a><a
					class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/581/movies">Movies</a><a
					class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/582/series">Series</a><a
					class="text-sm font-semibold text-blue-1 hover:text-blue-3" href="#category/555/entertainment">View
				More</a>
			</li>
			<!-- politics -->
			<li class="flex flex-col gap-6">
				<a href="#category/98/politics">
					<div class="flex items-center gap-2">
						<span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
							<svg
								width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M2 20H22V22H2V20ZM4 12H6V19H4V12ZM9 12H11V19H9V12ZM13 12H15V19H13V12ZM18 12H20V19H18V12ZM2 7L12 2L22 7V11H2V7ZM4 8.236V9H20V8.236L12 4.236L4 8.236ZM12 8C11.7348 8 11.4804 7.89464 11.2929 7.70711C11.1054 7.51957 11 7.26522 11 7C11 6.73478 11.1054 6.48043 11.2929 6.29289C11.4804 6.10536 11.7348 6 12 6C12.2652 6 12.5196 6.10536 12.7071 6.29289C12.8946 6.48043 13 6.73478 13 7C13 7.26522 12.8946 7.51957 12.7071 7.70711C12.5196 7.89464 12.2652 8 12 8Z"
									fill="currentColor"></path>
							</svg>
						</span>
						<span class="max-w-[100px] font-semibold text-base leading-[14px]">Politics</span>
					</div>
				</a>
				<a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/106/brazil">
				Brazil </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/104/europe">
				Europe </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/99/usa">
				USA </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/103/world-politics">
				World Politics </a><a class="text-sm font-semibold text-blue-1 hover:text-blue-3"
					href="#category/98/politics">View More</a>
			</li>
			<!-- science -->
			<li class="flex flex-col gap-6">
				<a href="#category/101/science">
					<div class="flex items-center gap-2">
						<span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
							<svg
								width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M16 2V4H15V7.243C15 8.40042 15.2511 9.54406 15.736 10.595L20.017 19.871C20.1226 20.0996 20.1689 20.351 20.1518 20.6022C20.1346 20.8534 20.0545 21.0963 19.9188 21.3083C19.7831 21.5204 19.5962 21.695 19.3754 21.8158C19.1545 21.9367 18.9068 22 18.655 22H5.344C5.09222 22 4.84449 21.9367 4.62362 21.8158C4.40276 21.695 4.21587 21.5204 4.08019 21.3083C3.94451 21.0963 3.8644 20.8534 3.84724 20.6022C3.83008 20.351 3.87642 20.0996 3.982 19.871L8.263 10.595C8.74827 9.54414 8.99972 8.40049 9 7.243V4H8V2H16ZM13.387 10.001H10.611C10.5067 10.3648 10.3819 10.7224 10.237 11.072L10.079 11.434L6.124 20H17.874L13.92 11.434C13.7059 10.9703 13.5278 10.4909 13.387 10V10.001ZM11 7.243C11 7.496 10.99 7.749 10.972 8.001H13.029C13.02 7.8798 13.0134 7.75845 13.009 7.637L13 7.243V4H11V7.243Z"
									fill="currentColor"></path>
							</svg>
						</span>
						<span class="max-w-[100px] font-semibold text-base leading-[14px]">Science</span>
					</div>
				</a>
				<a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/2077/bio-engineering">Bioengineering</a><a
					class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/2041/Environmental-indicators">Environmental Indicators</a><a
					class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/2028/space-exploration">Space Exploration</a><a
					class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1"
					href="#category/1269/transmissible-diseases">Transmissible Diseases</a><a
					class="text-sm font-semibold text-blue-1 hover:text-blue-3" href="#category/101/science">View More</a>
			</li>
			<!-- sports -->
			<li class="flex flex-col gap-6">
				<a href="#category/7/sports">
					<div class="flex items-center gap-2">
						<span class="flex items-center justify-center rounded-lg size-10 text-neutral-5 bg-blue-2">
							<svg
								width="20px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M9 0.666626C13.6025 0.666626 17.3333 4.39746 17.3333 8.99996C17.3333 13.6025 13.6025 17.3333 9 17.3333C4.3975 17.3333 0.666667 13.6025 0.666667 8.99996C0.666667 4.39746 4.3975 0.666626 9 0.666626ZM10.3917 12.3333H7.60833L6.45833 13.9141L6.92 15.3358C7.5914 15.5557 8.29351 15.6674 9 15.6666C9.72583 15.6666 10.425 15.55 11.08 15.3358L11.5408 13.9141L10.3908 12.3333H10.3917ZM3.41167 8.05996L2.335 8.84079L2.33333 8.99996C2.33333 10.4416 2.79083 11.7758 3.56833 12.8666H5.16L6.2625 11.35L5.40583 8.70829L3.41167 8.05996ZM14.5883 8.05996L12.5942 8.70829L11.7375 11.35L12.8392 12.8666H14.4308C15.2365 11.7384 15.6687 10.3863 15.6667 8.99996L15.6642 8.84079L14.5883 8.05996ZM10.9083 2.61079L9.83333 3.39413V5.49163L12.0783 7.12246L13.9442 6.51663L14.4058 5.09746C13.5455 3.90652 12.3165 3.0325 10.9092 2.61079H10.9083ZM7.09 2.61079C5.68254 3.03272 4.45351 3.90704 3.59333 5.09829L4.055 6.51663L5.92083 7.12246L8.16667 5.49163V3.39413L7.09083 2.61079H7.09Z"
									fill="currentColor"></path>
							</svg>
						</span>
						<span class="max-w-[100px] font-semibold text-base leading-[14px]">Sports</span>
					</div>
				</a>
				<a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/8/football">
				Football </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/552/nba">
				NBA </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/585/ncaab">
				NCAAB </a><a class="p-2 -ml-2 rounded text-neutral-5 hover:bg-neutral-1" href="#category/620/nhl">
				NHL </a><a class="text-sm font-semibold text-blue-1 hover:text-blue-3" href="#category/7/sports">View
				More</a>
			</li> --}}
		</ul>
	</div>
</div>
