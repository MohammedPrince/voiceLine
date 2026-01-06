@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<style>
.chart-container {
    position: relative;
    height: 300px;
    width: 300px;
    max-width: 300px;
    margin: 0 auto;
}

canvas {
    max-width: 100%;
    height: 100% !important;
}

.profile-card {
    cursor: pointer;
    border: 1px solid #ddd;
    padding: 20px;
    margin-top: 20px;
    border-radius: 8px;
    max-width: 320px;
}

.profile-card .name {
    font-weight: bold;
    font-size: 1.3em;
    margin-bottom: 10px;
}

.profile-card .info {
    margin-bottom: 6px;
}
</style>

<div class="choices">
    <a class="choice" href="{{ url('reports/') }}">
        <span>Reports</span>
    </a>
    <a class="choice" href="{{ route('student') }}">
        <span>Call Entry</span>
    </a>

    <!-- User Card (initially empty placeholders) -->
    <div class="profile-card" data-bs-toggle="modal" data-bs-target="#cardModal" style="min-height: 140px;">
        <div class="name">Loading user name...</div>
        <div class="info">User Email: <span>Loading...</span></div>
        <div class="info">Received Calls <span>Loading...</span></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left side user info -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <img src="assets/zoom.svg" class="rounded-circle me-3" alt="Profile" width="60" height="60">
                            <div>
                                <h5 class="mb-0 modal-user-name">Loading...</h5>
                                <p class="mb-0 text-muted modal-user-email">Loading...</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p><strong>Total Calls:</strong> <span class="modal-total-calls">Loading...</span></p>
                            <p><strong>Today's Calls:</strong> <span class="modal-today-calls">Loading...</span></p>
                        </div>
                    </div>

                    <!-- Right side chart -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="totalStatusChart"></canvas>
                        </div>
                        <p class="text-center mt-2"><strong>Total Calls by Status</strong></p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ route('call.archive') }}" class="btn btn-primary btn-lg">View Full Call Archive</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
$(document).ready(function() {
    const userId = @json(auth()->id());
    let totalStatusChart;

    // Function to render the status chart
    function renderTotalStatusChart(statusCounts) {
        const canvas = document.getElementById('totalStatusChart');
        const ctx = canvas.getContext('2d');
        
        if (totalStatusChart) totalStatusChart.destroy();

        totalStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Resolved', 'Submitted', 'Escalated'],
                datasets: [{
                    data: [
                        Number(statusCounts.Resolved) || 0,
                        Number(statusCounts.Submitted) || 0,
                        Number(statusCounts.Escalated) || 0
                    ],
                    backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    datalabels: {
                        formatter: (value, ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            return total ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Function to calculate status counts
    function calculateStatusCounts(calls) {
        const counts = { Resolved: 0, Submitted: 0, Escalated: 0 };
        calls.forEach(call => {
            const status = call.Final_Status;
            if (status === 'Resolved' || status === '1') {
                counts.Resolved++;
            } else if (status === 'Submitted' || status === '2') {
                counts.Submitted++;
            } else if (status === 'Escalated' || status === '3') {
                counts.Escalated++;
            }
        });
        return counts;
    }

    // Load profile data
    function loadProfileData() {
        $.get(`/profile-data/${userId}`)
            .done(function(data) {
                // Update profile card
                $('.profile-card .name').text(data.user.name);
                $('.profile-card .info:contains("User Email") span').text(data.user.email);
                $('.profile-card .info:contains("Received Calls") span').text(`${data.totalCalls} calls`);

                // Update modal info
                $('.modal-user-name').text(data.user.name);
                $('.modal-user-email').text(data.user.email);
                $('.modal-total-calls').text(data.totalCalls);
                $('.modal-today-calls').text(data.todayCalls);

                // Calculate and render chart
                const statusCounts = calculateStatusCounts(data.calls || []);
                renderTotalStatusChart(statusCounts);
            })
            .fail(function() {
                alert('Failed to load profile data.');
            });
    }

    // Load data on page load
    loadProfileData();

    // Refresh chart when modal opens
    $('#cardModal').on('shown.bs.modal', function () {
        if (totalStatusChart) {
            totalStatusChart.resize();
        }
    });
});
</script>
@endpush