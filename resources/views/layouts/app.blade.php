<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'voice Line System')</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>

    </style>
    <style>
    /* زر التمرير إلى الأعلى */
    .a:hover {

        border-radius: 15%;
    }

    .scroll-to-top {




        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #EC8305;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .scroll-to-top.show {
        opacity: 1;
    }

    /*     
    .scroll-to-top:hover {
      background-color: #5b3609ff;
      transform: translateY(-3px);
    }
     */
    /* زر التمرير إلى الأسفل */
    .scroll-to-bottom {
        position: fixed;
        bottom: 90px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #EC8305;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .scroll-to-bottom.show {
        opacity: 1;
    }
    </style>
    @stack('styles')
    <!-- Optional page-specific styles -->
</head>

<body>

    <!-- Logo -->
    <!-- <a href="{{route('dashboard')}}">
        <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="logo" draggable="false">
    </a> -->
    <button type="button" onclick="window.location='{{ route('dashboard') }}'"
        style="background: none; border: none; padding: 0; cursor: pointer;">
        <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="logo" draggable="false">
    </button>


    <!-- Decorative Images -->
    <img src="{{ asset('assets/bottomleft.svg') }}" class="bottom-left" alt="bottomleft" draggable="false">
    <img src="{{ asset('assets/topright.svg') }}" class="top-right" alt="topright" draggable="false">

    <!-- User Profile Dropdown -->
    <div class="profile">
        <div class="dropdown">
            <button class="dropbtn">
                <i class="fa-solid fa-circle-user" style="color: white; font-size: 38px;"></i>
            </button>
            <div class="dropdown-content" id="profile-dropdown-content"
                style="position: absolute; right: 0; min-width: 120px; max-width: 180px;max-hieght">
                <a class="a" href="{{ url('/profile') }}">Profile</a>
                <a class="a" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden"
                    style="display:none;">
                    <style></style>

                    @csrf
                </form>
            </div>
        </div>
    </div>


    <!-- Main Navigation Choices -->

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Card -->
    <div class="profile-card" data-bs-toggle="modal" data-bs-target="#cardModal">
        <div class="profile-img">
            <img src="assets/96765adfcc834ea1ad53be69c3d61be9.jpeg" alt="Profile">
        </div>
        <div class="name">Ms. Mohammed</div>
        <p>ID:125058</p>
        <h6>Morning Shift</h6>
        <div class="info">User Email: <span>Mohamed@gmail.com</span></div>
        <div class="info">Shift Received Calls <span>11 calls</span></div>
        <div class="info">Session Start Time: <span>09:00AM</span></div>
        <div class="info">Session End Time <span>05:18PM</span></div>
        <div class="info">Total Hours: <span>1:30:37</span></div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="cardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left side: user info -->
                        <div class="col-md-5" style="padding: 40px;">
                            <div class="d-flex align-items-center mb-3">
                                <img src="assets/96765adfcc834ea1ad53be69c3d61be9.jpeg" class="rounded-circle me-3"
                                    alt="Profile">
                                <div>
                                    <h5 class="mb-0">Ms. Mohammed </h5>
                                    <p>ID:125058</p>
                                    <small>Morning Shift</small>
                                </div>
                            </div>
                            <p>User Email: <b>Mohamed@gmail.com</b></p>
                            <p>Shift Received Calls: <b>11 calls</b></p>
                            <p>Session Start Time: <b>09:00AM</b></p>
                            <p>Session End Time: <b>05:18PM</b></p>
                            <p>Total Hours: <b>1:30:37</b></p>
                        </div>

                        <!-- Right side: per-hour chart -->
                        <div class="col-md-7">
                            <div class="card" role="region" aria-label="Calls per hour chart">
                                <h1>Calls per hour</h1>

                                <div class="chart-wrap">
                                    <canvas id="callsChart" aria-label="Bar chart showing calls per hour"
                                        role="img"></canvas>
                                </div>

                                <div class="controls" style="align-items:center; margin-top: -140px;">
                                    <button class="secondary" id="downloadCsvBtn">Download CSV</button>
                                </div>
                            </div> <!-- end card -->
                        </div> <!-- end col-md-7 -->
                        <!-- Active Users Table -->
                        <h3 class=" mt-4">Call Archive</h3>
                        <table class="table table-striped table-bordered text-center">
                            <thead class="custom-header">
                                <tr>
                                    <th>Student Index</th>
                                    <th>Name</th>
                                    <th>Ticket Number</th>
                                    <th>Case Category </th>
                                    <th>Issue</th>
                                    <th>Case status</th>
                                    <th>Final Status</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <!-- Filled by JS -->
                            </tbody>
                        </table>

                    </div> <!-- end row -->
                </div>
                <!-- أزرار التمرير -->
                <button class="scroll-to-top" id="scrollToTop" title="انتقل إلى الأعلى">
                    <i class="fas fa-arrow-up"></i>
                </button>

                <button class="scroll-to-bottom" id="scrollToBottom" title="انتقل إلى الأسفل">
                    <i class="fas fa-arrow-down"></i>
                </button>

                <!-- Scripts -->
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // عناصر أزرار التمرير
                    const scrollToTopBtn = document.getElementById('scrollToTop');
                    const scrollToBottomBtn = document.getElementById('scrollToBottom');

                    // عرض/إخفاء أزرار التمرير بناء على موضع التمرير
                    window.addEventListener('scroll', function() {
                        // التمرير إلى الأعلى
                        if (window.pageYOffset > 300) {
                            scrollToTopBtn.classList.add('show');
                        } else {
                            scrollToTopBtn.classList.remove('show');
                        }

                        // التمرير إلى الأسفل - إظهار الزر إذا لم نكن في الأسفل
                        const isAtBottom = window.innerHeight + window.pageYOffset >= document.body
                            .offsetHeight -
                            100;
                        if (!isAtBottom) {
                            scrollToBottomBtn.classList.add('show');
                        } else {
                            scrollToBottomBtn.classList.remove('show');
                        }
                    });

                    // التمرير إلى الأعلى عند النقر
                    scrollToTopBtn.addEventListener('click', function() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });

                    // التمرير إلى الأسفل عند النقر
                    scrollToBottomBtn.addEventListener('click', function() {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        });
                    });
                });
                </script>
                <script>
                const users = [{
                        id: "U001",
                        email: "Mohammed",
                        received: 120,
                        solved: 100,
                        Issue: 10,
                        escalated: 5,
                        priority: "Low"
                    },
                    {
                        id: "U002",
                        email: "Mohammed",
                        received: 90,
                        solved: 70,
                        Issue: 15,
                        escalated: 7,
                        priority: "High"
                    },
                    {
                        id: "U003",
                        email: "Mohammed",
                        received: 60,
                        solved: 55,
                        Issue: 8,
                        escalated: 2,
                        priority: 'Low'
                    },
                    {
                        id: "U004",
                        email: "Mohammed",
                        received: 30,
                        solved: 20,
                        Issue: 5,
                        escalated: 1,
                        priority: "Medium"
                    }
                ];

                // Fill Active Users table
                const tableBody = document.getElementById("userTableBody");
                users.forEach(user => {
                    const row = `
    <tr>
      <td>${user.id}</td>
      <td>${user.email}</td>
      <td>${user.received}</td>
      <td>${user.solved}</td>
      <td>${user.new}</td>
      <td>${user.escalated}</td>
      <td>${user.priority}</td>
    </tr>`;
                    tableBody.innerHTML += row;
                });
                </script>
                <!-- end modal-body -->


                <!-- Bootstrap + Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

                <script>
                // Labels for 24 hours (0:00 to 23:00)
                const labels = Array.from({
                    length: 24
                }, (_, i) => `${i}:00`);

                // Replace sampleData with your real call counts (24 numbers)
                const sampleData = [2, 1, 0, 0, 1, 3, 8, 12, 20, 30, 28, 25, 22, 20, 18, 19, 21, 24, 23, 17, 12, 8, 4,
                    2
                ];

                // Create gradient for the bars
                const ctx = document.getElementById('callsChart').getContext('2d');

                // Build chart
                const config = {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Calls',
                            data: sampleData.slice(),
                            borderRadius: 6,
                            maxBarThickness: 40,
                            backgroundColor: function(context) {
                                // subtle gradient per bar (vertical)
                                const chart = context.chart;
                                const {
                                    ctx,
                                    chartArea
                                } = chart;
                                if (!chartArea)
                                    return 'rgba(59,130,246,0.9)'; // fallback before rendering
                                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea
                                    .bottom);
                                gradient.addColorStop(0, 'rgba(59,130,246,0.95)');
                                gradient.addColorStop(1, 'rgba(59,130,246,0.65)');
                                return gradient;
                            },
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const val = ctx.parsed.y ?? ctx.raw ?? 0;
                                        return ` ${val} call${val===1 ? '' : 's'}`;
                                    }
                                }
                            },
                            title: {
                                display: false
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 12
                                },
                                title: {
                                    display: true,
                                    text: 'Hour of day'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax: Math.max(...sampleData) + 5,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Number of calls'
                                }
                            }
                        }
                    }
                };

                const callsChart = new Chart(ctx, config);

                // Helper: update chart with an array of 24 numbers
                function updateChart(data24) {
                    if (!Array.isArray(data24) || data24.length !== 24) {
                        alert('updateChart requires an array of exactly 24 numeric values (one per hour).');
                        return;
                    }
                    // sanitize values to numbers
                    const sanitized = data24.map(n => (n === '' || n === null || n === undefined) ? 0 : Number(n));
                    callsChart.data.datasets[0].data = sanitized;
                    // adjust suggestedMax for nicer scaling
                    const max = Math.max(...sanitized);
                    callsChart.options.scales.y.suggestedMax = Math.max(Math.ceil(max * 1.15), 5);
                    callsChart.update();
                }







                // Download chart data as CSV
                document.getElementById('downloadCsvBtn').addEventListener('click', () => {
                    const data = callsChart.data.datasets[0].data;
                    const header = ['hour', 'calls'];
                    const rows = data.map((v, i) => `${i}:00,${v}`);
                    const csv = [header.join(','), ...rows].join('\n');
                    const blob = new Blob([csv], {
                        type: 'text/csv'
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'calls_per_hour.csv';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);
                });

                // make updateChart available on window for external scripts
                window.updateChart = updateChart;

                // initial render (already uses sampleData)
                updateChart(sampleData);
                </script>
                @stack('scripts')
                <!-- Optional page-specific scripts -->
</body>

</html>