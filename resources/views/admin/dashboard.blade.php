@extends('layouts.admin')
@section('title', get_phrase('Admin Dashboard'))
@section('admin_layout')
<script src="{{asset('assets/backend/js/Chart.js')}}"></script>
<style>
    #myChart{
        width: 100%;
        height: 600px;
    }
</style>
<div class="row g-2 g-3 mb-3">
   
    
</div>

<div class="row mb-3 ">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
        <div class="row gx-3 gy-2">    
            @if($totalListing != 0)
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                <div class="ol-card card-hover">
                    <div class="ol-card-body px-20px py-3">
                        <h5 class="sub-title fs-16px mb-2">{{get_phrase('Total Listing')}}</h5>
                        <h3 class="title card-title-hover fs-18px">{{$totalListing}}</h3>
                    </div>
                </div>
            </div>
            @endif
            @if($sleep != 0)
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                <div class="ol-card card-hover">
                    <div class="ol-card-body px-20px py-3">
                        <h5 class="sub-title fs-16px mb-2">{{get_phrase('Sleep')}}</h5>
                        <h3 class="title card-title-hover fs-18px">{{$sleep}}</h3>
                    </div>
                </div>
            </div>
            @endif
            @if($play != 0)
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                <div class="ol-card card-hover">
                    <div class="ol-card-body px-20px py-3">
                        <h5 class="sub-title fs-16px mb-2">{{get_phrase('Play')}}</h5>
                        <h3 class="title card-title-hover fs-18px">{{$play}}</h3>
                    </div>
                </div>
            </div>
            @endif
            @if($work != 0)
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                <div class="ol-card card-hover">
                    <div class="ol-card-body px-20px py-3">
                        <h5 class="sub-title fs-16px mb-2">{{get_phrase('Work')}}</h5>
                        <h3 class="title card-title-hover fs-18px">{{$work}}</h3>
                    </div>
                </div>
            </div>
            @endif
            @if(user('type') == 'admin')
                <div class=" col-lg-6 col-md-6 col-sm-6">
                    <div class="ol-card card-hover">
                        <div class="ol-card-body px-20px py-3">
                            <p class="sub-title fs-14px mb-2">{{get_phrase('All Agents')}}</p>
                            <p class="title card-title-hover fs-18px">{{$agent ?? ''}}</p>
                        </div>
                    </div>
                </div>
                <div class=" col-lg-6 col-md-6 col-sm-6">
                    <div class="ol-card card-hover">
                        <div class="ol-card-body px-20px py-3">
                            <p class="sub-title fs-14px mb-2">{{get_phrase('All Customers')}}</p>
                            <p class="title card-title-hover fs-18px">{{$customer ?? ''}}</p>
                        </div>
                    </div>
                </div> 
            @endif       
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
        <div class="ol-card h-100">
            <div class="ol-card-body p-4">
                <div class="chart-sm-item d-flex g-14px align-items-end justify-content-between">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


@php
    $currentYear = date('Y');
    $monthlyEarnings = DB::table('subscriptions')
        ->selectRaw("MONTH(created_at) as month, SUM(paid_amount) as total_earning")
        ->whereYear('created_at', $currentYear)
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->orderBy(DB::raw("MONTH(created_at)"))
        ->get();
    $monthlyData = [];
    for ($i = 1; $i <= 12; $i++) {
        $earningsForMonth = $monthlyEarnings->firstWhere('month', $i);
        $monthlyData[$i] = $earningsForMonth ? $earningsForMonth->total_earning : 0;
    }
@endphp
<div class="row">
    @if(count($sleep_appointment) != 0)
    <div class="col-12 col-md-12 mb-3">
        <div class="ol-card h-100">
            <div class="ol-card-body p-4">
                <canvas id="sleepChart" class="w-100"></canvas>
            </div>
        </div>
    </div>
    @endif
    @if(count($work_appointment) != 0)
    <div class="col-12 col-md-12 mb-3">
        <div class="ol-card h-100">
            <div class="ol-card-body p-4">
                <canvas id="workChart" class="w-100"></canvas>
            </div>
        </div>
    </div>
    @endif
    @if(count($play_appointment) != 0)
    <div class="col-12 col-md-12 mb-3">
        <div class="ol-card h-100">
            <div class="ol-card-body p-4">
                <canvas id="playChart" class="w-100"></canvas>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    "use strict";
    const xValues = [ "Sleep", "Work","Play"];
    const yValues = [
        {{ $sleepPercentage }},
        {{ $playPercentage }},
        {{ $workPercentage }}
    ];
    const barColors = [
        "#FF736A",
        "#124797",
        "#44A1ED",
    ];

    new Chart("myChart", {
        type: "pie",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            title: {
                display: true,
                text: "{{get_phrase('Visible Listings as Percentages')}}"
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        let label = data.labels[tooltipItem.index] || '';
                        if (label) {
                            label += ': ';
                        }
                        label += data.datasets[0].data[tooltipItem.index].toFixed(2) + '%';
                        return label;
                    }
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
"use strict";

const months = [
  "January","February","March","April","May","June",
  "July","August","September","October","November","December"
];

const currentYear = new Date().getFullYear();

// Safely decode PHP arrays → JS arrays
const workData  = {!! json_encode(array_values($workData  ?? [])) !!};
const sleepData = {!! json_encode(array_values($sleepData ?? [])) !!};
const playData  = {!! json_encode(array_values($playData  ?? [])) !!};

// Common color palettes
const colors = {
  work:  ["#3498DB","#2980B9","#5DADE2","#85C1E9","#AED6F1","#D6EAF8","#EBF5FB"],
  sleep: ["#9B59B6","#8E44AD","#BB8FCE","#D2B4DE","#E8DAEF","#F4ECF7"],
  play:  ["#2ECC71","#28B463","#58D68D","#82E0AA","#ABEBC6","#E9F7EF"]
};

// Reusable chart builder
function buildChart(ctxId, label, data, colors){
  const ctx = document.getElementById(ctxId);
  if(!ctx) return; // no error style
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: months,
      datasets: [{
        label: label,
        data: data,
        backgroundColor: colors,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: label + " Monthly Earnings (" + currentYear + ")"
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1000 }
        }
      }
    }
  });
}

// Render charts
buildChart("workChart",  "Work",  workData,  colors.work);
buildChart("sleepChart", "Sleep", sleepData, colors.sleep);
buildChart("playChart",  "Play",  playData,  colors.play);
</script>


@endsection