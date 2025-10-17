<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    

    <style>
        :root {
            --primary-black: #000000;
            --secondary-black: #1a1a1a;
            --primary-white: #ffffff;
            --secondary-white: #f5f5f5;
            --accent-gray: #333333;
            --light-gray: #e0e0e0;
            --medium-gray: #9e9e9e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--secondary-white);
            color: var(--primary-black);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background-color: var(--primary-white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-black);
        }

        .logo span {
            color: var(--accent-gray);
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--accent-gray);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background-color: var(--light-gray);
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-login {
            background-color: var(--primary-white);
            color: var(--primary-black);
            border: 1px solid var(--light-gray);
        }

        .btn-register {
            background-color: var(--primary-black);
            color: var(--primary-white);
            border: 1px solid var(--primary-black);
        }

        .btn-login:hover {
            background-color: var(--light-gray);
        }

        .btn-register:hover {
            background-color: var(--accent-gray);
            border-color: var(--accent-gray);
        }

        /* Hero Slider Styles */
        .hero-slider {
            position: relative;
            height: 600px;
            overflow: hidden;
            background-color: var(--secondary-black);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease;
            background-size: cover;
            background-position: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            color: var(--primary-white);
            padding: 0 20px;
        }

        .slide-content h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            max-width: 800px;
        }

        .slide-content p {
            font-size: 20px;
            margin-bottom: 30px;
            max-width: 600px;
            color: var(--light-gray);
        }

        .slider-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .slider-dot.active {
            background-color: var(--primary-white);
        }

        /* Search Form Styles */
        .search-form {
            background-color: var(--primary-white);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--accent-gray);
            text-align: left;
        }

        .form-input {
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            font-size: 16px;
            background-color: var(--primary-white);
            color: var(--primary-black);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-gray);
        }

        .range-container {
            display: flex;
            flex-direction: column;
        }

        .range-display {
            font-weight: 700;
            margin-top: 5px;
        }

        .range-input {
            -webkit-appearance: none;
            width: 100%;
            height: 5px;
            background: var(--light-gray);
            border-radius: 5px;
            outline: none;
        }

        .range-input::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary-black);
            cursor: pointer;
        }

        .btn-search {
            background-color: var(--primary-black);
            color: var(--primary-white);
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            align-self: flex-end;
        }

        .btn-search:hover {
            background-color: var(--accent-gray);
        }

        /* Properties Section */
        .properties-section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            color: var(--primary-black);
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .property-card {
            background-color: var(--primary-white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .property-image {
            height: 200px;
            background-color: var(--light-gray);
            position: relative;
            overflow: hidden;
        }

        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .property-card:hover .property-image img {
            transform: scale(1.05);
        }

        .property-price {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background-color: var(--primary-black);
            color: var(--primary-white);
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 18px;
        }

        .property-details {
            padding: 20px;
        }

        .property-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-black);
        }

        .property-location {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: var(--medium-gray);
            font-size: 14px;
        }

        .property-location svg {
            margin-right: 5px;
        }

        .property-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .btn-view {
            background-color: var(--secondary-white);
            color: var(--primary-black);
            text-align: center;
            font-weight: 600;
        }

        .btn-view:hover {
            background-color: var(--light-gray);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-book {
            flex: 1;
            background-color: var(--primary-black);
            color: var(--primary-white);
            text-align: center;
            font-weight: 600;
        }

        .btn-book:hover {
            background-color: var(--accent-gray);
        }

        .btn-chat {
            padding: 10px 15px;
            background-color: var(--primary-white);
            color: var(--primary-black);
            border: 1px solid var(--light-gray);
            text-align: center;
            font-weight: 600;
        }

        .btn-chat:hover {
            background-color: var(--light-gray);
        }

        .no-properties {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background-color: var(--primary-white);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .no-properties p {
            margin-bottom: 10px;
            color: var(--accent-gray);
        }

        .no-properties p:first-child {
            font-size: 20px;
            font-weight: 600;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .pagination a, .pagination span {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            text-decoration: none;
            color: var(--primary-black);
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: var(--light-gray);
        }

        .pagination .active {
            background-color: var(--primary-black);
            color: var(--primary-white);
            border-color: var(--primary-black);
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .slide-content h1 {
                font-size: 36px;
            }

            .slide-content p {
                font-size: 18px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                order: 3;
                width: 100%;
                justify-content: center;
                margin-top: 15px;
            }

            .hero-slider {
                height: 500px;
            }

            .slide-content h1 {
                font-size: 28px;
            }

            .slide-content p {
                font-size: 16px;
            }

            .search-form {
                margin-top: -40px;
            }

            .properties-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .properties-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-chat {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    @include('sweetalert::alert')

    @include('components.header')

    <main>
        @yield('content')
    </main>

       <script>
        // Hero Slider Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;

            // Function to show a specific slide
            function showSlide(n) {
                // Hide all slides
                slides.forEach(slide => {
                    slide.classList.remove('active');
                });

                // Remove active class from all dots
                dots.forEach(dot => {
                    dot.classList.remove('active');
                });

                // Show the selected slide
                slides[n].classList.add('active');
                dots[n].classList.add('active');

                currentSlide = n;
            }

            // Add click event to dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                });
            });

            // Auto slide change
            setInterval(() => {
                let nextSlide = (currentSlide + 1) % slides.length;
                showSlide(nextSlide);
            }, 5000);

            // Range input display update
            const rentRange = document.getElementById('max_rent');
            const rentDisplay = document.getElementById('rent_display');

            if (rentRange && rentDisplay) {
                rentRange.addEventListener('input', function() {
                    rentDisplay.textContent = '$' + Number(this.value).toLocaleString('en-US');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
