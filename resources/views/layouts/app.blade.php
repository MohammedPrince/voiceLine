<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'voice Line System')</title>
 <base href="{{ url('/') }}/">
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




.profile-card .name {
    font-weight: bold;
    font-size: 1.2em;
    margin-bottom: 8px;
}

.profile-card .info {
    font-size: 0.95em;
    margin-bottom: 5px;
}


.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 240px; /* Increased slightly for 4 statuses */
    padding: 10px; /* Added padding so labels don't hit the edges */
    margin: 0 auto;
}


.profile-card {
    position: fixed;
    bottom: 30px;
    right: 80px;
    min-width: auto;
    max-width: 100px;
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    z-index: 999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: max-width 0.5s ease; /* animate max-width */
    box-sizing: border-box;
    will-change: max-width;
}

.profile-card.expanded {
    max-width: 320px;
}

/* expanded state: wider */
.profile-card.expanded {
    width: 300px; /* expanded width */
}

/* expandable part */
.card-expand {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.3s ease;
    opacity: 0;
    margin-top: 10px; /* spacing from header */
}

/* expanded state */
.profile-card.expanded .card-expand {
    max-height: 700px; /* enough to show content */
    opacity: 1;
}
.profile-card .card-header {
    display: flex;
    flex-direction: column;  /* stack vertically */
    align-items: center;     /* center horizontally */
    gap: 6px;
}



.celebration-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.celebration-content {
    background: #fff;
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: popIn 0.6s ease;
}

.celebration-icon {
    font-size: 60px;
    color: #EC8305;
}

@keyframes popIn {
    from {
        transform: scale(0.7);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
.celebration-gif {
    width: 220px;
    max-width: 100%;
    margin-bottom: 10px;
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
<div id="celebration-overlay" class="celebration-overlay d-none">
    <div class="celebration-content text-center">
   <img 
    id="celebration-gif"
    src="" 
    alt="Celebration"
    class="celebration-gif"
/>


        <h2 class="mt-3">Congratlations!</h2>
        <p id="celebration-text"></p>
    </div>
</div>


    <!-- Main Navigation Choices -->

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>


@if(!request()->routeIs('login','register'))

<div class="profile-card" id="profileCard">

    <!-- Always visible -->
    <div class="card-header">
 <img src="{{ asset('assets/zoom.svg') }}" alt="User Avatar" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
        <!-- <div class="name">Loading user name...</div>
        <div class="info">User Email: <span>Loading...</span></div> -->
        <div class="info">Calls <span class="total-calls">Loading...</span></div>
    </div>

    <!-- Expandable content (MUST be inside) -->
    <div class="card-expand">
        <hr>

        <!-- <p><strong>Total Calls:</strong> <span class="total-calls">Loading...</span></p> -->
      <div class="name">Loading user name...</div>
        <div class="info">User Email: <span>Loading...</span></div>
        <p><strong>Today's Calls:</strong> <span class="today-calls">Loading...</span></p>

        <div class="chart-container" style=" margin-top:0px;
    margin-left:0px;">
            <canvas id="totalStatusChartProfile" style=" margin-top:0px;
    margin-left:0px;"></canvas>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('call.archive') }}" class="btn btn-primary btn-sm">
                View Full Call Archive
            </a>
        </div>
    </div>

</div>

@endif





<!-- Global Profile Card -->
 
    <!-- Modal -->
   
                <!-- أزرار التمرير -->
                <button class="scroll-to-top" id="scrollToTop" title="انتقل إلى الأعلى">
                    <i class="fas fa-arrow-up"></i>
                </button>

                <button class="scroll-to-bottom" id="scrollToBottom" title="انتقل إلى الأسفل">
                    <i class="fas fa-arrow-down"></i>
                </button>
@stack('scripts')

                <!-- Scripts -->
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

     <script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll buttons functionality
    const scrollToTopBtn = document.getElementById('scrollToTop');
    const scrollToBottomBtn = document.getElementById('scrollToBottom');

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }

        const isAtBottom = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 100;
        if (!isAtBottom) {
            scrollToBottomBtn.classList.add('show');
        } else {
            scrollToBottomBtn.classList.remove('show');
        }
    });

    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    scrollToBottomBtn.addEventListener('click', function() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    });
});

// ============================================================
// PROFILE CARD & CHART - MOVED OUTSIDE $(document).ready()
// ============================================================

const userId = @json(auth()->id());
window.totalStatusChart = null;
let chartInitialized = false;

// Make loadProfileData global so it's accessible everywhere
function loadProfileData() {
    $.get(`/profile-data/${userId}`)
        .done(function(data) {
            // Update profile card
            $('.profile-card .name').text(data.user.name);
            $('.profile-card .info:contains("User Email") span').text(data.user.email);
            $('.profile-card .info:contains("Calls") span').text(data.totalCalls);

            // Update expanded content
            $('.total-calls').text(data.totalCalls);
            $('.today-calls').text(data.todayCalls);
            
            checkCelebration(data.todayCalls);

            // Calculate and render chart
            const statusCounts = calculateStatusCounts(data.calls || []);
            renderTotalStatusChart(statusCounts);
        })
        .fail(function() {
            console.error('Failed to load profile data.');
        });
}

function calculateStatusCounts(calls) {
    const counts = { Resolved: 0, Submitted: 0, Escalated: 0, Updated: 0 }; 
    
    calls.forEach(call => {
        const status = call.Final_Status;
        if (status === 'Resolved' || status === '1') {
            counts.Resolved++;
        } else if (status === 'Submitted' || status === '2') {
            counts.Submitted++;
        } else if (status === 'Escalated' || status === '3') {
            counts.Escalated++;
        } else if (status === 'Updated' || status === '4') {
            counts.Updated++;
        }
    });
    return counts;
}

function renderTotalStatusChart(statusCounts) {
    const canvas = document.getElementById('totalStatusChartProfile');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    if (window.totalStatusChart) {
        window.totalStatusChart.destroy();
    }

    window.totalStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Resolved', 'Submitted', 'Escalated', 'Updated'], 
            datasets: [{
                data: [
                    Number(statusCounts.Resolved) || 0,
                    Number(statusCounts.Submitted) || 0,
                    Number(statusCounts.Escalated) || 0,
                    Number(statusCounts.Updated) || 0
                ],
                backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384', '#4BC0C0'] 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return total ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                    },
                    color: '#fff',
                    font: { weight: 'bold', size: 12 }
                }
            }
        },
        plugins: window.ChartDataLabels ? [ChartDataLabels] : []
    });
}

function checkCelebration(todayCalls) {
    const celebrations = {
        10: {
            text: 'You reached 10 calls today! Amazing start 🚀',
            gif: "{{ asset('assets/got-this.gif') }}"
        },
        20: {
            text: '20 calls completed today! Outstanding work 💪',
            gif: "{{ asset('assets/yesss.gif') }}"
        },
        30: {
            text: '30 calls today! You are a superstar 🌟',
            gif: "{{ asset('assets/wow.gif') }}"
        },
        40: {
            text: '40 calls today! Incredible focus and energy 🔥',
            gif: "{{ asset('assets/min.gif') }}"
        },
        50: {
            text: '50 calls today! Absolute legend status achieved 👑🎉',
            gif: "{{ asset('assets/champion.gif') }}"
        }
    };

    if (!celebrations[todayCalls]) return;

    const overlay = document.getElementById('celebration-overlay');
    const text = document.getElementById('celebration-text');
    const gif = document.getElementById('celebration-gif');

    gif.src = '';
    gif.src = celebrations[todayCalls].gif;

    text.textContent = celebrations[todayCalls].text;
    overlay.classList.remove('d-none');

    setTimeout(() => {
        overlay.classList.add('d-none');
    }, 4000);
}

// Profile card click handler
$(document).on('click', '#profileCard', function (e) {
    if ($(e.target).closest('a, button, canvas').length) return;

    $(this).toggleClass('expanded');

    if ($(this).hasClass('expanded') && !chartInitialized) {
        chartInitialized = true;
        loadProfileData();
    }

    setTimeout(() => {
        if (window.totalStatusChart) {
            window.totalStatusChart.resize();
        }
    }, 400);
});

// Initial load
$(document).ready(function() {
    loadProfileData();
});
</script>
   
             
</body>

</html>