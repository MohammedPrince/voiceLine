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
   
             
</body>

</html>