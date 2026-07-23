import focus from "@alpinejs/focus";
Alpine.plugin(focus);

function formatDate(
	dateString,
	options = {
		year: "numeric",
		month: "short",
		day: "numeric",
	}
) {
	if (typeof dateString !== "string") {
		return `'${dateString}' is not a string!`;
	}
	const date = new Date(dateString);

	if (isNaN(date.getTime())) {
		return "Invalid date";
	}

	return new Intl.DateTimeFormat("en-US", options).format(date);
}

function formatCurrency(number, locales = "en-US", options = {}) {
	const formatter = new Intl.NumberFormat(locales, options);

	return formatter.format(number);
}

function remainingTime(dateString) {
	if (typeof dateString !== "string") {
		return `'${dateString}' is not a string!`;
	}

	const targetDate = new Date(dateString);

	if (isNaN(targetDate.getTime())) {
		return "Invalid date";
	}
	const now = new Date();

	const differenceMs = Math.abs(targetDate - now);

	const hours = Math.floor(differenceMs / (1000 * 60 * 60));
	const minutes = Math.floor((differenceMs % (1000 * 60 * 60)) / (1000 * 60));

	const formatter = new Intl.DateTimeFormat("en", {
		hour: "numeric",
		minute: "numeric",
	});

	const formattedDifference = formatter.format(new Date(0, 0, 0, hours, minutes));

	return formattedDifference;
}

document.addEventListener("alpine:init", () => {
	Alpine.store("screenWidth", window.innerWidth);
	Alpine.store("scrollingup", false);

	let lastScrollTop = 0;

	window.addEventListener("scroll", function () {
		let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

		Alpine.store("scrollingup", scrollTop > lastScrollTop);

		lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For mobile or negative scrolling
	});

	/**
	 *  Detect screen size change
	 */
	new ResizeObserver((entries) => {
		Alpine.store("screenWidth", entries[0].contentRect.width);
	}).observe(document.body);

	Alpine.data("playmarkets", () => ({
		currentFilter: "All",
		filters: ["All", "Reserved Only"],
		markets: getMarkets(),
		postMarkets: getPostMarkets(),
		get isOneCol() {
			return this.$store.screenWidth < 768;
		},
		get oddMarkets() {
			return this.markets.filter((_, i) => i % 2 === 0);
		},
		get evenMarkets() {
			return this.markets.filter((_, i) => i % 2 !== 0);
		},
	}));

	Alpine.data("searchbar", () => ({
		search: "",

		markets: getMarkets(),

		get categories() {
			return this.markets.flatMap((m) => m.category).filter((c) => c.title.toLowerCase().includes(this.search.toLowerCase()));
		},
		get tags() {
			return this.markets.flatMap((m) => m.tags).filter((t) => t.name.toLowerCase().includes(this.search.toLowerCase()));
		},

		get filteredMarkets() {
			return this.markets.filter((i) => i.title.toLowerCase().includes(this.search.toLowerCase()));
		},
	}));

	Alpine.data("heroslider", () => ({
		init() {
			this.swiper = new Swiper(this.$refs.swiper, {
				slidesPerView: 1,
				loop: true,
				autoplay: {
					delay: 5000,
				},
				pagination: {
					el: ".swiper-pagination",
					type: "bullets",
				},
			});
		},
		slides: getBanners(),
		swiper: null,
	}));
});

function getBanners() {
	return [];
}

function getMarkets() {
	return [];
}
