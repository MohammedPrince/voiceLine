@extends('layouts.app')

@section('title', 'Call Page')

@section('content')
<style>
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

canvas {
    max-width: 100%;
    height: 100% !important;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<div class="reports-container mt-4">
    <ul>
        <li><a href="/reports"> General Report </a></li>
        <li><a href="/reports/calls-per-user" class="active">Detailed Report</a></li>
         <li><a href="/reports/voice-calls">Voice Calls Report</a></li>
    </ul>

    <h5>Calls Per Users Report:</h5>

    <!-- Filter Form -->
    <form id="filterForm" style="margin-top:-8vh;">
        <label for="period">Days/Duration:</label>
        <select name="period" id="period">
            <option value="">-- Select --</option>
            <option value="week">this week</option>
            <option value="month">this month</option>
            <option value="last_month">last month</option>
            <option value="custom">custom days</option>
        </select>

        <div id="custom-range" style="margin-top:10px; display:none">
            From: <input type="date" name="from" id="from">
            To: <input type="date" name="to" id="to">
            <button type="button" id="filterBtn">search</button>
        </div>
    </form>

    <!-- Charts -->
<!-- Charts -->
<div class="mt-4" style="margin-top:-10vh;">
    <div class="row text-center">
<div class="col-md-4 mb-4">
    <div class="chart-container">
    <canvas id="rushHourChart"></canvas>
       </div>
        <p class="chart-label">Rush Hour Report (Time of Day)</p>
    </div>
        <div class="col-md-4">
            <div class="chart-container">
                <canvas id="circleChart"></canvas>
            </div>
            <p>All Calls</p>
        </div>
        <div class="col-md-4">
            <div class="chart-container">
                <canvas id="totalStatusChart"></canvas>
            </div>
            <p>Total Calls Status</p>
        </div>
     
    </div>

    <div class="row text-center mt-4">
        <div class="col-md-4">
            <div class="chart-container">
                <canvas id="solvedChart"></canvas>
            </div>
            <p>Escalated</p>
        </div>
      
          <div class="col-md-4">
            <div class="chart-container">
                <canvas id="receivedChart"></canvas>
            </div>
            <p>Submitted</p>
        </div>
          <div class="col-md-4">
            <div class="chart-container">
                <canvas id="resolvedChart"></canvas>
            </div>
            <p>Resolved</p>
        </div>
</div>
    </div>
</div>

    <!-- Table -->
    <table id="reportTable" class="table table-striped table-bordered text-center">
        <thead class="custom-header">
            <tr>
                <th>User</th>
                <th>Received Calls</th>
                <th>Resolved</th>
                <th>Submitted</th>
                <th>Escalated</th>


            </tr>
        </thead>
        <tbody id='userTableBody'>
            @foreach($report as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->Received_Calls }}</td>
                <td>{{ $row->Resolved }}</td>
                <td>{{ $row->Submitted }}</td>
                <td>{{ $row->Escalated }}</td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script>
//     console.log('circleChart:', document.getElementById('circleChart'));
// console.log('receivedChart:', document.getElementById('receivedChart'));
// console.log('solvedChart:', document.getElementById('solvedChart'));
// console.log('resolvedChart:', document.getElementById('resolvedChart'));

document.addEventListener("DOMContentLoaded", function() {
    let periodSelect = document.getElementById("period");
    let customRange = document.getElementById("custom-range");
    let filterBtn = document.getElementById("filterBtn");

  const circleCanvas = document.getElementById('circleChart');
const receivedCanvas = document.getElementById('receivedChart');
const solvedCanvas = document.getElementById('solvedChart');
const resolvedCanvas = document.getElementById('resolvedChart');
const totalStatusCanvas = document.getElementById('totalStatusChart');
const rushHourCanvas = document.getElementById('rushHourChart');
if (!circleCanvas || !receivedCanvas || !solvedCanvas || !resolvedCanvas || !totalStatusCanvas || !rushHourCanvas) {
    console.error("One or more canvas elements not found");
    return;
}
const ctxTotalStatus = totalStatusCanvas.getContext('2d');
const ctxAll = circleCanvas.getContext('2d');
const ctxReceived = receivedCanvas.getContext('2d');
const ctxSolved = solvedCanvas.getContext('2d');
const ctxResolved = resolvedCanvas.getContext('2d');
const ctxRushHour = rushHourCanvas.getContext('2d');
    const colors = ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"];
const colors24 = Array.from({ length: 24 }, (_, i) =>
    `hsl(${(i * 360) / 24}, 70%, 60%)`
);

    const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 1, // Makes it square
    plugins: {
        legend: {
            position: 'bottom',
        },
        datalabels: {
            color: '#000',
            font: {
                weight: 'bold'
            },
            formatter: (value, context) => {
                const dataset = context.chart.data.datasets[0].data;
                const total = dataset.reduce((sum, val) => sum + val, 0);
                if (total === 0) return '0%';
                const percentage = ((value / total) * 100).toFixed(1);
                return percentage + "%";
            }
        }
    }
};

    let chartAll, chartCompleted, chartResolved, chartScheduled, totalStatusChart, rushHourChart ;

function toNumber(val) {
    return Number(val) || 0;
}
function renderCharts(users) {

    if (chartAll) chartAll.destroy();
    if (chartCompleted) chartCompleted.destroy();
    if (chartScheduled) chartScheduled.destroy();
    if (chartResolved) chartResolved.destroy();
const totalResolved = users.reduce(
    (sum, u) => sum + toNumber(u.Resolved), 0
);

const totalSubmitted = users.reduce(
    (sum, u) => sum + toNumber(u.Submitted), 0
);

const totalEscalated = users.reduce(
    (sum, u) => sum + toNumber(u.Escalated), 0
);
if (totalStatusChart) {
    totalStatusChart.destroy();
}

    const labels = users.map(u => u.name);
totalStatusChart = new Chart(ctxTotalStatus, {
    type: 'doughnut',
    data: {
        labels: ['Resolved', 'Submitted', 'Escalated'],
        datasets: [{
            data: [
                totalResolved,
                totalSubmitted,
                totalEscalated
            ],
            backgroundColor: [
                '#36A2EB', // Received
                '#FFCE56', // Submitted
                '#FF6384'  // Escalated
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            datalabels: {
                formatter: (value, context) => {
                    const data = context.dataset.data;
                    const total = data.reduce((a, b) => a + b, 0);
                    if (!total) return '0%';
                    return ((value / total) * 100).toFixed(1) + '%';
                },
                font: {
                    weight: 'bold'
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});

    chartAll = new Chart(ctxAll, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: users.map(u => toNumber(u.Received_Calls)),
                backgroundColor: colors
            }]
        },
        options: chartOptions,
        plugins: [ChartDataLabels]
    });

    chartCompleted = new Chart(ctxReceived, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: users.map(u => toNumber(u.Submitted)),
                backgroundColor: colors
            }]
        },
        options: chartOptions,
        plugins: [ChartDataLabels]
    });

    chartScheduled = new Chart(ctxSolved, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: users.map(u => toNumber(u.Escalated)),
                backgroundColor: colors
            }]
        },
        options: chartOptions,
        plugins: [ChartDataLabels]
    });

    chartResolved = new Chart(ctxResolved, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: users.map(u => toNumber(u.Resolved)),
                backgroundColor: colors
            }]
        },
        options: chartOptions,
        plugins: [ChartDataLabels]
    });
}
function renderRushHourChart(rushHourData) {
    if (!ctxRushHour) return;

    // Initialize an array with 24 zeros for each hour
    const hours = Array.from({ length: 24 }, (_, i) => i);
    const countsByHour = new Array(24).fill(0);

    // Fill counts from the data
    rushHourData.forEach(item => {
        countsByHour[item.hour] = item.count;
    });

    if (rushHourChart) {
        rushHourChart.destroy();
    }

   rushHourChart = new Chart(ctxRushHour, {
    type: 'doughnut',
    data: {
        labels: hours.map(h => `${h}:00`),
        datasets: [{
            label: 'Calls per Hour',
            data: countsByHour,
            backgroundColor: colors24 // See below for generating colors
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right', // Legend on the right for clarity
                labels: {
                    boxWidth: 12,
                    padding: 10,
                }
            },
            datalabels: {
                color: '#000',
                formatter: function(value) {
                    return value > 0 ? value : '';
                },
                font: { weight: 'bold' },
            }
        }
    },
    plugins: [ChartDataLabels]
});

}

    function loadReport() {
        let period = periodSelect.value;
        let from = document.getElementById("from").value;
        let to = document.getElementById("to").value;

      let url = "{{ route('dashboard.data') }}" + "?period=" + period;
        if (period === "custom") {
            url += "&from=" + from + "&to=" + to;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {

                let tbody = document.querySelector("#userTableBody");
              if (tbody) {
    tbody.innerHTML = "";
    data.users.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td>${row.name}</td>
                <td>${row.Received_Calls}</td>
                <td>${row.Resolved}</td>
                <td>${row.Submitted}</td>
                <td>${row.Escalated}</td>
            </tr>`;
    });
}
         // عشان نغذي او نملى الجارت
                renderCharts(data.users);
                 renderRushHourChart(data.rushHour);
            });
    }


    periodSelect.addEventListener("change", function() {
        if (this.value === "custom") {
            customRange.style.display = "block";
        } else {
            customRange.style.display = "none";
            loadReport();
        }
    });

    filterBtn.addEventListener("click", function() {
        loadReport();
    });


    loadReport();
});
</script>
     @endpush
