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

  const formattedDifference = formatter.format(
    new Date(0, 0, 0, hours, minutes)
  );

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
    postMarkets:getPostMarkets(),
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
      return this.markets
        .flatMap((m) => m.category)
        .filter((c) =>
          c.title.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    get tags() {
      return this.markets
        .flatMap((m) => m.tags)
        .filter((t) =>
          t.name.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    get filteredMarkets() {
      return this.markets.filter((i) =>
        i.title.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  }));

  Alpine.data("heroslider", () => ({
    init() {
      this.swiper = new Swiper(this.$refs.swiper, {
        slidesPerView: 1,
        loop: true,
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
  return [
    {
      id: 40,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/7a/9c/7a9c8f672e3499d573f24901280952f3.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/0d/0c/0d0cf75bd794283b4606e85cc30f0045.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/64/3f/643f313db56c3735d15ae3eb1c27d5ad.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/14/8c/148c10ea338dfbe1bbd329e551afbfcf.webp",
      link: "https://My_Company.com/q/category/99/usa",
      title: "American Politics",
      short_description: "Congress, White House, Elections and more",
      action_text: "Make Your Forecasts",
      category: 99,
      category_dict: {
        id: 99,
        title: "USA",
        slug: "usa",
        parent: 98,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: false,
      open_markets_count: 119,
      landing_banner: false,
    },
    {
      id: 105,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/9e/91/9e9127e3501309be893f05fdf5816400.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/b4/b2/b4b2c23aa7ec70fca4f3e93a303f8b4b.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/49/4d/494deb7baf0ae08abb9e0e0aafce2cc1.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/42/ce/42ce8ed04bb6d4fcfe08be4505a9c4f6.webp",
      link: "https://My_Company.com/q/category/103/world-politics",
      title: "World Politics",
      short_description: "Who moves next in geopolitical chess?",
      action_text: "Make Your Forecasts",
      category: 103,
      category_dict: {
        id: 103,
        title: "World Politics",
        slug: "world-politics",
        parent: 98,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: true,
      open_markets_count: 71,
      landing_banner: false,
    },
    {
      id: 92,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/ef/26/ef263873938a4b0aa17553846c155dd1.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/cd/8f/cd8f40da96b9cf05c403273474589925.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/30/3e/303e14ed0c8b18dafb736af84adfd186.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/74/7c/747caf4b7f4acd2049142f2c01935ff2.webp",
      link: "https://My_Company.com/q/tag/ukraine-conflict",
      title: "Ukraine Conflict",
      short_description: "Key markets on the Russia-Ukraine conflict",
      action_text: "See Forecasts",
      category: null,
      category_dict: null,
      end_date: null,
      hot_topic: false,
      open_markets_count: 2,
      landing_banner: false,
    },
    {
      id: 102,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/20/01/2001abe271f644a5d9c2dc8efc67ca81.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/73/e3/73e35e18e7db0cafb43447c4c2ef6ae5.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/2b/9d/2b9d424c0124dd9c96a8619b16caa9d6.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/ca/c1/cac166741059bfb345b62f0df5f57716.webp",
      link: "https://My_Company.com/q/tag/artificial-intelligence",
      title: "Artificial Inteligence",
      short_description: "Regulation, innovation, and the next big thing in AI",
      action_text: "Place your bets",
      category: null,
      category_dict: null,
      end_date: null,
      hot_topic: false,
      open_markets_count: 7,
      landing_banner: false,
    },
    {
      id: 99,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/2b/7d/2b7d6883f6087a5e55386c2450359f83.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/88/a8/88a8509cb1e372c28d82e1848a23ede7.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/28/ee/28ee2f79000b55514f497503dcee2419.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/ac/0b/ac0b6fdd01d83bff07aac8e696369fd2.webp",
      link: "https://My_Company.com/q/category/2669/crypto",
      title: "Crypto",
      short_description: "Cryptocurrencies, regulation, and the future of Web3",
      action_text: "Place your bets",
      category: 2669,
      category_dict: {
        id: 2669,
        title: "Crypto",
        slug: "crypto",
        parent: 123,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: false,
      open_markets_count: 24,
      landing_banner: false,
    },
    {
      id: 17,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/96/aa/96aadc55027c274b8f90492177196c27.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/20/bb/20bb96e3e84028bb79221cf522da88e9.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/57/ea/57ea6a1534cef5d01f07e2723b6d0ef8.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/76/9f/769f28d64933bdb8d5e48602b6fa12d3.webp",
      link: "https://My_Company.com/q/category/1308/economic-indicators",
      title: "Economic Indicators",
      short_description: "Crowd-driven forecasts for key economic indicators",
      action_text: "Make your forecasts",
      category: 1308,
      category_dict: {
        id: 1308,
        title: "Economic Indicators",
        slug: "economic-indicators",
        parent: 123,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: false,
      open_markets_count: 57,
      landing_banner: false,
    },
    {
      id: 106,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/d7/04/d70430e77ab3f1b24a81b8e36175c498.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/b6/96/b696c5d6e5fe6120e4e9d33094b5da35.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/7c/3a/7c3a1baf9275df6b12fb5de78c214a1c.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/91/91/91915d4fb6bfee9efd0d2c029a77a861.webp",
      link: "https://My_Company.com/q/tag/climate-change",
      title: "Climate Change",
      short_description:
        "Predict the future of the global temperature, sea level, ice cap, and other environmental indicators",
      action_text: "Make your forecasts",
      category: null,
      category_dict: null,
      end_date: null,
      hot_topic: false,
      open_markets_count: 17,
      landing_banner: false,
    },
    {
      id: 97,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/ce/ab/ceab58ac343412aa648cab1f4e6be6b8.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/bd/6c/bd6cf0a760b498ec233ad7b0c445ba09.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/39/02/3902ab86c1340b6497463ab9fb928798.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/0b/bd/0bbd5b5fb7a3b841100502acb24b6817.webp",
      link: "https://My_Company.com/q/category/553/nfl",
      title: "NFL",
      short_description: "Predict every game in the league, week by week",
      action_text: "Place your bets",
      category: 553,
      category_dict: {
        id: 553,
        title: "NFL",
        slug: "nfl",
        parent: 7,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: true,
      open_markets_count: 0,
      landing_banner: false,
    },
    {
      id: 80,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/55/02/55021135955da3c3eb0a3be3f9a3ac4a.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/87/ff/87ff3e22f1bd542f88995a5e9e505767.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/1d/6f/1d6f7708b8f8f1bbfb83ab274300545c.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/6f/e1/6fe13ae83c0efbd89b20e024bb73d72e.webp",
      link: "https://My_Company.com/q/category/106/brazil",
      title: "Brazilian Politics",
      short_description: "Congress, Presidency, Investigations, and more",
      action_text: "Place Your Bets",
      category: 106,
      category_dict: {
        id: 106,
        title: "Brazil",
        slug: "brazil",
        parent: 98,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: true,
      open_markets_count: 55,
      landing_banner: false,
    },
    {
      id: 98,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/d0/b9/d0b911ce129f22e086f0aa5491a32a33.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/81/38/8138a9332294b7f4331154a8496206a3.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/36/a6/36a69ea83f84e0a3a3b4500cf0df5b54.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/ae/96/ae965c552888fdfbd055e2b4f55cd453.webp",
      link: "https://My_Company.com/q/category/8/football",
      title: "Football",
      short_description:
        "Matches and prop markets from tournaments all over the world",
      action_text: "Place your bets",
      category: 8,
      category_dict: {
        id: 8,
        title: "Football",
        slug: "football",
        parent: 7,
        in_leaderboard: false,
        icon: null,
      },
      end_date: null,
      hot_topic: true,
      open_markets_count: 125,
      landing_banner: false,
    },

    {
      id: 1,
      desktop_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/69/96/699635b11663281902877af264b1f181.jpg",
      mobile_thumbnail:
        "https://My_Company-media-production.s3.amazonaws.com/cache/cf/d2/cfd2f40883ddc7bab784f1b4162d975e.jpg",
      desktop_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/1c/a1/1ca118c81f4728ab80396eb457330671.webp",
      mobile_thumbnail_webp:
        "https://My_Company-media-production.s3.amazonaws.com/cache/45/95/459548c28cc7fe4c056af42a85ffc93e.webp",
      link: "https://My_Company.com/q/suggest",
      title: "Suggest a Market! 🔮",
      short_description:
        "What do you want to know?\r\nPut the crowd to work for you",
      action_text: "Make a Suggestion",
      category: null,
      category_dict: null,
      end_date: null,
      hot_topic: false,
      open_markets_count: null,
      landing_banner: false,
    },
  ];
}

function getMarkets() {
  return [
    {
      id: 190203,
      title: "Next target for the US interest rate in March",
      slug: "next-target-for-the-us-interest-rate-in-march",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1309,
          title: "United States",
          slug: "united-states",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 588,
          title: "Interest Rates",
          slug: "interest-rates",
          parent: 1309,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473868,
          title: "Maintain the same rate",
          disabled: false,
          price: {
            OOM: 0.69,
            BTC: 0.87,
          },
          shares: {
            OOM: 55270.58496261389,
            BTC: 0.0103696079510267,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dc/e1/dce1caa6035177dd0c42e09e3e5f359f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/61/4d/614de1db6bb85f8d5b065c9ae0d1c301.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473869,
          title: "Lower by 0.25%",
          disabled: false,
          price: {
            OOM: 0.23,
            BTC: 0.11,
          },
          shares: {
            OOM: 53164.27096997742,
            BTC: 0.00965698434591765,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/40/02/4002210afbcb621d92d4d98929e05ff4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a0/a1/a0a19997804e270a1d54a374dfb6b5e8.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473871,
          title: "Raise by 0.25%",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.02,
          },
          shares: {
            OOM: 50839.31439437332,
            BTC: 0.008938590888294445,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/10/dd101f7d779a72dcf7adcb7d88409d7d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1a/5a/1a5aa7d59f9845a49719bdc1af9c6c13.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473870,
          title: "Lower by 0.5% or more",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 45033.77188888974,
            BTC: 0.008353752637004639,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/2f/b42f92a7d19ec8c6465e8710c20fec40.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/c0/b6c009c3ebe6b24b9ecb2e8b752ffe8e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473867,
          title: "Raise by 0.5% or more",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 45015.48988052412,
            BTC: 0.008055472378870833,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4f/f8/4ff8c465d6a2ddb1b471a6f3d088f5d3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/39/6d/396d7faf54146bed8fdb84b59dfb896e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-20T15:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 3,
      wagers_count_total: 8,
      wagers: null,
      tags: [],
      volume_play_money: 10910.0,
      volume_real_money: 102.41996106,
      is_following: false,
    },
    {
      id: 190188,
      title: "Will the US National vacancies rate rise above 6.6% in Q1?",
      slug: "will-the-us-national-vacancies-rate-rise-above-66-in-q4",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1309,
          title: "United States",
          slug: "united-states",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2274,
          title: "Housing Market",
          slug: "housing-market",
          parent: 1309,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473825,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.6,
            BTC: 0.6,
          },
          shares: {
            OOM: 126738.21126010442,
            BTC: 0.02528133338321482,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5f/97/5f97a5a9ea6a404ce35c310c3571a03f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/02/ce/02cec824e5bf3e28bfc39696c5497cf2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473826,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.4,
          },
          shares: {
            OOM: 122025.59996754512,
            BTC: 0.024358125337104467,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/73/a3/73a3e4925cc28db4a7be86ab79355246.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/2c/622cf67876ccbe076c8d0540fbdd01bc.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-04-30T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [
        {
          slug: "real-estate",
          name: "Real Estate",
        },
      ],
      volume_play_money: 10310.0,
      volume_real_money: 86.93272,
      is_following: false,
    },
    {
      id: 190096,
      title:
        "Which party will win Connecticut in the 2024 presidential election?",
      slug: "which-party-will-win-connecticut-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473604,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.97,
          },
          shares: {
            OOM: 43619.756200026844,
            BTC: 0.008723951046062318,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/19/12/1912ed71b07a30235a8072a0ee5f9330.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/aa/92aa899c00479d973d40b830fd958d5c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473605,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.69988591461,
            BTC: 0.007079539831358683,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/11/5c11403b4aa1adf6b9b1e7834992a776.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/4d/924dab6f92f48a91712956307b66eeb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473606,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43411567828,
            BTC: 0.006734486628741257,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/ae/77ae0584b3cb5b788ed4af60ce7139e7.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/e1/1fe106d1f797f86f866b540abc0485e6.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 0,
      wagers_count_total: 0,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190104,
      title: "Which party will win New York in the 2024 presidential election?",
      slug: "which-party-will-win-new-york-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473628,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.98,
          },
          shares: {
            OOM: 45469.1424512631,
            BTC: 0.008988215945316506,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/06/fd/06fd1a6c3d315f89c6db037bea4cf862.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/fa/91fa25a131d39da5cdb775abebfb9aae.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/94/1b/941bb30271936761f802810d8fbffb96.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d3/f8/d3f8b6261da086c349ef441ca416ff90.webp",
        },
        {
          id: 473629,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168044180486,
            BTC: 0.006979233615147038,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/09/60/096088990930e43fa2578724dd52fe66.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6e/ef/6eef0b60c8ad0b9e56e41c6f318c78b9.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/07/03/070350da68dfff8c2ddd7ad05735bc65.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e4/14/e414905d8829b263b9f0902c32a3b81b.webp",
        },
        {
          id: 473630,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168044180486,
            BTC: 0.006979233615147038,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/13/fd/13fd67daaaa3da7049afdf72c6f619c4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/64/9164a736e835a047cb8d7abf677006e1.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/15/78/1578b6b0facdfcf449a2f41ed5ace192.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/04/a8/04a83980c8afd8f81d34e51f874987e8.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [],
      volume_play_money: 10600.0,
      volume_real_money: 87.93007252,
      is_following: false,
    },
    {
      id: 190054,
      title: "SAG Awards winner for Best Actress in a Series (Comedy)",
      slug: "sag-awards-winner-for-best-actress-in-a-series-comedy",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473476,
          title: "Ayo Edebiri, The Bear",
          disabled: false,
          price: {
            OOM: 0.61,
            BTC: 0.62,
          },
          shares: {
            OOM: 97352.28187697755,
            BTC: 0.01950808673966084,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1b/c7/1bc74910a26a6b19917e5ff7a89fa7f0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/ff/f2ff70c37bbb21b71276004ce9bc5bf7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473475,
          title: "Quinta Brunson, Abbott Elementary",
          disabled: false,
          price: {
            OOM: 0.16,
            BTC: 0.15,
          },
          shares: {
            OOM: 92516.9311070242,
            BTC: 0.018503382182245582,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/26/7d/267d98d2f62cfa6c975bfe100968e2ce.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/35/d4/35d4aeae0dceb906dba46a6c8d437b8e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473477,
          title: "Hannah Waddingham, Ted Lasso",
          disabled: false,
          price: {
            OOM: 0.1,
            BTC: 0.09,
          },
          shares: {
            OOM: 90792.79038037342,
            BTC: 0.018158554531669525,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/21/5a/215add2cd7a8ea2f5d12c3f94c3d7a00.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/30/04/3004f6a459d193dcd5da64d163cbb2a4.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473474,
          title: "Rachel Brosnahan, The Marvelous Mrs. Maisel",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.08,
          },
          shares: {
            OOM: 89950.01818441489,
            BTC: 0.017989999143398168,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c3/d5/c3d55e80c5111aac5f3a52108009ecc9.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fb/97/fb9775c2d3f6ef6c886c4c67405df40a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473473,
          title: "Alex Borstein, The Marvelous Mrs. Maisel",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.06,
          },
          shares: {
            OOM: 88829.65158235005,
            BTC: 0.017765925884141463,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/a4/f5a48b50138f6d584aefa8f9941263e0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/48/3e/483e94e8dab2fe11557eb3b3a8b93379.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [],
      volume_play_money: 10110.0,
      volume_real_money: 89.17572002,
      is_following: false,
    },
    {
      id: 187335,
      title:
        "What will be the Brazilian basic interest rate at the end of 2024?",
      slug: "what-will-be-the-brazilian-basic-interest-rate-at-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1310,
          title: "Brazil",
          slug: "Brazil",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1569,
          title: "Interest rate",
          slug: "interest-rate",
          parent: 1310,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 466702,
          title: "Less than 8.5%",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.24,
          },
          shares: {
            OOM: 139991.745411682,
            BTC: 0.027115860871121555,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/78/ce/78cefc612d42be1d644e657054980679.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f9/99/f999c72a11a5c7b661ed0663e8c6850a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466705,
          title: "Between 9.5% and 9.9%",
          disabled: false,
          price: {
            OOM: 0.32,
            BTC: 0.13,
          },
          shares: {
            OOM: 139187.94619403774,
            BTC: 0.026713742385042077,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6a/e7/6ae72a06e1fb3ee614be0d691547128b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bc/69/bc69577bb03b3eca98663789d1803bef.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466704,
          title: "Between 9.0% and 9.4%",
          disabled: false,
          price: {
            OOM: 0.14,
            BTC: 0.37,
          },
          shares: {
            OOM: 136279.02917220187,
            BTC: 0.027414675627393918,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/a3/7fa3360c9ae4402d492f573b09b6d2f0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/93/3e/933eeaa99c3eece0abcd60206593aa6b.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466706,
          title: "10% or more",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.02,
          },
          shares: {
            OOM: 134435.57291820002,
            BTC: 0.02531431969522252,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/f5/f5f57cca6a73678a39be21981ef72372.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f0/5f/f05f5355b2a5d08a3240223ddca62064.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466703,
          title: "Between 8.5% and 8.9%",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.23,
          },
          shares: {
            OOM: 133274.55037115444,
            BTC: 0.027090751590525278,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/33/dc/33dc69dda060ea21ff6e11860ea2baaa.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/63/b76342b679d2d32912707847c2543209.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-11T10:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 9,
      wagers_count_canonical: 8,
      wagers_count_total: 17,
      wagers: null,
      tags: [
        {
          slug: "interest-rate",
          name: "Interest rate",
        },
      ],
      volume_play_money: 16510.0,
      volume_real_money: 118.94904784,
      is_following: false,
    },
    {
      id: 190105,
      title:
        "Which party will win Rhode Island in the 2024 presidential election?",
      slug: "which-party-will-win-rhode-island-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473631,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.98,
          },
          shares: {
            OOM: 45064.09666969168,
            BTC: 0.00897224232936561,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ee/ce/eeceb5746f37e27e4266a9d683850549.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/dd/79dd9e37fc337d5fea793ec98c32efa7.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/91/7f9109828801236cf36859e043719cb9.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/85/be852525e7e37195ae2676211f12bb2d.webp",
        },
        {
          id: 473632,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.16847757407,
            BTC: 0.0069792335983711465,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/86/63/8663187d5de9aeafbb436bbac5c4d251.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5b/d9/5bd9806e1ad9e004a1e4bc50329b1e69.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/ab/afab9b00e7425364630084d81847f728.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ae/1e/ae1e447b2fda2f13fdb3234e86fccf1d.webp",
        },
        {
          id: 473633,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.16847757407,
            BTC: 0.0069792335983711465,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/18/c5/18c5ee14da3fcfab95a1e42ec4a29b3d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/0c/970c6e05644d8992b85e00b636a9756d.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/42/9a/429ab3617bfaf35e3da4a54097043ca1.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/8a/628a064dd39f7e50855f846bce4b48be.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10200.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190128,
      title: "Will Ecuadorian authorities capture Fito by the end of February?",
      slug: "will-ecuadorian-authorities-capture-fito-by-the-end-of-february",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 584,
          title: "Ecuador",
          slug: "ecuador",
          parent: 105,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473680,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.68,
            BTC: 0.74,
          },
          shares: {
            OOM: 102160.25856415549,
            BTC: 0.020943728606335213,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4c/e4/4ce4fa5e83bd5b5943ce33fcc534006a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ec/88/ec887d078215e4b6d8e7cf19d09698c5.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473679,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.32,
            BTC: 0.26,
          },
          shares: {
            OOM: 95321.96819873585,
            BTC: 0.01906439363825679,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/03/ea/03ea6dab0a8ab619012537be55923c68.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/60/d860ffe0860005903dca979acf701bda.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-25T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 4,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 10010.0,
      volume_real_money: 102.81759621,
      is_following: false,
    },
    {
      id: 190041,
      title: "SAG Awards winner for Best Film Cast",
      slug: "sag-awards-winner-for-best-film-cast",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473412,
          title: "Oppenheimer",
          disabled: false,
          price: {
            OOM: 0.49,
            BTC: 0.58,
          },
          shares: {
            OOM: 102474.2483052974,
            BTC: 0.02070492457852198,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/00/b9/00b955964d9b4080d3c61f5edd49cc4f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/ea/91ea546e7e4ddaf355dc31145e484f2d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473409,
          title: "Barbie",
          disabled: false,
          price: {
            OOM: 0.26,
            BTC: 0.15,
          },
          shares: {
            OOM: 100141.42432872241,
            BTC: 0.019686143357932333,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/27/ff27e6e4f745a59ab05931f324a4b5fe.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/31/f5312292fed7e10a9b5dd18040bd1ff7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473408,
          title: "American Fiction",
          disabled: false,
          price: {
            OOM: 0.11,
            BTC: 0.1,
          },
          shares: {
            OOM: 96652.5778552788,
            BTC: 0.01933051557105576,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/04/3f0434c4b18673db39ab9f4b343c3293.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f8/80/f880101745a83ecf6c4c748b7875616c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473411,
          title: "Killers of the Flower Moon",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.1,
          },
          shares: {
            OOM: 95506.31900494074,
            BTC: 0.019329955566166748,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bc/51/bc512d18a41da015101c5efc46172f5a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7a/ac/7aac7d00b98c187548646e00e8f70da0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473410,
          title: "The Color Purple",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.08,
          },
          shares: {
            OOM: 94474.29002494394,
            BTC: 0.019183743279140887,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/07/97070f65a9d77c8d6647d9bda06ac0c0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ef/f9/eff9a5dfa92a73cdc2c42aad9838ff19.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 3,
      wagers_count_total: 8,
      wagers: null,
      tags: [],
      volume_play_money: 10510.0,
      volume_real_money: 96.17636064,
      is_following: false,
    },
    {
      id: 190106,
      title: "Which party will win Vermont in the 2024 presidential election?",
      slug: "which-party-will-win-vermont-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473634,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.94,
            BTC: 0.98,
          },
          shares: {
            OOM: 45521.87475417447,
            BTC: 0.008972242339785271,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ca/72/ca721be4628fbc87264276212ab48b56.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c9/43/c943106de7c25765ff228d5647bb997d.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/ca/5ccad06854e26377dd8965ad2689a603.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/83/14/8314f6ccd3a1dfcde95a66a611422075.webp",
        },
        {
          id: 473635,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.05,
            BTC: 0.01,
          },
          shares: {
            OOM: 39059.72559106064,
            BTC: 0.00697923360884658,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7c/4f/7c4f53ad1b2c384f6f310bc5d277b000.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/43/f44391645c54956440b22714bb72b7ce.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/12/65/12650807b1d3f0d170b581609b13a1cc.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b0/0f/b00f507c01eb10a30c701ab8ec5ca9dc.webp",
        },
        {
          id: 473636,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168018169636,
            BTC: 0.00697923360884658,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/00/a8/00a8dcb561f88f7dcefd1097979a1dce.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/d3/71d3caa8786c03866a0532baf1fb134f.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/34/35/34353d07b2e37647cca4b6632547e02f.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4c/32/4c32e60af8c05e106940faf7e2068118.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10750.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190199,
      title:
        "What will be the price of a gallon of gasoline in the US at the end of February?",
      slug: "what-will-be-the-price-of-a-gallon-of-gasoline-in-the-us-at-the-end-of-february",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 2066,
          title: "Commodities",
          slug: "commodities",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2232,
          title: "Oil",
          slug: "oil",
          parent: 2066,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473857,
          title: "Between 3.100 and 3.300",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.58,
          },
          shares: {
            OOM: 98333.61824125012,
            BTC: 0.019663786786311387,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ed/68/ed68d57aa6ec6d61d36f4809aeb3c162.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/57/99/5799f7480c97ec7e4221d402cc0c66e9.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/0b/1f0b714595a3a13eba73fe1ab1100910.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/cf/a9/cfa9adf5f88000383fe056d8c029b541.webp",
        },
        {
          id: 473859,
          title: "More than 3.300",
          disabled: false,
          price: {
            OOM: 0.21,
            BTC: 0.28,
          },
          shares: {
            OOM: 92476.87878737628,
            BTC: 0.018889571424653546,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/69/df/69dfb9430d357d783fb476d0c8cdb0bb.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bb/76/bb76d81f6976bfd05b450c5b88db6991.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/98/5c98af7903325598af02e932790c6889.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/8b/628b06e7cff788b2910de4cc05baee19.webp",
        },
        {
          id: 473858,
          title: "Less than 3.100",
          disabled: false,
          price: {
            OOM: 0.15,
            BTC: 0.14,
          },
          shares: {
            OOM: 90404.17636973149,
            BTC: 0.018080834393496108,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/41/d6/41d6d9626b488f52cdf8cfb1ec154c40.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/65/8d/658d6e6a82c1d205a82908801f7c27c2.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/51/64/516456e109212209864ba50f85eb1b22.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/99/8b/998b823907a461b5737f0510d9390b43.webp",
        },
      ],
      bet_end_date: "2024-02-26T06:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [
        {
          slug: "inflation",
          name: "Inflation",
        },
      ],
      volume_play_money: 10110.0,
      volume_real_money: 91.21424,
      is_following: false,
    },
    {
      id: 190192,
      title:
        "Will a second ceasefire be initiated in Gaza by the end of February 2024?",
      slug: "will-a-second-ceasefire-be-initiated-in-gaza-by-the-end-of-january-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 108,
          title: "Middle East",
          slug: "middle-east",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3484,
          title: "Palestine",
          slug: "palestine",
          parent: 108,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473833,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.6,
            BTC: 0.83,
          },
          shares: {
            OOM: 73607.95207751395,
            BTC: 0.017971382110329268,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f8/bf/f8bff12e7801c6ae187a21eb83cb88cb.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/f4/f2f4fea20d659eba48299270a9b78503.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473834,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.17,
          },
          shares: {
            OOM: 70972.39072421704,
            BTC: 0.015530830644389572,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a4/4a/a44a7cb1f0cd4ca6dfb0af3fd55de026.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/3c/b53c776df30886076d19e5ed37bd3e95.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-29T00:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 11,
      wagers_count_total: 15,
      wagers: null,
      tags: [
        {
          slug: "israel",
          name: "Israel",
        },
        {
          slug: "israel-hamas-war",
          name: "Israel-Hamas War",
        },
      ],
      volume_play_money: 12140.0,
      volume_real_money: 122.97798837,
      is_following: false,
    },
    {
      id: 190076,
      title: "Which party will win Iowa in the 2024 presidential election?",
      slug: "which-party-will-win-iowa-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3558,
          title: "Swing States",
          slug: "swing-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473545,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.9,
            BTC: 0.96,
          },
          shares: {
            OOM: 40310.359110360434,
            BTC: 0.008134786339529349,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/b8/f4b860e5556ac87ff4899bb6354f1c81.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/3a/813a447b27d46396271886308d180d74.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473544,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.04,
          },
          shares: {
            OOM: 35864.081492766265,
            BTC: 0.006875742922755695,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/5c/dd5ca4ede707bc4c0467b8b7259e5143.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/db/ec/dbec8e3d42651637e103c268918df018.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473546,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 29195.84752783412,
            BTC: 0.00583916957892365,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/18/711821f8da62b21a98c69d9c23b498d3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ef/16/ef166bc6ca93b2742b99b03d0791425c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 1,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 11300.0,
      volume_real_money: 103.1777043,
      is_following: false,
    },
    {
      id: 186441,
      title:
        "Will the Premier League punish Manchester City for violating financial fairplay in the 23/24 season?",
      slug: "will-the-premier-league-punish-manchester-city-for-violating-financial-fairplay-in-the-2324-season",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 32,
          title: "England",
          slug: "england",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464549,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.67,
            BTC: 0.39,
          },
          shares: {
            OOM: 194601.01600683905,
            BTC: 0.03648651513242826,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f3/8a/f38a0df59df31b2f15e010dc8aadcb55.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b1/8d/b18d14f77414d37b236b5e3c1a0f3b38.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 464548,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.33,
            BTC: 0.61,
          },
          shares: {
            OOM: 186569.26976879698,
            BTC: 0.037523526736022314,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9e/19/9e19b681fcfa2d9f60a27c3d9320df01.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/80/6c/806caeb42543f1fcc4aa6230dcf1e9c7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-31T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 26,
      wagers_count_canonical: 9,
      wagers_count_total: 35,
      wagers: null,
      tags: [],
      volume_play_money: 19157.21,
      volume_real_money: 117.45559139,
      is_following: false,
    },
    {
      id: 186448,
      title:
        "Will Xabi Alonso continue to work as manager of Leverkusen through the end of the 23/24 season?",
      slug: "will-xabi-alonso-continue-to-work-as-manager-of-leverkusen-through-the-end-of-the-2324-season",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 13,
          title: "Germany",
          slug: "germany",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464562,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.75,
            BTC: 0.84,
          },
          shares: {
            OOM: 240528.37470045977,
            BTC: 0.04564804195323253,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/8d/4b/8d4b92c7af9775acaa29ca48fbb12a5f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1d/ae/1dae53d83d4aa100416a595360301961.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/75/96/75961e7c77333d22237180e2c1a1d850.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/79/3f7997331ad3f2bd53b84a320d610248.webp",
        },
        {
          id: 464563,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.25,
            BTC: 0.16,
          },
          shares: {
            OOM: 225007.7892550557,
            BTC: 0.04141696112414745,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0f/8d/0f8d8172aa97c0d51b248fad3dd0575e.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/08/8f/088f25642b3112c94ad6b8b27d786749.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-31T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 65,
      wagers_count_canonical: 10,
      wagers_count_total: 75,
      wagers: null,
      tags: [],
      volume_play_money: 35284.02,
      volume_real_money: 308.55029661,
      is_following: false,
    },
    {
      id: 190058,
      title: "Which party will win Arkansas in the 2024 presidential election?",
      slug: "which-party-will-win-arkansas-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473491,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.97,
          },
          shares: {
            OOM: 44739.96507985771,
            BTC: 0.008723951215007639,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d7/93/d79310f708b8a819438a6a791f319bbf.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/8d/d88d093853b404f9c30dfbad9776c40d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473492,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43741313089,
            BTC: 0.006734486798087227,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c6/2d/c62d281e0d410c111242932277c0da31.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/80/5c805b73d05bdf70dcff0c7b17b54111.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473490,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.70222867107,
            BTC: 0.007079539958754622,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/84/be84ba63e84a76e3d2c9090a9d24d95f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/81/1f81a66ed20ec4b55603bc183cf1b633.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 11100.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190057,
      title: "Which party will win Alabama in the 2024 presidential election?",
      slug: "which-party-will-win-alabama-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473488,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.96,
          },
          shares: {
            OOM: 43619.75857464054,
            BTC: 0.010908660755784427,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7e/4c/7e4c77ec34c9713d019f18f1dfae3ee1.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/51/30/51301a4b3493f9d266180f61a3371e85.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473487,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.03,
          },
          shares: {
            OOM: 35397.70152691932,
            BTC: 0.009234693880437296,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/bb/f2bb9e10b8989f5ffb50849cdc3bdedc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/8f/7d8fee8838897681da94326ceca79856.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473489,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43649287858,
            BTC: 0.006734487185980464,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/17/35/1735ab18ea11113ea22dea17dc762e4f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/9a/b59a78f5df9bb40e4451f1dca6a6d9bf.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 2,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 182.4843162,
      is_following: false,
    },
    {
      id: 190195,
      title: "Will the US government shut down in March?",
      slug: "will-the-us-government-shut-down-in-march",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 114,
          title: "Congress",
          slug: "congress",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473842,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.88,
            BTC: 0.76,
          },
          shares: {
            OOM: 107289.07083030652,
            BTC: 0.020545052582040122,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/43/f4/43f4907fcfe33f48e16e26fd18aee4d5.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/b6/97b6b2c975a8ceb1e6872daad7c0a986.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473843,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.12,
            BTC: 0.24,
          },
          shares: {
            OOM: 89595.65625944991,
            BTC: 0.018507033075836374,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/74/22/74220dcbf22addeb10d2377df379e211.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/f1/b4f1c28b5cb01f7cf068ba1308cf7673.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-01T00:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 4,
      wagers_count_total: 7,
      wagers: null,
      tags: [
        {
          slug: "shutdown",
          name: "Shutdown",
        },
      ],
      volume_play_money: 18700.0,
      volume_real_money: 107.55244862,
      is_following: false,
    },
    {
      id: 190715,
      title: "Kawasaki Frontale vs. Shandong Taishan",
      slug: "kawasaki-frontale-vs-shandong-taishan",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 65,
          title: "International",
          slug: "international",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 265,
          title: "Afc Champions League",
          slug: "afc-champions-league",
          parent: 65,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 475058,
          title: "Kawasaki Frontale",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.64,
          },
          shares: {
            OOM: 94475.19110689206,
            BTC: 0.018895039435863594,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/b6/beb621143348fdbd6dd8247682c1deb3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d7/b1/d7b191ffefadd7d204fe844a67fd0e2e.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/88/6288a4bb9830c753c120395b3c821c7d.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e3/fd/e3fd63b654a7917af725d1cabc7d1235.webp",
        },
        {
          id: 475060,
          title: "Tie",
          disabled: false,
          price: {
            OOM: 0.22,
            BTC: 0.22,
          },
          shares: {
            OOM: 88992.48423871474,
            BTC: 0.017798497982852178,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e6/1d/e61d1226928f679e6f42d3091ebcece6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/78/79789e9771c0786e5256f1823a1619c0.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/74/8c/748c46c57696e25fc29c84c64b53d25e.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/6d/b56d772b5d6f0d4f28241669ab4053d1.webp",
        },
        {
          id: 475059,
          title: "Shandong Taishan",
          disabled: false,
          price: {
            OOM: 0.14,
            BTC: 0.14,
          },
          shares: {
            OOM: 86557.9074798944,
            BTC: 0.017311582756931728,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/89/0e/890e35789e420eb41345897c48566b68.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/06/2e/062ed78ef6349bd7e78cead03aa1579f.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/39/18/39184c52efebb70ffaa7b7a4b9f0ca0e.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5a/36/5a36a07e3d50bd8a23493fce9aeeaa08.webp",
        },
      ],
      bet_end_date: "2024-02-20T08:00:00Z",
      event_start_date: "2024-02-20T08:00:00Z",
      event_end_date: "2024-02-20T09:45:00Z",
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 0,
      wagers_count_total: 0,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 85.60766,
      is_following: false,
    },
    {
      id: 190101,
      title: "Which party will win Oregon in the 2024 presidential election?",
      slug: "which-party-will-win-oregon-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473619,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.92,
            BTC: 0.97,
          },
          shares: {
            OOM: 43718.72463930023,
            BTC: 0.008723951183554433,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ea/47/ea47c6c4ab5676ca4ef77d94f44c9273.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/19/62/1962048f40a956ee245adc55a462dba9.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473620,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.02,
          },
          shares: {
            OOM: 38003.8830244257,
            BTC: 0.007079539921055864,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/68/f0/68f00f5080831b157b28f4780e2434e6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/7c/3f7c701b4c50a504da84088d003f7cb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473621,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33669.095818192436,
            BTC: 0.006734486766274902,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/57/88/57881faf1263847d661bcdc555730c5c.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d6/8b/d68bd7050b6f4254221bc9c89119323e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10200.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190052,
      title: "SAG Awards winner for Best Actress in a Series (Drama)",
      slug: "sag-awards-winner-for-best-actress-in-a-series-drama",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473467,
          title: "Sarah Snook, Succession",
          disabled: false,
          price: {
            OOM: 0.58,
            BTC: 0.66,
          },
          shares: {
            OOM: 97963.83413473141,
            BTC: 0.019624926256438968,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/16/fc/16fc40e6cad198129987b8828638ce48.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/38/c8/38c843b06eeb6ca9cd92c75cf48239eb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473464,
          title: "Elizabeth Debicki, The Crown",
          disabled: false,
          price: {
            OOM: 0.16,
            BTC: 0.12,
          },
          shares: {
            OOM: 93409.51630368773,
            BTC: 0.01842824225896014,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/54/96/54964989fd0dac3bc06ec59571144b32.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dc/29/dc29b7694e922fe24d826fd8d9bf44c1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473466,
          title: "Keri Russell, The Diplomat",
          disabled: false,
          price: {
            OOM: 0.1,
            BTC: 0.06,
          },
          shares: {
            OOM: 91656.99948326458,
            BTC: 0.01784385082539961,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fe/4b/fe4b6b5e4ce023b38506c4636d7eb4ad.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/78/44/78448ad4da9673b29bb41638cbc11fee.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473465,
          title: "Bella Ramsey, The Last of Us",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.08,
          },
          shares: {
            OOM: 90779.45266863746,
            BTC: 0.01815588613355777,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ad/1c/ad1cdc464e5a3ca8a72c883ab7185518.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/01/40/0140d991009d2bb4ea5b2b7f6b48e7a9.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473463,
          title: "Jennifer Aniston, The Morning Show",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.08,
          },
          shares: {
            OOM: 90333.84428953128,
            BTC: 0.018066764250753167,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/0d/ff0dba1aad1b5ae64e6318c3a826fd25.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a5/00/a500edf7c2a7ea7e48bad09ed13e8eb0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 6,
      wagers_count_canonical: 1,
      wagers_count_total: 7,
      wagers: null,
      tags: [],
      volume_play_money: 10510.0,
      volume_real_money: 89.17572002,
      is_following: false,
    },
    {
      id: 190099,
      title:
        "Which party will win Massachusetts in the 2024 presidential election?",
      slug: "which-party-will-win-massachusetts-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473613,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.93,
            BTC: 0.97,
          },
          shares: {
            OOM: 43935.97852315655,
            BTC: 0.008723951242840497,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/d3/81d3bf7cf63f778a5e1a1763b44800c6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ac/db/acdb1fd64001705becf0930fc895b575.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473614,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.02,
          },
          shares: {
            OOM: 38007.38152905486,
            BTC: 0.007079539972607898,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9d/45/9d453856d96606efde8fa023254ba7a4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/4d/624de8126ee15d35ffbaf8fc03a867d1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473615,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33673.32187678596,
            BTC: 0.006734486825840894,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e6/15/e615f81425f4cd08fa10f810031844b8.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/69/7d6935e139c5c803cc23e530169897e4.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10400.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 181308,
      title:
        "Will Donald Trump announce his choice of running mate (vice president) before the Republican National Convention?",
      slug: "will-donald-trump-announce-his-choice-of-running-mate-vice-president-before-the-republican-national-convention",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3561,
          title: "Primaries",
          slug: "primaries",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3548,
          title: "Republican Primaries",
          slug: "republican-primaries",
          parent: 3561,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 452385,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.63,
            BTC: 0.7,
          },
          shares: {
            OOM: 49306.40545423327,
            BTC: 0.010697525748049564,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/38/d8388fb822623f02112e3327e3586bef.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/db/f5/dbf51b40819252f79e9e8bb6562ab717.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 452386,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.37,
            BTC: 0.3,
          },
          shares: {
            OOM: 46259.29167871124,
            BTC: 0.009635973080195271,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/20/5f/205fdcf188dca15e375fae5e11eb35d0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7c/cb/7ccb92948f55123780e997d94f7dd5a6.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-07-14T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 15,
      wagers_count_canonical: 6,
      wagers_count_total: 21,
      wagers: null,
      tags: [
        {
          slug: "donald-trump",
          name: "Donald Trump",
        },
        {
          slug: "elections",
          name: "Elections",
        },
      ],
      volume_play_money: 6540.96,
      volume_real_money: 78.22215958,
      is_following: false,
    },
    {
      id: 190098,
      title: "Which party will win Idaho in the 2024 presidential election?",
      slug: "which-party-will-win-idaho-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473610,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.13,
          },
          shares: {
            OOM: 43619.754741245495,
            BTC: 0.008807462624911415,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/38/e5/38e58f83ef07008862a04807ffd2fdb3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/44/f444aa6ea9870e2c6a6b3a40c8ed13c0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473611,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.86,
          },
          shares: {
            OOM: 35397.69879873151,
            BTC: 0.009710547433102013,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/76/54/7654f95d743602d012133eb8494db005.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c5/7c/c57c465bec565869ff4e7146f3f78f0e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473612,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43265369867,
            BTC: 0.006734486756230058,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b1/ec/b1eca3bc42452dac55f8822d9a7dfc19.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/68/2e/682eb75e91b47c9c4f76ba67ef9da169.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 5,
      wagers_count_total: 6,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 132.8520713,
      is_following: false,
    },
    {
      id: 182110,
      title:
        "Will the fake news inquiry be closed in Brazil's Supreme Court by the end of 2024?",
      slug: "will-the-fake-news-inquiry-be-closed-in-brazils-supreme-court-by-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 106,
          title: "Brazil",
          slug: "brazil",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1364,
          title: "Judiciary",
          slug: "judiciary",
          parent: 106,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 454288,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.62,
            BTC: 0.69,
          },
          shares: {
            OOM: 154409.17417459982,
            BTC: 0.029755170065968582,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/38/6238661367fb8788f8d528367d5931c3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ec/59/ec595da9c0604e637afb3fcd9c031c41.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/28/5b/285b30d59fa2a030145a9bda27f92165.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ce/53/ce539449597cf11723b48d6fa6ac9971.webp",
        },
        {
          id: 454287,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.38,
            BTC: 0.31,
          },
          shares: {
            OOM: 149811.01571936192,
            BTC: 0.02835042063199287,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/4e/af4e19a609e237c1931393b8b8e8b4c6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/90/92/9092ba2a56409ec106d2188c44485fe2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-23T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 34,
      wagers_count_canonical: 2,
      wagers_count_total: 36,
      wagers: null,
      tags: [
        {
          slug: "brazil",
          name: "Brazil",
        },
        {
          slug: "judiciary",
          name: "Judiciary",
        },
      ],
      volume_play_money: 16740.1,
      volume_real_money: 86.35559814,
      is_following: false,
    },
    {
      id: 190065,
      title: "Which party will win Utah in the 2024 presidential election?",
      slug: "which-party-will-win-utah-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473512,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.94,
            BTC: 0.94,
          },
          shares: {
            OOM: 50383.07487771923,
            BTC: 0.010045062560438979,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/90/fd/90fd71929dac9582a0f5eeb001f1391d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/88/928874c1d01eeca3e42f5f2d745d5bd8.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473513,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.04,
            BTC: 0.04,
          },
          shares: {
            OOM: 42273.71873047387,
            BTC: 0.008454743953146333,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e2/40/e2405166b1f7000d5d81e68eb488daa3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/04/810466702f475bb6d220262b7576b04c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473511,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 40292.38453623792,
            BTC: 0.008058477175181456,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3e/5e/3e5e73d38ffe9a55431e46840336901f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9c/fb/9cfb4de3327429c2a40551535e107b5a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 2,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 10210.0,
      volume_real_money: 87.75993671,
      is_following: false,
    },
    {
      id: 190068,
      title:
        "Which party will win Louisiana in the 2024 presidential election?",
      slug: "which-party-will-win-louisiana-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473521,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.96,
            BTC: 0.97,
          },
          shares: {
            OOM: 42670.85692756078,
            BTC: 0.0098969523519606,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/64/ab/64ab9498b23485ef4b306310eaf24d8a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4b/41/4b41753dd2bf1219c696fa64f7ad4faa.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473520,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.03,
            BTC: 0.03,
          },
          shares: {
            OOM: 35468.0881146019,
            BTC: 0.008237876442169017,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d5/26/d526788da0194939abd9038747420ea3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/72/b772401fa88a0c5b56ba6c7030c2b653.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473522,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 32741.29122682131,
            BTC: 0.00654826630312012,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/02/57/0257f5ca0b1b7b6ae0e0789fba7d0465.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ac/ed/aced199694e029fd148dd509c328d0c2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 2,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 146.4676872,
      is_following: false,
    },
    {
      id: 186420,
      title:
        "Will Brazil's Congress approve the end of reelection for the executive branch by the end of 2024?",
      slug: "will-brazils-congress-approve-the-end-of-reelection-for-the-executive-branch-by-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 106,
          title: "Brazil",
          slug: "brazil",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1449,
          title: "Congress",
          slug: "congress",
          parent: 106,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464507,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.92,
            BTC: 0.7,
          },
          shares: {
            OOM: 205585.73372109066,
            BTC: 0.03672024990096394,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/4a/7f4a979b5ac0b4bbcf94ee2cbb2d6be1.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a4/96/a4968ccb6b558d0fb4d5f900972da761.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f1/f1/f1f143f0b66b771eda67616b81b0d59f.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/56/b656b59628c9d0d1a65862dc5b5b2505.webp",
        },
        {
          id: 464506,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.3,
          },
          shares: {
            OOM: 177466.12711811627,
            BTC: 0.03485527503428112,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/42/bc/42bc2abbe90e1db8f8eec764d676736b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/2e/dd2ea8e3474a24d854adca9bdd961eb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-01T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 45,
      wagers_count_canonical: 4,
      wagers_count_total: 49,
      wagers: null,
      tags: [
        {
          slug: "elections",
          name: "Elections",
        },
        {
          slug: "president",
          name: "President",
        },
      ],
      volume_play_money: 32145.0,
      volume_real_money: 110.51829205,
      is_following: false,
    },
    {
      id: 190027,
      title: "Oscar winner for Best Original Screenplay",
      slug: "oscar-winner-for-best-original-screenplay",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3563,
          title: "Oscars 2024",
          slug: "oscars-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473338,
          title: "Anatomy of a Fall",
          disabled: false,
          price: {
            OOM: 0.45,
            BTC: 0.58,
          },
          shares: {
            OOM: 46917.76265933138,
            BTC: 0.009628474855797844,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0f/ca/0fca831c0f0e2b54a19d4e8ceb94d5bc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c6/56/c6564457222355a4910df02d43b78e1a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473339,
          title: "The Holdovers",
          disabled: false,
          price: {
            OOM: 0.41,
            BTC: 0.32,
          },
          shares: {
            OOM: 46779.857348251164,
            BTC: 0.009430313741559913,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d1/ef/d1efd9a6b4154c5682be121e5aa20d35.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/10/f5102f8233a86e63beef45d11c09055a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473342,
          title: "Past Lives",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.06,
          },
          shares: {
            OOM: 44277.25984288743,
            BTC: 0.008858416279059549,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/20/3f/203f3025429171b4a11bd4d017c3b4ec.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/34/a6/34a61fd3d16eed48cdc70032df472cda.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473341,
          title: "May December",
          disabled: false,
          price: {
            OOM: 0.04,
            BTC: 0.02,
          },
          shares: {
            OOM: 42430.224278812544,
            BTC: 0.008488853129358938,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/95/ff95219fd51fd0bfc0a868771de11e7f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/5d/715daafc1fd10fbd4b44c8d5e337f9e3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473340,
          title: "Maestro",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 37474.96362302919,
            BTC: 0.007498183898668283,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/49/46/494651b9e36a76b09735484d5901f846.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ea/e3/eae3bb094b1fa84f2e2137112c208a34.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-10T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 5,
      wagers_count_total: 10,
      wagers: null,
      tags: [],
      volume_play_money: 10710.0,
      volume_real_money: 100.39073237,
      is_following: false,
    },
    {
      id: 190078,
      title: "Which party will win Florida in the 2024 presidential election?",
      slug: "which-party-will-win-florida-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3558,
          title: "Swing States",
          slug: "swing-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473551,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.9,
            BTC: 0.96,
          },
          shares: {
            OOM: 38391.37576905767,
            BTC: 0.008654843208579574,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/01/3f01f3cb6114c2d69cf17436308ce44b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6f/19/6f191bbf941b3a571059e359bcacb759.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473550,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.04,
          },
          shares: {
            OOM: 34048.272723508635,
            BTC: 0.007332830340290171,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4d/55/4d554c538d00d8fd81ad9ce09463a14f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/85/43/854359c7393f1342c14107a59291c0a1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473552,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 28564.976048549306,
            BTC: 0.00571299649615047,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ad/b0/adb0e4cbbb25b449c86c8e9f1830bb17.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1b/8e/1b8e77120fdd623f472465e7289a3726.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 3,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 135.90610082,
      is_following: false,
    },
    {
      id: 190059,
      title: "Which party will win Kentucky in the 2024 presidential election?",
      slug: "which-party-will-win-kentucky-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473494,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.97,
          },
          shares: {
            OOM: 43619.756706771026,
            BTC: 0.008724378020053956,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/48/e9/48e966b9c758032081b4240cddd35419.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/11/9f/119fcf69d440673d9dcfddb042e5780b.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473493,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.700232019735,
            BTC: 0.0070795397181776226,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/83/be8392703c1a301e67a954b3079f1809.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/16/59/1659bfe5700f47998ea3b195e0e143bf.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473495,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.434622891284,
            BTC: 0.006734486463398948,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/e9/77e95d2669798e38486b1156c9d5273a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/7a/7f7ace16ccf2c9702a5cba8feed5e5f5.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 1,
      wagers_count_total: 1,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 87.27191843,
      is_following: false,
    },
    {
      id: 186146,
      title: "Will Shein go public in the US by July 1st, 2024?",
      slug: "will-shein-go-public-in-the-us-by-july-1st-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 2129,
          title: "Big Companies",
          slug: "big-companies",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 463930,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.62,
          },
          shares: {
            OOM: 178701.74423374844,
            BTC: 0.03546354891144956,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/20/af204403210c6d011f26f4783f3c0349.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/3a/7d3ab1f941cab8221f3ca02f3ef8fdfb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 463929,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.36,
            BTC: 0.38,
          },
          shares: {
            OOM: 172752.9529500131,
            BTC: 0.03446248910383886,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/c3/d8c38bcf936ed98ebfd0eb0979d23651.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d5/05/d5054bf2450353412f93a6c79e6ffbdd.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-06-24T08:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 15,
      wagers_count_canonical: 4,
      wagers_count_total: 19,
      wagers: null,
      tags: [
        {
          slug: "ipos",
          name: "IPOs",
        },
        {
          slug: "shein",
          name: "Shein",
        },
      ],
      volume_play_money: 13961.0,
      volume_real_money: 110.95714001,
      is_following: false,
    },
    {
      id: 190051,
      title: "SAG Awards winner for Best Actor in a Series (Drama)",
      slug: "sag-awards-winner-for-best-actor-in-a-series-drama",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473460,
          title: "Kieran Culkin, Succession",
          disabled: false,
          price: {
            OOM: 0.55,
            BTC: 0.59,
          },
          shares: {
            OOM: 96483.73651634093,
            BTC: 0.019422809528817017,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0c/e7/0ce7aa7f1559a079071fc2028fd0ea4c.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/65/64/6564f461fef7367aea974d0084f8150a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473462,
          title: "Pedro Pascal, The Last of Us",
          disabled: false,
          price: {
            OOM: 0.21,
            BTC: 0.19,
          },
          shares: {
            OOM: 93088.58387031565,
            BTC: 0.018617706854483,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/24/5c/245c8734d22376c9d31eee20d44fbd84.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/70/b7703f0cb4c36d1c5cc54bfe6e174e3d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473461,
          title: "Matthew Macfadyen, Succession",
          disabled: false,
          price: {
            OOM: 0.11,
            BTC: 0.1,
          },
          shares: {
            OOM: 90896.14947289009,
            BTC: 0.018179220207193602,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/40/794029fdd9ab02c37421e374afc73127.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f7/80/f7802f01ce3bfe02fae4a2a36cfc36fb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473459,
          title: "Billy Crudup, The Morning Show",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.07,
          },
          shares: {
            OOM: 89370.7372977866,
            BTC: 0.017874138889053197,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/43/46/434680f14adc122f4f7fe3759e5a88dc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1a/b3/1ab3f1679273747e567eaf2291565781.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473458,
          title: "Brian Cox, Succession",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.05,
          },
          shares: {
            OOM: 88241.13863516579,
            BTC: 0.017648217218272862,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/5d/b65dd8bb8814bbc0bb12965a5162d2a5.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/70/49/7049fd60d0ad86ae240dec9e44c2b3e3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 1,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10210.0,
      volume_real_money: 92.17622011,
      is_following: false,
    },
    {
      id: 179586,
      title:
        "Will Bernardo Arévalo complete his Presidential term in Guatemala?",
      slug: "will-bernardo-arevalo-complete-his-presidential-term-in-guatemala",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 448197,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.53,
            BTC: 0.45,
          },
          shares: {
            OOM: 55741.31604405247,
            BTC: 0.010597473723417974,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/cc/a8/cca8218c9d977b89459e8a7eb72ac2e0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e1/68/e168e2603b22d7d3f63e54a672d92430.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b0/1e/b01e48f6e0a21285316a929e638bfd2c.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/df/f0/dff00a7bd0702d7d8de326349072e5dc.webp",
        },
        {
          id: 448198,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.47,
            BTC: 0.55,
          },
          shares: {
            OOM: 54843.092243967534,
            BTC: 0.010865890800622667,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b9/0c/b90c9369c0132de83b7cb0f71f837999.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/da/7d/da7da0db586308ffd958a4d5475af9d3.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/29/c1/29c18c26fafe1809fa3ad7bbc5afd49a.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/2a/b6/2ab6cb0d262c74bb41752e4f0090e6e7.webp",
        },
      ],
      bet_end_date: "2028-01-13T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 8,
      wagers_count_canonical: 0,
      wagers_count_total: 8,
      wagers: null,
      tags: [
        {
          slug: "guatemala",
          name: "Guatemala",
        },
        {
          slug: "president",
          name: "President",
        },
        {
          slug: "term-duration-presidents-and-prime-ministers",
          name: "Term Duration - Presidents and Prime Ministers",
        },
      ],
      volume_play_money: 6744.0,
      volume_real_money: 42.9991,
      is_following: false,
    },
    {
      id: 186443,
      title: "Will Haaland be the top scorer in the 23/24 Premier League?",
      slug: "will-haaland-be-the-top-scorer-in-the-2324-premier-league",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 32,
          title: "England",
          slug: "england",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464552,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.82,
            BTC: 0.76,
          },
          shares: {
            OOM: 178001.25004058407,
            BTC: 0.0320483799247418,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/c3/b6c3464fb0b0e9f0c1b48ff11e251b64.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e7/00/e700a6507851e60e4939c0c4b2a78c0f.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 464553,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.18,
            BTC: 0.24,
          },
          shares: {
            OOM: 162641.87149203703,
            BTC: 0.029932048090720236,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/26/772687705ac4b8ab98b48ffc3cc7c065.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/58/df/58df6947b2f50e3a32f055216b839145.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-04-30T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 58,
      wagers_count_canonical: 8,
      wagers_count_total: 66,
      wagers: null,
      tags: [],
      volume_play_money: 33703.17,
      volume_real_money: 145.31925346,
      is_following: false,
    },
    {
      id: 178461,
      title:
        "Will Ecuador improve its score in the Democracy Report ranking in 2024?",
      slug: "will-ecuador-improve-its-score-in-the-democracy-report-ranking-in-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 584,
          title: "Ecuador",
          slug: "ecuador",
          parent: 105,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3444,
          title: "Democracy Ranking",
          slug: "democracy-ranking",
          parent: 584,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 445281,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.58,
            BTC: 0.58,
          },
          shares: {
            OOM: 52266.44958690435,
            BTC: 0.010331750088493479,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fd/1a/fd1a63d1e1cca2847229890e61f1c68f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/98/b498510bea7ae51f8a753aa68390c78b.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ce/94/ce94ca6d0f295891438bced72a5c5cad.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/32/ed/32ed1ff21b802050f1953de2c9676314.webp",
        },
        {
          id: 445280,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.42,
            BTC: 0.42,
          },
          shares: {
            OOM: 50119.96713331726,
            BTC: 0.009934120943054594,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/9b/af9b0318ab5ef822118228688b2a0ce7.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c0/16/c0160617ef158bb172a192aceb016f2d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-01T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 8,
      wagers_count_canonical: 2,
      wagers_count_total: 10,
      wagers: null,
      tags: [
        {
          slug: "v-dem-liberal-democracy-ranking",
          name: "V-Dem Liberal Democracy Ranking",
        },
      ],
      volume_play_money: 5715.0,
      volume_real_money: 43.71993403,
      is_following: false,
    },
  ];
}

function getPostMarkets() {
  return [
    {
      id: 190203,
      title: "Next target for the US interest rate in March",
      slug: "next-target-for-the-us-interest-rate-in-march",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1309,
          title: "United States",
          slug: "united-states",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 588,
          title: "Interest Rates",
          slug: "interest-rates",
          parent: 1309,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473868,
          title: "Maintain the same rate",
          disabled: false,
          price: {
            OOM: 0.69,
            BTC: 0.87,
          },
          shares: {
            OOM: 55270.58496261389,
            BTC: 0.0103696079510267,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dc/e1/dce1caa6035177dd0c42e09e3e5f359f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/61/4d/614de1db6bb85f8d5b065c9ae0d1c301.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473869,
          title: "Lower by 0.25%",
          disabled: false,
          price: {
            OOM: 0.23,
            BTC: 0.11,
          },
          shares: {
            OOM: 53164.27096997742,
            BTC: 0.00965698434591765,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/40/02/4002210afbcb621d92d4d98929e05ff4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a0/a1/a0a19997804e270a1d54a374dfb6b5e8.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473871,
          title: "Raise by 0.25%",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.02,
          },
          shares: {
            OOM: 50839.31439437332,
            BTC: 0.008938590888294445,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/10/dd101f7d779a72dcf7adcb7d88409d7d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1a/5a/1a5aa7d59f9845a49719bdc1af9c6c13.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473870,
          title: "Lower by 0.5% or more",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 45033.77188888974,
            BTC: 0.008353752637004639,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/2f/b42f92a7d19ec8c6465e8710c20fec40.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/c0/b6c009c3ebe6b24b9ecb2e8b752ffe8e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473867,
          title: "Raise by 0.5% or more",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 45015.48988052412,
            BTC: 0.008055472378870833,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4f/f8/4ff8c465d6a2ddb1b471a6f3d088f5d3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/39/6d/396d7faf54146bed8fdb84b59dfb896e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-20T15:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 3,
      wagers_count_total: 8,
      wagers: null,
      tags: [],
      volume_play_money: 10910.0,
      volume_real_money: 102.41996106,
      is_following: false,
    },
    {
      id: 190188,
      title: "Will the US National vacancies rate rise above 6.6% in Q1?",
      slug: "will-the-us-national-vacancies-rate-rise-above-66-in-q4",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1309,
          title: "United States",
          slug: "united-states",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2274,
          title: "Housing Market",
          slug: "housing-market",
          parent: 1309,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473825,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.6,
            BTC: 0.6,
          },
          shares: {
            OOM: 126738.21126010442,
            BTC: 0.02528133338321482,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5f/97/5f97a5a9ea6a404ce35c310c3571a03f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/02/ce/02cec824e5bf3e28bfc39696c5497cf2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473826,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.4,
          },
          shares: {
            OOM: 122025.59996754512,
            BTC: 0.024358125337104467,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/73/a3/73a3e4925cc28db4a7be86ab79355246.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/2c/622cf67876ccbe076c8d0540fbdd01bc.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-04-30T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [
        {
          slug: "real-estate",
          name: "Real Estate",
        },
      ],
      volume_play_money: 10310.0,
      volume_real_money: 86.93272,
      is_following: false,
    },
    {
      id: 190096,
      title:
        "Which party will win Connecticut in the 2024 presidential election?",
      slug: "which-party-will-win-connecticut-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473604,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.97,
          },
          shares: {
            OOM: 43619.756200026844,
            BTC: 0.008723951046062318,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/19/12/1912ed71b07a30235a8072a0ee5f9330.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/aa/92aa899c00479d973d40b830fd958d5c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473605,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.69988591461,
            BTC: 0.007079539831358683,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/11/5c11403b4aa1adf6b9b1e7834992a776.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/4d/924dab6f92f48a91712956307b66eeb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473606,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43411567828,
            BTC: 0.006734486628741257,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/ae/77ae0584b3cb5b788ed4af60ce7139e7.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/e1/1fe106d1f797f86f866b540abc0485e6.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 0,
      wagers_count_total: 0,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190104,
      title: "Which party will win New York in the 2024 presidential election?",
      slug: "which-party-will-win-new-york-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473628,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.98,
          },
          shares: {
            OOM: 45469.1424512631,
            BTC: 0.008988215945316506,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/06/fd/06fd1a6c3d315f89c6db037bea4cf862.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/fa/91fa25a131d39da5cdb775abebfb9aae.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/94/1b/941bb30271936761f802810d8fbffb96.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d3/f8/d3f8b6261da086c349ef441ca416ff90.webp",
        },
        {
          id: 473629,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168044180486,
            BTC: 0.006979233615147038,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/09/60/096088990930e43fa2578724dd52fe66.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6e/ef/6eef0b60c8ad0b9e56e41c6f318c78b9.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/07/03/070350da68dfff8c2ddd7ad05735bc65.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e4/14/e414905d8829b263b9f0902c32a3b81b.webp",
        },
        {
          id: 473630,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168044180486,
            BTC: 0.006979233615147038,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/13/fd/13fd67daaaa3da7049afdf72c6f619c4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/64/9164a736e835a047cb8d7abf677006e1.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/15/78/1578b6b0facdfcf449a2f41ed5ace192.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/04/a8/04a83980c8afd8f81d34e51f874987e8.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [],
      volume_play_money: 10600.0,
      volume_real_money: 87.93007252,
      is_following: false,
    },
    {
      id: 190054,
      title: "SAG Awards winner for Best Actress in a Series (Comedy)",
      slug: "sag-awards-winner-for-best-actress-in-a-series-comedy",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473476,
          title: "Ayo Edebiri, The Bear",
          disabled: false,
          price: {
            OOM: 0.61,
            BTC: 0.62,
          },
          shares: {
            OOM: 97352.28187697755,
            BTC: 0.01950808673966084,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1b/c7/1bc74910a26a6b19917e5ff7a89fa7f0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/ff/f2ff70c37bbb21b71276004ce9bc5bf7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473475,
          title: "Quinta Brunson, Abbott Elementary",
          disabled: false,
          price: {
            OOM: 0.16,
            BTC: 0.15,
          },
          shares: {
            OOM: 92516.9311070242,
            BTC: 0.018503382182245582,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/26/7d/267d98d2f62cfa6c975bfe100968e2ce.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/35/d4/35d4aeae0dceb906dba46a6c8d437b8e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473477,
          title: "Hannah Waddingham, Ted Lasso",
          disabled: false,
          price: {
            OOM: 0.1,
            BTC: 0.09,
          },
          shares: {
            OOM: 90792.79038037342,
            BTC: 0.018158554531669525,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/21/5a/215add2cd7a8ea2f5d12c3f94c3d7a00.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/30/04/3004f6a459d193dcd5da64d163cbb2a4.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473474,
          title: "Rachel Brosnahan, The Marvelous Mrs. Maisel",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.08,
          },
          shares: {
            OOM: 89950.01818441489,
            BTC: 0.017989999143398168,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c3/d5/c3d55e80c5111aac5f3a52108009ecc9.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fb/97/fb9775c2d3f6ef6c886c4c67405df40a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473473,
          title: "Alex Borstein, The Marvelous Mrs. Maisel",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.06,
          },
          shares: {
            OOM: 88829.65158235005,
            BTC: 0.017765925884141463,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/a4/f5a48b50138f6d584aefa8f9941263e0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/48/3e/483e94e8dab2fe11557eb3b3a8b93379.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [],
      volume_play_money: 10110.0,
      volume_real_money: 89.17572002,
      is_following: false,
    },
    {
      id: 187335,
      title:
        "What will be the Brazilian basic interest rate at the end of 2024?",
      slug: "what-will-be-the-brazilian-basic-interest-rate-at-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1308,
          title: "Economic Indicators",
          slug: "economic-indicators",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1310,
          title: "Brazil",
          slug: "Brazil",
          parent: 1308,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1569,
          title: "Interest rate",
          slug: "interest-rate",
          parent: 1310,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 466702,
          title: "Less than 8.5%",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.24,
          },
          shares: {
            OOM: 139991.745411682,
            BTC: 0.027115860871121555,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/78/ce/78cefc612d42be1d644e657054980679.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f9/99/f999c72a11a5c7b661ed0663e8c6850a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466705,
          title: "Between 9.5% and 9.9%",
          disabled: false,
          price: {
            OOM: 0.32,
            BTC: 0.13,
          },
          shares: {
            OOM: 139187.94619403774,
            BTC: 0.026713742385042077,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6a/e7/6ae72a06e1fb3ee614be0d691547128b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bc/69/bc69577bb03b3eca98663789d1803bef.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466704,
          title: "Between 9.0% and 9.4%",
          disabled: false,
          price: {
            OOM: 0.14,
            BTC: 0.37,
          },
          shares: {
            OOM: 136279.02917220187,
            BTC: 0.027414675627393918,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/a3/7fa3360c9ae4402d492f573b09b6d2f0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/93/3e/933eeaa99c3eece0abcd60206593aa6b.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466706,
          title: "10% or more",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.02,
          },
          shares: {
            OOM: 134435.57291820002,
            BTC: 0.02531431969522252,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/f5/f5f57cca6a73678a39be21981ef72372.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f0/5f/f05f5355b2a5d08a3240223ddca62064.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 466703,
          title: "Between 8.5% and 8.9%",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.23,
          },
          shares: {
            OOM: 133274.55037115444,
            BTC: 0.027090751590525278,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/33/dc/33dc69dda060ea21ff6e11860ea2baaa.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/63/b76342b679d2d32912707847c2543209.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-11T10:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 9,
      wagers_count_canonical: 8,
      wagers_count_total: 17,
      wagers: null,
      tags: [
        {
          slug: "interest-rate",
          name: "Interest rate",
        },
      ],
      volume_play_money: 16510.0,
      volume_real_money: 118.94904784,
      is_following: false,
    },
    {
      id: 190105,
      title:
        "Which party will win Rhode Island in the 2024 presidential election?",
      slug: "which-party-will-win-rhode-island-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473631,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.98,
          },
          shares: {
            OOM: 45064.09666969168,
            BTC: 0.00897224232936561,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ee/ce/eeceb5746f37e27e4266a9d683850549.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/dd/79dd9e37fc337d5fea793ec98c32efa7.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/91/7f9109828801236cf36859e043719cb9.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/85/be852525e7e37195ae2676211f12bb2d.webp",
        },
        {
          id: 473632,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.16847757407,
            BTC: 0.0069792335983711465,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/86/63/8663187d5de9aeafbb436bbac5c4d251.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5b/d9/5bd9806e1ad9e004a1e4bc50329b1e69.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/ab/afab9b00e7425364630084d81847f728.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ae/1e/ae1e447b2fda2f13fdb3234e86fccf1d.webp",
        },
        {
          id: 473633,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.16847757407,
            BTC: 0.0069792335983711465,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/18/c5/18c5ee14da3fcfab95a1e42ec4a29b3d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/0c/970c6e05644d8992b85e00b636a9756d.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/42/9a/429ab3617bfaf35e3da4a54097043ca1.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/8a/628a064dd39f7e50855f846bce4b48be.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10200.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190128,
      title: "Will Ecuadorian authorities capture Fito by the end of February?",
      slug: "will-ecuadorian-authorities-capture-fito-by-the-end-of-february",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 584,
          title: "Ecuador",
          slug: "ecuador",
          parent: 105,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473680,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.68,
            BTC: 0.74,
          },
          shares: {
            OOM: 102160.25856415549,
            BTC: 0.020943728606335213,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4c/e4/4ce4fa5e83bd5b5943ce33fcc534006a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ec/88/ec887d078215e4b6d8e7cf19d09698c5.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473679,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.32,
            BTC: 0.26,
          },
          shares: {
            OOM: 95321.96819873585,
            BTC: 0.01906439363825679,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/03/ea/03ea6dab0a8ab619012537be55923c68.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/60/d860ffe0860005903dca979acf701bda.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-25T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 4,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 10010.0,
      volume_real_money: 102.81759621,
      is_following: false,
    },
    {
      id: 190041,
      title: "SAG Awards winner for Best Film Cast",
      slug: "sag-awards-winner-for-best-film-cast",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473412,
          title: "Oppenheimer",
          disabled: false,
          price: {
            OOM: 0.49,
            BTC: 0.58,
          },
          shares: {
            OOM: 102474.2483052974,
            BTC: 0.02070492457852198,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/00/b9/00b955964d9b4080d3c61f5edd49cc4f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/91/ea/91ea546e7e4ddaf355dc31145e484f2d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473409,
          title: "Barbie",
          disabled: false,
          price: {
            OOM: 0.26,
            BTC: 0.15,
          },
          shares: {
            OOM: 100141.42432872241,
            BTC: 0.019686143357932333,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/27/ff27e6e4f745a59ab05931f324a4b5fe.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/31/f5312292fed7e10a9b5dd18040bd1ff7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473408,
          title: "American Fiction",
          disabled: false,
          price: {
            OOM: 0.11,
            BTC: 0.1,
          },
          shares: {
            OOM: 96652.5778552788,
            BTC: 0.01933051557105576,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/04/3f0434c4b18673db39ab9f4b343c3293.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f8/80/f880101745a83ecf6c4c748b7875616c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473411,
          title: "Killers of the Flower Moon",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.1,
          },
          shares: {
            OOM: 95506.31900494074,
            BTC: 0.019329955566166748,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bc/51/bc512d18a41da015101c5efc46172f5a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7a/ac/7aac7d00b98c187548646e00e8f70da0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473410,
          title: "The Color Purple",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.08,
          },
          shares: {
            OOM: 94474.29002494394,
            BTC: 0.019183743279140887,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/07/97070f65a9d77c8d6647d9bda06ac0c0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ef/f9/eff9a5dfa92a73cdc2c42aad9838ff19.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 3,
      wagers_count_total: 8,
      wagers: null,
      tags: [],
      volume_play_money: 10510.0,
      volume_real_money: 96.17636064,
      is_following: false,
    },
    {
      id: 190106,
      title: "Which party will win Vermont in the 2024 presidential election?",
      slug: "which-party-will-win-vermont-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473634,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.94,
            BTC: 0.98,
          },
          shares: {
            OOM: 45521.87475417447,
            BTC: 0.008972242339785271,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ca/72/ca721be4628fbc87264276212ab48b56.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c9/43/c943106de7c25765ff228d5647bb997d.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/ca/5ccad06854e26377dd8965ad2689a603.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/83/14/8314f6ccd3a1dfcde95a66a611422075.webp",
        },
        {
          id: 473635,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.05,
            BTC: 0.01,
          },
          shares: {
            OOM: 39059.72559106064,
            BTC: 0.00697923360884658,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7c/4f/7c4f53ad1b2c384f6f310bc5d277b000.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/43/f44391645c54956440b22714bb72b7ce.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/12/65/12650807b1d3f0d170b581609b13a1cc.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b0/0f/b00f507c01eb10a30c701ab8ec5ca9dc.webp",
        },
        {
          id: 473636,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 34896.168018169636,
            BTC: 0.00697923360884658,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/00/a8/00a8dcb561f88f7dcefd1097979a1dce.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/d3/71d3caa8786c03866a0532baf1fb134f.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/34/35/34353d07b2e37647cca4b6632547e02f.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4c/32/4c32e60af8c05e106940faf7e2068118.webp",
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10750.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190199,
      title:
        "What will be the price of a gallon of gasoline in the US at the end of February?",
      slug: "what-will-be-the-price-of-a-gallon-of-gasoline-in-the-us-at-the-end-of-february",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 2066,
          title: "Commodities",
          slug: "commodities",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2232,
          title: "Oil",
          slug: "oil",
          parent: 2066,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473857,
          title: "Between 3.100 and 3.300",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.58,
          },
          shares: {
            OOM: 98333.61824125012,
            BTC: 0.019663786786311387,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ed/68/ed68d57aa6ec6d61d36f4809aeb3c162.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/57/99/5799f7480c97ec7e4221d402cc0c66e9.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/0b/1f0b714595a3a13eba73fe1ab1100910.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/cf/a9/cfa9adf5f88000383fe056d8c029b541.webp",
        },
        {
          id: 473859,
          title: "More than 3.300",
          disabled: false,
          price: {
            OOM: 0.21,
            BTC: 0.28,
          },
          shares: {
            OOM: 92476.87878737628,
            BTC: 0.018889571424653546,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/69/df/69dfb9430d357d783fb476d0c8cdb0bb.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/bb/76/bb76d81f6976bfd05b450c5b88db6991.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/98/5c98af7903325598af02e932790c6889.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/8b/628b06e7cff788b2910de4cc05baee19.webp",
        },
        {
          id: 473858,
          title: "Less than 3.100",
          disabled: false,
          price: {
            OOM: 0.15,
            BTC: 0.14,
          },
          shares: {
            OOM: 90404.17636973149,
            BTC: 0.018080834393496108,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/41/d6/41d6d9626b488f52cdf8cfb1ec154c40.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/65/8d/658d6e6a82c1d205a82908801f7c27c2.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/51/64/516456e109212209864ba50f85eb1b22.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/99/8b/998b823907a461b5737f0510d9390b43.webp",
        },
      ],
      bet_end_date: "2024-02-26T06:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 1,
      wagers_count_total: 3,
      wagers: null,
      tags: [
        {
          slug: "inflation",
          name: "Inflation",
        },
      ],
      volume_play_money: 10110.0,
      volume_real_money: 91.21424,
      is_following: false,
    },
    {
      id: 190192,
      title:
        "Will a second ceasefire be initiated in Gaza by the end of February 2024?",
      slug: "will-a-second-ceasefire-be-initiated-in-gaza-by-the-end-of-january-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 108,
          title: "Middle East",
          slug: "middle-east",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3484,
          title: "Palestine",
          slug: "palestine",
          parent: 108,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473833,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.6,
            BTC: 0.83,
          },
          shares: {
            OOM: 73607.95207751395,
            BTC: 0.017971382110329268,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f8/bf/f8bff12e7801c6ae187a21eb83cb88cb.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/f4/f2f4fea20d659eba48299270a9b78503.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473834,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.4,
            BTC: 0.17,
          },
          shares: {
            OOM: 70972.39072421704,
            BTC: 0.015530830644389572,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a4/4a/a44a7cb1f0cd4ca6dfb0af3fd55de026.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/3c/b53c776df30886076d19e5ed37bd3e95.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-29T00:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 11,
      wagers_count_total: 15,
      wagers: null,
      tags: [
        {
          slug: "israel",
          name: "Israel",
        },
        {
          slug: "israel-hamas-war",
          name: "Israel-Hamas War",
        },
      ],
      volume_play_money: 12140.0,
      volume_real_money: 122.97798837,
      is_following: false,
    },
    {
      id: 190076,
      title: "Which party will win Iowa in the 2024 presidential election?",
      slug: "which-party-will-win-iowa-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3558,
          title: "Swing States",
          slug: "swing-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473545,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.9,
            BTC: 0.96,
          },
          shares: {
            OOM: 40310.359110360434,
            BTC: 0.008134786339529349,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/b8/f4b860e5556ac87ff4899bb6354f1c81.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/3a/813a447b27d46396271886308d180d74.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473544,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.04,
          },
          shares: {
            OOM: 35864.081492766265,
            BTC: 0.006875742922755695,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/5c/dd5ca4ede707bc4c0467b8b7259e5143.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/db/ec/dbec8e3d42651637e103c268918df018.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473546,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 29195.84752783412,
            BTC: 0.00583916957892365,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/18/711821f8da62b21a98c69d9c23b498d3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ef/16/ef166bc6ca93b2742b99b03d0791425c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 1,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 11300.0,
      volume_real_money: 103.1777043,
      is_following: false,
    },
    {
      id: 186441,
      title:
        "Will the Premier League punish Manchester City for violating financial fairplay in the 23/24 season?",
      slug: "will-the-premier-league-punish-manchester-city-for-violating-financial-fairplay-in-the-2324-season",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 32,
          title: "England",
          slug: "england",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464549,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.67,
            BTC: 0.39,
          },
          shares: {
            OOM: 194601.01600683905,
            BTC: 0.03648651513242826,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f3/8a/f38a0df59df31b2f15e010dc8aadcb55.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b1/8d/b18d14f77414d37b236b5e3c1a0f3b38.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 464548,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.33,
            BTC: 0.61,
          },
          shares: {
            OOM: 186569.26976879698,
            BTC: 0.037523526736022314,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9e/19/9e19b681fcfa2d9f60a27c3d9320df01.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/80/6c/806caeb42543f1fcc4aa6230dcf1e9c7.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-31T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 26,
      wagers_count_canonical: 9,
      wagers_count_total: 35,
      wagers: null,
      tags: [],
      volume_play_money: 19157.21,
      volume_real_money: 117.45559139,
      is_following: false,
    },
    {
      id: 186448,
      title:
        "Will Xabi Alonso continue to work as manager of Leverkusen through the end of the 23/24 season?",
      slug: "will-xabi-alonso-continue-to-work-as-manager-of-leverkusen-through-the-end-of-the-2324-season",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 13,
          title: "Germany",
          slug: "germany",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464562,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.75,
            BTC: 0.84,
          },
          shares: {
            OOM: 240528.37470045977,
            BTC: 0.04564804195323253,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/8d/4b/8d4b92c7af9775acaa29ca48fbb12a5f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1d/ae/1dae53d83d4aa100416a595360301961.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/75/96/75961e7c77333d22237180e2c1a1d850.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/79/3f7997331ad3f2bd53b84a320d610248.webp",
        },
        {
          id: 464563,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.25,
            BTC: 0.16,
          },
          shares: {
            OOM: 225007.7892550557,
            BTC: 0.04141696112414745,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0f/8d/0f8d8172aa97c0d51b248fad3dd0575e.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/08/8f/088f25642b3112c94ad6b8b27d786749.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-31T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 65,
      wagers_count_canonical: 10,
      wagers_count_total: 75,
      wagers: null,
      tags: [],
      volume_play_money: 35284.02,
      volume_real_money: 308.55029661,
      is_following: false,
    },
    {
      id: 190058,
      title: "Which party will win Arkansas in the 2024 presidential election?",
      slug: "which-party-will-win-arkansas-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473491,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.98,
            BTC: 0.97,
          },
          shares: {
            OOM: 44739.96507985771,
            BTC: 0.008723951215007639,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d7/93/d79310f708b8a819438a6a791f319bbf.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/8d/d88d093853b404f9c30dfbad9776c40d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473492,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43741313089,
            BTC: 0.006734486798087227,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c6/2d/c62d281e0d410c111242932277c0da31.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5c/80/5c805b73d05bdf70dcff0c7b17b54111.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473490,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.70222867107,
            BTC: 0.007079539958754622,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/84/be84ba63e84a76e3d2c9090a9d24d95f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1f/81/1f81a66ed20ec4b55603bc183cf1b633.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 11100.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190057,
      title: "Which party will win Alabama in the 2024 presidential election?",
      slug: "which-party-will-win-alabama-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473488,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.96,
          },
          shares: {
            OOM: 43619.75857464054,
            BTC: 0.010908660755784427,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7e/4c/7e4c77ec34c9713d019f18f1dfae3ee1.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/51/30/51301a4b3493f9d266180f61a3371e85.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473487,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.03,
          },
          shares: {
            OOM: 35397.70152691932,
            BTC: 0.009234693880437296,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f2/bb/f2bb9e10b8989f5ffb50849cdc3bdedc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/8f/7d8fee8838897681da94326ceca79856.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473489,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43649287858,
            BTC: 0.006734487185980464,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/17/35/1735ab18ea11113ea22dea17dc762e4f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/9a/b59a78f5df9bb40e4451f1dca6a6d9bf.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 2,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 182.4843162,
      is_following: false,
    },
    {
      id: 190195,
      title: "Will the US government shut down in March?",
      slug: "will-the-us-government-shut-down-in-march",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 114,
          title: "Congress",
          slug: "congress",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473842,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.88,
            BTC: 0.76,
          },
          shares: {
            OOM: 107289.07083030652,
            BTC: 0.020545052582040122,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/43/f4/43f4907fcfe33f48e16e26fd18aee4d5.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/97/b6/97b6b2c975a8ceb1e6872daad7c0a986.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473843,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.12,
            BTC: 0.24,
          },
          shares: {
            OOM: 89595.65625944991,
            BTC: 0.018507033075836374,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/74/22/74220dcbf22addeb10d2377df379e211.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/f1/b4f1c28b5cb01f7cf068ba1308cf7673.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-01T00:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 4,
      wagers_count_total: 7,
      wagers: null,
      tags: [
        {
          slug: "shutdown",
          name: "Shutdown",
        },
      ],
      volume_play_money: 18700.0,
      volume_real_money: 107.55244862,
      is_following: false,
    },
    {
      id: 190715,
      title: "Kawasaki Frontale vs. Shandong Taishan",
      slug: "kawasaki-frontale-vs-shandong-taishan",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 65,
          title: "International",
          slug: "international",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 265,
          title: "Afc Champions League",
          slug: "afc-champions-league",
          parent: 65,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 475058,
          title: "Kawasaki Frontale",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.64,
          },
          shares: {
            OOM: 94475.19110689206,
            BTC: 0.018895039435863594,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/b6/beb621143348fdbd6dd8247682c1deb3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d7/b1/d7b191ffefadd7d204fe844a67fd0e2e.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/88/6288a4bb9830c753c120395b3c821c7d.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e3/fd/e3fd63b654a7917af725d1cabc7d1235.webp",
        },
        {
          id: 475060,
          title: "Tie",
          disabled: false,
          price: {
            OOM: 0.22,
            BTC: 0.22,
          },
          shares: {
            OOM: 88992.48423871474,
            BTC: 0.017798497982852178,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e6/1d/e61d1226928f679e6f42d3091ebcece6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/78/79789e9771c0786e5256f1823a1619c0.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/74/8c/748c46c57696e25fc29c84c64b53d25e.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b5/6d/b56d772b5d6f0d4f28241669ab4053d1.webp",
        },
        {
          id: 475059,
          title: "Shandong Taishan",
          disabled: false,
          price: {
            OOM: 0.14,
            BTC: 0.14,
          },
          shares: {
            OOM: 86557.9074798944,
            BTC: 0.017311582756931728,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/89/0e/890e35789e420eb41345897c48566b68.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/06/2e/062ed78ef6349bd7e78cead03aa1579f.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/39/18/39184c52efebb70ffaa7b7a4b9f0ca0e.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/5a/36/5a36a07e3d50bd8a23493fce9aeeaa08.webp",
        },
      ],
      bet_end_date: "2024-02-20T08:00:00Z",
      event_start_date: "2024-02-20T08:00:00Z",
      event_end_date: "2024-02-20T09:45:00Z",
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 0,
      wagers_count_total: 0,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 85.60766,
      is_following: false,
    },
    {
      id: 190101,
      title: "Which party will win Oregon in the 2024 presidential election?",
      slug: "which-party-will-win-oregon-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473619,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.92,
            BTC: 0.97,
          },
          shares: {
            OOM: 43718.72463930023,
            BTC: 0.008723951183554433,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ea/47/ea47c6c4ab5676ca4ef77d94f44c9273.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/19/62/1962048f40a956ee245adc55a462dba9.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473620,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.02,
          },
          shares: {
            OOM: 38003.8830244257,
            BTC: 0.007079539921055864,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/68/f0/68f00f5080831b157b28f4780e2434e6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/7c/3f7c701b4c50a504da84088d003f7cb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473621,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33669.095818192436,
            BTC: 0.006734486766274902,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/57/88/57881faf1263847d661bcdc555730c5c.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d6/8b/d68bd7050b6f4254221bc9c89119323e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 2,
      wagers_count_canonical: 0,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10200.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 190052,
      title: "SAG Awards winner for Best Actress in a Series (Drama)",
      slug: "sag-awards-winner-for-best-actress-in-a-series-drama",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473467,
          title: "Sarah Snook, Succession",
          disabled: false,
          price: {
            OOM: 0.58,
            BTC: 0.66,
          },
          shares: {
            OOM: 97963.83413473141,
            BTC: 0.019624926256438968,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/16/fc/16fc40e6cad198129987b8828638ce48.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/38/c8/38c843b06eeb6ca9cd92c75cf48239eb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473464,
          title: "Elizabeth Debicki, The Crown",
          disabled: false,
          price: {
            OOM: 0.16,
            BTC: 0.12,
          },
          shares: {
            OOM: 93409.51630368773,
            BTC: 0.01842824225896014,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/54/96/54964989fd0dac3bc06ec59571144b32.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dc/29/dc29b7694e922fe24d826fd8d9bf44c1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473466,
          title: "Keri Russell, The Diplomat",
          disabled: false,
          price: {
            OOM: 0.1,
            BTC: 0.06,
          },
          shares: {
            OOM: 91656.99948326458,
            BTC: 0.01784385082539961,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fe/4b/fe4b6b5e4ce023b38506c4636d7eb4ad.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/78/44/78448ad4da9673b29bb41638cbc11fee.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473465,
          title: "Bella Ramsey, The Last of Us",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.08,
          },
          shares: {
            OOM: 90779.45266863746,
            BTC: 0.01815588613355777,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ad/1c/ad1cdc464e5a3ca8a72c883ab7185518.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/01/40/0140d991009d2bb4ea5b2b7f6b48e7a9.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473463,
          title: "Jennifer Aniston, The Morning Show",
          disabled: false,
          price: {
            OOM: 0.07,
            BTC: 0.08,
          },
          shares: {
            OOM: 90333.84428953128,
            BTC: 0.018066764250753167,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/0d/ff0dba1aad1b5ae64e6318c3a826fd25.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a5/00/a500edf7c2a7ea7e48bad09ed13e8eb0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 6,
      wagers_count_canonical: 1,
      wagers_count_total: 7,
      wagers: null,
      tags: [],
      volume_play_money: 10510.0,
      volume_real_money: 89.17572002,
      is_following: false,
    },
    {
      id: 190099,
      title:
        "Which party will win Massachusetts in the 2024 presidential election?",
      slug: "which-party-will-win-massachusetts-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473613,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.93,
            BTC: 0.97,
          },
          shares: {
            OOM: 43935.97852315655,
            BTC: 0.008723951242840497,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/d3/81d3bf7cf63f778a5e1a1763b44800c6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ac/db/acdb1fd64001705becf0930fc895b575.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473614,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.02,
          },
          shares: {
            OOM: 38007.38152905486,
            BTC: 0.007079539972607898,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9d/45/9d453856d96606efde8fa023254ba7a4.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/4d/624de8126ee15d35ffbaf8fc03a867d1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473615,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33673.32187678596,
            BTC: 0.006734486825840894,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e6/15/e615f81425f4cd08fa10f810031844b8.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/69/7d6935e139c5c803cc23e530169897e4.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 4,
      wagers_count_canonical: 0,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10400.0,
      volume_real_money: 87.25394,
      is_following: false,
    },
    {
      id: 181308,
      title:
        "Will Donald Trump announce his choice of running mate (vice president) before the Republican National Convention?",
      slug: "will-donald-trump-announce-his-choice-of-running-mate-vice-president-before-the-republican-national-convention",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3561,
          title: "Primaries",
          slug: "primaries",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3548,
          title: "Republican Primaries",
          slug: "republican-primaries",
          parent: 3561,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 452385,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.63,
            BTC: 0.7,
          },
          shares: {
            OOM: 49306.40545423327,
            BTC: 0.010697525748049564,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/38/d8388fb822623f02112e3327e3586bef.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/db/f5/dbf51b40819252f79e9e8bb6562ab717.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 452386,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.37,
            BTC: 0.3,
          },
          shares: {
            OOM: 46259.29167871124,
            BTC: 0.009635973080195271,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/20/5f/205fdcf188dca15e375fae5e11eb35d0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7c/cb/7ccb92948f55123780e997d94f7dd5a6.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-07-14T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 15,
      wagers_count_canonical: 6,
      wagers_count_total: 21,
      wagers: null,
      tags: [
        {
          slug: "donald-trump",
          name: "Donald Trump",
        },
        {
          slug: "elections",
          name: "Elections",
        },
      ],
      volume_play_money: 6540.96,
      volume_real_money: 78.22215958,
      is_following: false,
    },
    {
      id: 190098,
      title: "Which party will win Idaho in the 2024 presidential election?",
      slug: "which-party-will-win-idaho-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473610,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.13,
          },
          shares: {
            OOM: 43619.754741245495,
            BTC: 0.008807462624911415,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/38/e5/38e58f83ef07008862a04807ffd2fdb3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f4/44/f444aa6ea9870e2c6a6b3a40c8ed13c0.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473611,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.86,
          },
          shares: {
            OOM: 35397.69879873151,
            BTC: 0.009710547433102013,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/76/54/7654f95d743602d012133eb8494db005.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c5/7c/c57c465bec565869ff4e7146f3f78f0e.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473612,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.43265369867,
            BTC: 0.006734486756230058,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b1/ec/b1eca3bc42452dac55f8822d9a7dfc19.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/68/2e/682eb75e91b47c9c4f76ba67ef9da169.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 5,
      wagers_count_total: 6,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 132.8520713,
      is_following: false,
    },
    {
      id: 182110,
      title:
        "Will the fake news inquiry be closed in Brazil's Supreme Court by the end of 2024?",
      slug: "will-the-fake-news-inquiry-be-closed-in-brazils-supreme-court-by-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 106,
          title: "Brazil",
          slug: "brazil",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1364,
          title: "Judiciary",
          slug: "judiciary",
          parent: 106,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 454288,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.62,
            BTC: 0.69,
          },
          shares: {
            OOM: 154409.17417459982,
            BTC: 0.029755170065968582,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/62/38/6238661367fb8788f8d528367d5931c3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ec/59/ec595da9c0604e637afb3fcd9c031c41.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/28/5b/285b30d59fa2a030145a9bda27f92165.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ce/53/ce539449597cf11723b48d6fa6ac9971.webp",
        },
        {
          id: 454287,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.38,
            BTC: 0.31,
          },
          shares: {
            OOM: 149811.01571936192,
            BTC: 0.02835042063199287,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/4e/af4e19a609e237c1931393b8b8e8b4c6.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/90/92/9092ba2a56409ec106d2188c44485fe2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-23T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 34,
      wagers_count_canonical: 2,
      wagers_count_total: 36,
      wagers: null,
      tags: [
        {
          slug: "brazil",
          name: "Brazil",
        },
        {
          slug: "judiciary",
          name: "Judiciary",
        },
      ],
      volume_play_money: 16740.1,
      volume_real_money: 86.35559814,
      is_following: false,
    },
    {
      id: 190065,
      title: "Which party will win Utah in the 2024 presidential election?",
      slug: "which-party-will-win-utah-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473512,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.94,
            BTC: 0.94,
          },
          shares: {
            OOM: 50383.07487771923,
            BTC: 0.010045062560438979,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/90/fd/90fd71929dac9582a0f5eeb001f1391d.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/92/88/928874c1d01eeca3e42f5f2d745d5bd8.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473513,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.04,
            BTC: 0.04,
          },
          shares: {
            OOM: 42273.71873047387,
            BTC: 0.008454743953146333,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e2/40/e2405166b1f7000d5d81e68eb488daa3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/81/04/810466702f475bb6d220262b7576b04c.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473511,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 40292.38453623792,
            BTC: 0.008058477175181456,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3e/5e/3e5e73d38ffe9a55431e46840336901f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/9c/fb/9cfb4de3327429c2a40551535e107b5a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 2,
      wagers_count_total: 5,
      wagers: null,
      tags: [],
      volume_play_money: 10210.0,
      volume_real_money: 87.75993671,
      is_following: false,
    },
    {
      id: 190068,
      title:
        "Which party will win Louisiana in the 2024 presidential election?",
      slug: "which-party-will-win-louisiana-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473521,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.96,
            BTC: 0.97,
          },
          shares: {
            OOM: 42670.85692756078,
            BTC: 0.0098969523519606,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/64/ab/64ab9498b23485ef4b306310eaf24d8a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4b/41/4b41753dd2bf1219c696fa64f7ad4faa.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473520,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.03,
            BTC: 0.03,
          },
          shares: {
            OOM: 35468.0881146019,
            BTC: 0.008237876442169017,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d5/26/d526788da0194939abd9038747420ea3.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/72/b772401fa88a0c5b56ba6c7030c2b653.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473522,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 32741.29122682131,
            BTC: 0.00654826630312012,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/02/57/0257f5ca0b1b7b6ae0e0789fba7d0465.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ac/ed/aced199694e029fd148dd509c328d0c2.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 2,
      wagers_count_total: 2,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 146.4676872,
      is_following: false,
    },
    {
      id: 186420,
      title:
        "Will Brazil's Congress approve the end of reelection for the executive branch by the end of 2024?",
      slug: "will-brazils-congress-approve-the-end-of-reelection-for-the-executive-branch-by-the-end-of-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 106,
          title: "Brazil",
          slug: "brazil",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 1449,
          title: "Congress",
          slug: "congress",
          parent: 106,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464507,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.92,
            BTC: 0.7,
          },
          shares: {
            OOM: 205585.73372109066,
            BTC: 0.03672024990096394,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/4a/7f4a979b5ac0b4bbcf94ee2cbb2d6be1.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/a4/96/a4968ccb6b558d0fb4d5f900972da761.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f1/f1/f1f143f0b66b771eda67616b81b0d59f.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/56/b656b59628c9d0d1a65862dc5b5b2505.webp",
        },
        {
          id: 464506,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.3,
          },
          shares: {
            OOM: 177466.12711811627,
            BTC: 0.03485527503428112,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/42/bc/42bc2abbe90e1db8f8eec764d676736b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/dd/2e/dd2ea8e3474a24d854adca9bdd961eb3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-12-01T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 45,
      wagers_count_canonical: 4,
      wagers_count_total: 49,
      wagers: null,
      tags: [
        {
          slug: "elections",
          name: "Elections",
        },
        {
          slug: "president",
          name: "President",
        },
      ],
      volume_play_money: 32145.0,
      volume_real_money: 110.51829205,
      is_following: false,
    },
    {
      id: 190027,
      title: "Oscar winner for Best Original Screenplay",
      slug: "oscar-winner-for-best-original-screenplay",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3563,
          title: "Oscars 2024",
          slug: "oscars-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473338,
          title: "Anatomy of a Fall",
          disabled: false,
          price: {
            OOM: 0.45,
            BTC: 0.58,
          },
          shares: {
            OOM: 46917.76265933138,
            BTC: 0.009628474855797844,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0f/ca/0fca831c0f0e2b54a19d4e8ceb94d5bc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c6/56/c6564457222355a4910df02d43b78e1a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473339,
          title: "The Holdovers",
          disabled: false,
          price: {
            OOM: 0.41,
            BTC: 0.32,
          },
          shares: {
            OOM: 46779.857348251164,
            BTC: 0.009430313741559913,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d1/ef/d1efd9a6b4154c5682be121e5aa20d35.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f5/10/f5102f8233a86e63beef45d11c09055a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473342,
          title: "Past Lives",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.06,
          },
          shares: {
            OOM: 44277.25984288743,
            BTC: 0.008858416279059549,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/20/3f/203f3025429171b4a11bd4d017c3b4ec.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/34/a6/34a61fd3d16eed48cdc70032df472cda.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473341,
          title: "May December",
          disabled: false,
          price: {
            OOM: 0.04,
            BTC: 0.02,
          },
          shares: {
            OOM: 42430.224278812544,
            BTC: 0.008488853129358938,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ff/95/ff95219fd51fd0bfc0a868771de11e7f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/71/5d/715daafc1fd10fbd4b44c8d5e337f9e3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473340,
          title: "Maestro",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 37474.96362302919,
            BTC: 0.007498183898668283,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/49/46/494651b9e36a76b09735484d5901f846.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ea/e3/eae3bb094b1fa84f2e2137112c208a34.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-10T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 5,
      wagers_count_canonical: 5,
      wagers_count_total: 10,
      wagers: null,
      tags: [],
      volume_play_money: 10710.0,
      volume_real_money: 100.39073237,
      is_following: false,
    },
    {
      id: 190078,
      title: "Which party will win Florida in the 2024 presidential election?",
      slug: "which-party-will-win-florida-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3558,
          title: "Swing States",
          slug: "swing-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473551,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.9,
            BTC: 0.96,
          },
          shares: {
            OOM: 38391.37576905767,
            BTC: 0.008654843208579574,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/3f/01/3f01f3cb6114c2d69cf17436308ce44b.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/6f/19/6f191bbf941b3a571059e359bcacb759.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473550,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.09,
            BTC: 0.04,
          },
          shares: {
            OOM: 34048.272723508635,
            BTC: 0.007332830340290171,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/4d/55/4d554c538d00d8fd81ad9ce09463a14f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/85/43/854359c7393f1342c14107a59291c0a1.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473552,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 28564.976048549306,
            BTC: 0.00571299649615047,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ad/b0/adb0e4cbbb25b449c86c8e9f1830bb17.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1b/8e/1b8e77120fdd623f472465e7289a3726.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 1,
      wagers_count_canonical: 3,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 135.90610082,
      is_following: false,
    },
    {
      id: 190059,
      title: "Which party will win Kentucky in the 2024 presidential election?",
      slug: "which-party-will-win-kentucky-in-the-2024-presidential-election",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 99,
          title: "USA",
          slug: "usa",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 2544,
          title: "2024 Elections",
          slug: "2024-elections",
          parent: 99,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3551,
          title: "Presidency",
          slug: "presidency",
          parent: 2544,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3557,
          title: "Results",
          slug: "results",
          parent: 3551,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3559,
          title: "Other States",
          slug: "other-states",
          parent: 3557,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473494,
          title: "Republican",
          disabled: false,
          price: {
            OOM: 0.97,
            BTC: 0.97,
          },
          shares: {
            OOM: 43619.756706771026,
            BTC: 0.008724378020053956,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/48/e9/48e966b9c758032081b4240cddd35419.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/11/9f/119fcf69d440673d9dcfddb042e5780b.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473493,
          title: "Democratic",
          disabled: false,
          price: {
            OOM: 0.02,
            BTC: 0.02,
          },
          shares: {
            OOM: 35397.700232019735,
            BTC: 0.0070795397181776226,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/be/83/be8392703c1a301e67a954b3079f1809.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/16/59/1659bfe5700f47998ea3b195e0e143bf.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473495,
          title: "Other",
          disabled: false,
          price: {
            OOM: 0.01,
            BTC: 0.01,
          },
          shares: {
            OOM: 33672.434622891284,
            BTC: 0.006734486463398948,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/e9/77e95d2669798e38486b1156c9d5273a.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7f/7a/7f7ace16ccf2c9702a5cba8feed5e5f5.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-11-05T18:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 0,
      wagers_count_canonical: 1,
      wagers_count_total: 1,
      wagers: null,
      tags: [],
      volume_play_money: 10000.0,
      volume_real_money: 87.27191843,
      is_following: false,
    },
    {
      id: 186146,
      title: "Will Shein go public in the US by July 1st, 2024?",
      slug: "will-shein-go-public-in-the-us-by-july-1st-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 123,
          title: "Business & Finance",
          slug: "business-finance",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 2129,
          title: "Big Companies",
          slug: "big-companies",
          parent: 123,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 463930,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.64,
            BTC: 0.62,
          },
          shares: {
            OOM: 178701.74423374844,
            BTC: 0.03546354891144956,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/20/af204403210c6d011f26f4783f3c0349.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/7d/3a/7d3ab1f941cab8221f3ca02f3ef8fdfb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 463929,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.36,
            BTC: 0.38,
          },
          shares: {
            OOM: 172752.9529500131,
            BTC: 0.03446248910383886,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d8/c3/d8c38bcf936ed98ebfd0eb0979d23651.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/d5/05/d5054bf2450353412f93a6c79e6ffbdd.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-06-24T08:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 15,
      wagers_count_canonical: 4,
      wagers_count_total: 19,
      wagers: null,
      tags: [
        {
          slug: "ipos",
          name: "IPOs",
        },
        {
          slug: "shein",
          name: "Shein",
        },
      ],
      volume_play_money: 13961.0,
      volume_real_money: 110.95714001,
      is_following: false,
    },
    {
      id: 190051,
      title: "SAG Awards winner for Best Actor in a Series (Drama)",
      slug: "sag-awards-winner-for-best-actor-in-a-series-drama",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 555,
          title: "Entertainment",
          slug: "entertainment",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 1500,
          title: "Awards",
          slug: "awards",
          parent: 555,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3564,
          title: "SAG Awards 2024",
          slug: "sag-awards-2024",
          parent: 1500,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 473460,
          title: "Kieran Culkin, Succession",
          disabled: false,
          price: {
            OOM: 0.55,
            BTC: 0.59,
          },
          shares: {
            OOM: 96483.73651634093,
            BTC: 0.019422809528817017,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/0c/e7/0ce7aa7f1559a079071fc2028fd0ea4c.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/65/64/6564f461fef7367aea974d0084f8150a.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473462,
          title: "Pedro Pascal, The Last of Us",
          disabled: false,
          price: {
            OOM: 0.21,
            BTC: 0.19,
          },
          shares: {
            OOM: 93088.58387031565,
            BTC: 0.018617706854483,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/24/5c/245c8734d22376c9d31eee20d44fbd84.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b7/70/b7703f0cb4c36d1c5cc54bfe6e174e3d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473461,
          title: "Matthew Macfadyen, Succession",
          disabled: false,
          price: {
            OOM: 0.11,
            BTC: 0.1,
          },
          shares: {
            OOM: 90896.14947289009,
            BTC: 0.018179220207193602,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/79/40/794029fdd9ab02c37421e374afc73127.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/f7/80/f7802f01ce3bfe02fae4a2a36cfc36fb.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473459,
          title: "Billy Crudup, The Morning Show",
          disabled: false,
          price: {
            OOM: 0.08,
            BTC: 0.07,
          },
          shares: {
            OOM: 89370.7372977866,
            BTC: 0.017874138889053197,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/43/46/434680f14adc122f4f7fe3759e5a88dc.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/1a/b3/1ab3f1679273747e567eaf2291565781.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 473458,
          title: "Brian Cox, Succession",
          disabled: false,
          price: {
            OOM: 0.06,
            BTC: 0.05,
          },
          shares: {
            OOM: 88241.13863516579,
            BTC: 0.017648217218272862,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/5d/b65dd8bb8814bbc0bb12965a5162d2a5.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/70/49/7049fd60d0ad86ae240dec9e44c2b3e3.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-02-24T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.06,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.06,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 3,
      wagers_count_canonical: 1,
      wagers_count_total: 4,
      wagers: null,
      tags: [],
      volume_play_money: 10210.0,
      volume_real_money: 92.17622011,
      is_following: false,
    },
    {
      id: 179586,
      title:
        "Will Bernardo Arévalo complete his Presidential term in Guatemala?",
      slug: "will-bernardo-arevalo-complete-his-presidential-term-in-guatemala",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 448197,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.53,
            BTC: 0.45,
          },
          shares: {
            OOM: 55741.31604405247,
            BTC: 0.010597473723417974,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/cc/a8/cca8218c9d977b89459e8a7eb72ac2e0.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e1/68/e168e2603b22d7d3f63e54a672d92430.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b0/1e/b01e48f6e0a21285316a929e638bfd2c.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/df/f0/dff00a7bd0702d7d8de326349072e5dc.webp",
        },
        {
          id: 448198,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.47,
            BTC: 0.55,
          },
          shares: {
            OOM: 54843.092243967534,
            BTC: 0.010865890800622667,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b9/0c/b90c9369c0132de83b7cb0f71f837999.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/da/7d/da7da0db586308ffd958a4d5475af9d3.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/29/c1/29c18c26fafe1809fa3ad7bbc5afd49a.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/2a/b6/2ab6cb0d262c74bb41752e4f0090e6e7.webp",
        },
      ],
      bet_end_date: "2028-01-13T23:59:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 8,
      wagers_count_canonical: 0,
      wagers_count_total: 8,
      wagers: null,
      tags: [
        {
          slug: "guatemala",
          name: "Guatemala",
        },
        {
          slug: "president",
          name: "President",
        },
        {
          slug: "term-duration-presidents-and-prime-ministers",
          name: "Term Duration - Presidents and Prime Ministers",
        },
      ],
      volume_play_money: 6744.0,
      volume_real_money: 42.9991,
      is_following: false,
    },
    {
      id: 186443,
      title: "Will Haaland be the top scorer in the 23/24 Premier League?",
      slug: "will-haaland-be-the-top-scorer-in-the-2324-premier-league",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 7,
          title: "Sports",
          slug: "sports",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 8,
          title: "Football",
          slug: "football",
          parent: 7,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 32,
          title: "England",
          slug: "england",
          parent: 8,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 464552,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.82,
            BTC: 0.76,
          },
          shares: {
            OOM: 178001.25004058407,
            BTC: 0.0320483799247418,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b6/c3/b6c3464fb0b0e9f0c1b48ff11e251b64.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/e7/00/e700a6507851e60e4939c0c4b2a78c0f.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
        {
          id: 464553,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.18,
            BTC: 0.24,
          },
          shares: {
            OOM: 162641.87149203703,
            BTC: 0.029932048090720236,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/77/26/772687705ac4b8ab98b48ffc3cc7c065.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/58/df/58df6947b2f50e3a32f055216b839145.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-04-30T12:00:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.04,
            bounded_loss: 0.002,
          },
          OOM: {
            tax: 0.04,
            bounded_loss: 10000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 58,
      wagers_count_canonical: 8,
      wagers_count_total: 66,
      wagers: null,
      tags: [],
      volume_play_money: 33703.17,
      volume_real_money: 145.31925346,
      is_following: false,
    },
    {
      id: 178461,
      title:
        "Will Ecuador improve its score in the Democracy Report ranking in 2024?",
      slug: "will-ecuador-improve-its-score-in-the-democracy-report-ranking-in-2024",
      status: "o",
      status_display: "open",
      category: [
        {
          id: 98,
          title: "Politics",
          slug: "politics",
          parent: null,
          in_leaderboard: true,
          icon: null,
        },
        {
          id: 105,
          title: "Latin America",
          slug: "latin-america",
          parent: 98,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 584,
          title: "Ecuador",
          slug: "ecuador",
          parent: 105,
          in_leaderboard: false,
          icon: null,
        },
        {
          id: 3444,
          title: "Democracy Ranking",
          slug: "democracy-ranking",
          parent: 584,
          in_leaderboard: false,
          icon: null,
        },
      ],
      outcomes: [
        {
          id: 445281,
          title: "No",
          disabled: false,
          price: {
            OOM: 0.58,
            BTC: 0.58,
          },
          shares: {
            OOM: 52266.44958690435,
            BTC: 0.010331750088493479,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/fd/1a/fd1a63d1e1cca2847229890e61f1c68f.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/b4/98/b498510bea7ae51f8a753aa68390c78b.jpg",
          thumbnail_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/ce/94/ce94ca6d0f295891438bced72a5c5cad.webp",
          image_2x_webp:
            "https://My_Company-media-production.s3.amazonaws.com/cache/32/ed/32ed1ff21b802050f1953de2c9676314.webp",
        },
        {
          id: 445280,
          title: "Yes",
          disabled: false,
          price: {
            OOM: 0.42,
            BTC: 0.42,
          },
          shares: {
            OOM: 50119.96713331726,
            BTC: 0.009934120943054594,
          },
          thumbnail_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/af/9b/af9b0318ab5ef822118228688b2a0ce7.jpg",
          image_2x:
            "https://My_Company-media-production.s3.amazonaws.com/cache/c0/16/c0160617ef158bb172a192aceb016f2d.jpg",
          thumbnail_2x_webp: null,
          image_2x_webp: null,
        },
      ],
      bet_end_date: "2024-03-01T00:01:00Z",
      event_start_date: null,
      event_end_date: null,
      is_wagerable: true,
      available_currencies: ["OOM", "BTC"],
      scoring_rule: "lslmsr",
      scoring_rule_metadata: {
        lmsr: {
          BTC: {
            liquidity_param: 1000,
          },
          OOM: {
            liquidity_param: 1000,
          },
        },
        lslmsr: {
          BTC: {
            tax: 0.08,
            bounded_loss: 0.001,
          },
          OOM: {
            tax: 0.08,
            bounded_loss: 5000,
          },
        },
      },
      resolution: null,
      resolve_date: null,
      brier_score: null,
      brier_score_play_money: null,
      brier_score_real_money: null,
      real_currency_available: true,
      wagers_count: 8,
      wagers_count_canonical: 2,
      wagers_count_total: 10,
      wagers: null,
      tags: [
        {
          slug: "v-dem-liberal-democracy-ranking",
          name: "V-Dem Liberal Democracy Ranking",
        },
      ],
      volume_play_money: 5715.0,
      volume_real_money: 43.71993403,
      is_following: false,
    },
  ];
}
