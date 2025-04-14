@extends('layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  @include('inc.breadcrumb', [
  'breadcrumb_items' => [
  __('messages.Home') => 'active',
  ],
  ])


  <div class="row">
    <div class="col-xl-12 mb-4 col-lg-5 col-12 d-flex justify-content-between">


      @if (date('a') == 'am')
      @if (app()->getLocale() == 'ar')
      صباح الخير يا {{ auth()->user()->name }}
      @else
      Good morning, {{ auth()->user()->name }}
      @endif
      @else
      @if (app()->getLocale() == 'ar')
      مساء الخير يا {{ auth()->user()->name }}
      @else
      Good evening, {{ auth()->user()->name }}
      @endif
      @endif
      😍

    </div>
    <div class="col-12 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title mb-0">{{ __('messages.Statistics') }}</h5>
            {{-- <small class="text-muted">Updated 1 month ago</small> --}}
          </div>
        </div>
        <div class="card-body">
          <div class="row gy-3">

            <div class="col-md-4 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-info me-3 p-2">
                  <i class="tf-icons ti ti-clock-pause"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $draft_invoices }}</h5>
                  <small>{{ __('messages_301.Draft invoices') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                  <i class="ti ti-clock-cancel"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $cancel_invoices }}</h5>
                  <small>{{ __('messages_301.Cancel invoices') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-success me-3 p-2">
                  <i class="tf-icons ti ti-clock-check"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $send_invoices }}</h5>
                  <small>{{ __('messages_301.Send invoices') }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="me-10 d-flex gap-5">
      <a href="{{ route('admin.invoices.create') }}"
        class="d-flex flex-column justify-content-center align-items-center">
        <button class="btn btn-primary mx-auto" style="width: fit-content; padding: 20px; margin-bottom: 4px;">
          <i class="fa-brands fa-plus" style="font-size: 25px"></i>
        </button>
        {{ __('messages.New Prescription') }}
      </a>

      <a href="{{ route('admin.formulations.index') }}"
        class="d-flex flex-column justify-content-center align-items-center">
        <button class="btn btn-primary mx-auto" style="width: fit-content; padding: 20px; margin-bottom: 4px;">
          <i class="fa-solid fa-prescription-bottle-medical" style="font-size: 25px"></i>
        </button>
        {{ __('messages.Formulations') }}
      </a>

      <a href="{{ route('admin.products.index') }}"
        class="d-flex flex-column justify-content-center align-items-center">
        <button class="btn btn-primary mx-auto" style="width: fit-content; padding: 20px; margin-bottom: 4px;">
          <i class="fa-brands fa-product-hunt" style="font-size: 25px"></i>
        </button>
        {{ __('messages.Products') }}
      </a>
    </div>

  </div>

</div>
@endsection
{{--
@section('script')
<script src="{{ asset('assets/js/charts-apex.js') }}"></script>
<script>
  if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        headingColor = config.colors_dark.headingColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
      } else {
        cardColor = config.colors.cardColor;
        headingColor = config.colors.headingColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
      }

      const chartColors = {
        column: {
          series1: '#826af9',
          series2: '#d2b0ff',
          bg: '#f8d3ff'
        },
        donut: {
          series1: '#fee802',
          series2: '#3fd0bd',
          series3: '#826bf8',
          series4: '#2b9bf4'
        },
        area: {
          series1: '#29dac7',
          series2: '#60f2ca',
          series3: '#a5f8cd'
        }
      };
  // Line Area Chart
  // --------------------------------------------------------------------
  const areaChartEl = document.querySelector('#lineAreaChart'),
  areaChartConfig = {
    chart: {
      height: 400,
      type: 'area',
      parentHeightOffset: 0,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: false,
      curve: 'straight'
    },
    legend: {
      show: true,
      position: 'top',
      horizontalAlign: 'start',
      labels: {
       colors: legendColor,
        useSeriesColors: false
      }
    },
    grid: {
      borderColor: borderColor,
      xaxis: {
        lines: {
          show: true
        }
      }
    },
    colors: [chartColors.area.series3, chartColors.area.series2, chartColors.area.series1],
    series: [
      {
        name: '{{ __("messages_301.Parents") }}',
        data: "{{ $charts['parents_chart'] }}".split(",")
      },
      {
        name: '{{ __("messages_301.Students") }}',
        data: "{{ $charts['students_chart'] }}".split(",")
      },
      {
        name: '{{ __("messages_301.Drivers") }}',
        data: "{{ $charts['drivers_chart'] }}".split(",")
      }
    ],
    xaxis: {
      categories: "{{ $charts['month_year'] }}".split(","),
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      labels: {
        style: {
          colors: labelColor,
          fontSize: '13px'
        }
      }
    },
    yaxis: {
      labels: {
        style: {
          colors: labelColor,
          fontSize: '13px'
        }
      }
    },
    fill: {
      opacity: 1,
      type: 'solid'
    },
    tooltip: {
      shared: false
    }
  };
if (typeof areaChartEl !== undefined && areaChartEl !== null) {
  const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
  areaChart.render();
}

</script>
@endsection --}}