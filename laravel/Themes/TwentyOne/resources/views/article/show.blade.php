<!-- container -->
<div class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12">
  <!-- play money markets -->
  <section class="pt-8" aria-label="Play Money Markets" {{-- x-data="playmarkets"id="playmarkets" --}}>
    <!-- list of markets -->
    <div class="flex flex-wrap lg:flex-nowrap w-full ">

      @include('pub_theme::article.show.content')

      @include('pub_theme::article.show.sidebar')

    </div>
    <div class="py-12 flex justify-center">
      <button type="button"
        class="max-w-[320px] w-[90%] bg-white px-10 h-14 rounded-lg text-blue-1 hover:text-[#0f79c8]">
      Load more
      </button>
    </div>
  </section>
</div>

{{-- @section('scripts')
  <script>
    var options = {
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: true
        }
      },
      series: [
        {
          name: 'Yes',
          data: [100, 75, 50, 25, 0, 30, 55]
        },
        {
          name: 'No',
          data: [20, 45, 60, 35, 10, 25, 40]
        }
      ],
      xaxis: {
        categories: ['1/30/2024', '1/31/2024', '2/4/2024', '2/5/2024', '2/9/2024', '2/14/2024', '2/21/2024']
      },
      title: {
        text: 'Website Visitors Over Time'
      },
      // Additional customization options
      colors: ['#007bff', '#ff0000'], // Different colors for each line
      dataLabels: {
        enabled: true,
      },
      stroke: {
        curve: 'smooth'
      },
      fill: {
        type: 'area',
        opacity: 0.2
      },
    };

    var chart = new ApexCharts(document.querySelector('#chart'), options);

    // Get checkbox elements
    var yesCheckbox = document.getElementById('yes-checkbox');
    var noCheckbox = document.getElementById('no-checkbox');

    // Update chart based on checkbox selections
    function updateChart() {
      var updatedSeries = [];
      if (yesCheckbox.checked) {
        updatedSeries.push(options.series[0]);
      }
      if (noCheckbox.checked) {
        updatedSeries.push(options.series[1]);
      }
      chart.updateSeries(updatedSeries);
    }

    // Add event listeners to checkboxes
    yesCheckbox.addEventListener('change', updateChart);
    noCheckbox.addEventListener('change', updateChart);

    // Initial chart render
    chart.render();
  </script>
@endsection --}}



