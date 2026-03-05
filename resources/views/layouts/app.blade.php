<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>@yield('title', 'voice Line System')</title>
    <base href="{{ url('/') }}/">
    
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    
    @stack('styles')
</head>

<body>
    <!-- Logo -->
    <button type="button" onclick="window.location='{{ route('dashboard') }}'"
        style="background: none; border: none; padding: 0; cursor: pointer; margin: 15px;">
        <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="logo" draggable="false">
    </button>

    <!-- Decorative Images -->
    <img src="{{ asset('assets/bottomleft.svg') }}" class="bottom-left" alt="bottomleft" draggable="false">
    <img src="{{ asset('assets/topright.svg') }}" class="top-right" alt="topright" draggable="false">

    <!-- User Profile Dropdown -->
    <div class="profile">
        <div class="dropdown">
            <button class="dropbtn" aria-label="User menu">
                <i class="fa-solid fa-circle-user"></i>
            </button>
            <div class="dropdown-content" id="profile-dropdown-content">
                <a class="a" href="{{ url('/profile') }}">Profile</a>
                <a class="a" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Celebration Overlay -->
    <div id="celebration-overlay" class="celebration-overlay d-none">
        <div class="celebration-content text-center">
            <img id="celebration-gif" src="" alt="Celebration" class="celebration-gif"/>
            <h2 class="mt-3">Congratulations!</h2>
            <p id="celebration-text"></p>
        </div>
    </div>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('login','register'))
    <!-- Profile Card -->
    <div class="profile-card" id="profileCard" role="button" tabindex="0" aria-label="User profile summary">
        <!-- Always visible -->
        <div class="card-header">
            <img src="{{ asset('assets/zoom.svg') }}" alt="User Avatar">
            <div class="info">Calls <span class="total-calls">...</span></div>
        </div>

        <!-- Expandable content -->
        <div class="card-expand">
            <hr>
            <div class="name">Loading...</div>
            <div class="info">Email: <span>...</span></div>
            <p><strong>Today:</strong> <span class="today-calls">...</span></p>

            <div class="chart-container">
                <canvas id="totalStatusChartProfile"></canvas>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('call.archive') }}" class="btn btn-primary btn-sm">
                    View Full Archive
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Scroll buttons -->
    <button class="scroll-to-top" id="scrollToTop" title="Scroll to top" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <button class="scroll-to-bottom" id="scrollToBottom" title="Scroll to bottom" aria-label="Scroll to bottom">
        <i class="fas fa-arrow-down"></i>
    </button>

    @stack('scripts')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // SCROLL BUTTONS FUNCTIONALITY
    // ============================================
    const scrollToTopBtn = document.getElementById('scrollToTop');
    const scrollToBottomBtn = document.getElementById('scrollToBottom');

    function updateScrollButtons() {
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
    }

    window.addEventListener('scroll', updateScrollButtons);
    
    // Throttle scroll events for better performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(updateScrollButtons);
    });

    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    scrollToBottomBtn.addEventListener('click', function() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    });

    // ============================================
    // PROFILE CARD & CHART FUNCTIONALITY
    // ============================================
    const userId = @json(auth()->id());
    window.totalStatusChart = null;
    let chartInitialized = false;

    function loadProfileData() {
        $.get(`/profile-data/${userId}`)
            .done(function(data) {
                // Update profile card
                $('.profile-card .name').text(data.user.name);
                $('.profile-card .info:contains("Email") span').text(data.user.email);
                $('.profile-card .info:contains("Calls") span').text(data.totalCalls);

                // Update expanded content
                $('.total-calls').text(data.totalCalls);
                $('.today-calls').text(data.todayCalls);
                
                checkCelebration(data.todayCalls);
                renderTotalStatusChart(data.statusCounts);
            })
            .fail(function() {
                console.error('Failed to load profile data.');
                $('.profile-card .name').text('Error loading');
                $('.profile-card .info span').text('N/A');
            });
    }

    function renderTotalStatusChart(statusCounts) {
        const canvas = document.getElementById('totalStatusChartProfile');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        if (window.totalStatusChart) {
            window.totalStatusChart.destroy();
        }

        // Determine font size based on screen width
        const isMobile = window.innerWidth < 576;
        const legendFontSize = isMobile ? 9 : 12;
        const datalabelFontSize = isMobile ? 9 : 12;

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
                    legend: { 
                        position: 'bottom',
                        labels: {
                            font: {
                                size: legendFontSize
                            },
                            padding: isMobile ? 8 : 10
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            if (total === 0) return '0%';
                            const percentage = ((value / total) * 100).toFixed(1);
                            return percentage + '%';
                        },
                        color: '#fff',
                        font: { 
                            weight: 'bold', 
                            size: datalabelFontSize
                        }
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

    // Profile card click/tap handler
    function toggleProfileCard(e) {
        // Don't toggle if clicking on links, buttons, or canvas
        if ($(e.target).closest('a, button, canvas').length) return;

        $('#profileCard').toggleClass('expanded');

        if ($('#profileCard').hasClass('expanded') && !chartInitialized) {
            chartInitialized = true;
            loadProfileData();
        }

        // Resize chart after animation
        setTimeout(() => {
            if (window.totalStatusChart) {
                window.totalStatusChart.resize();
            }
        }, 500);
    }

    // Support both click and keyboard navigation
    $(document).on('click', '#profileCard', toggleProfileCard);
    
    $(document).on('keypress', '#profileCard', function(e) {
        if (e.which === 13 || e.which === 32) { // Enter or Space
            e.preventDefault();
            toggleProfileCard(e);
        }
    });

    // Initial load
    loadProfileData();

    // Handle window resize for responsiveness
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (window.totalStatusChart) {
                window.totalStatusChart.destroy();
                const canvas = document.getElementById('totalStatusChartProfile');
                if (canvas && $('#profileCard').hasClass('expanded')) {
                    loadProfileData();
                }
            }
        }, 250);
    });

    // Close celebration overlay on click
    $(document).on('click', '#celebration-overlay', function() {
        $(this).addClass('d-none');
    });
});
    </script>
</body>

</html>