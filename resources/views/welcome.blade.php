<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIA Cloud</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #10b981;
            --bg-gradient: linear-gradient(135deg, #3b82f6, #10b981);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            color: white;
            perspective: 1000px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .nav-auth {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 1rem;
            z-index: 1000;
        }

        .btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            transform: scale(1);
            display: inline-block;
        }

        .btn-login {
            background-color: white;
            color: var(--primary-color);
        }

        .btn-register {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
        }

        .logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 2rem;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .logo img {
            max-width: 80%;
            max-height: 80%;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .tagline {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 1000px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .carousel-wrapper {
            position: relative;
            width: 800px;
            height: 500px;
            overflow: hidden;
            border-radius: 15px;
        }

        .carousel-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            background-size: cover;
            background-position: center;
        }

        .carousel-slide.active {
            opacity: 1;
        }

        .slide-content {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            padding: 25px;
            color: white;
        }

        .slide-content h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .slide-content p {
            font-size: 1.1rem;
        }

        .carousel-arrow {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 1.3rem;
        }

        .carousel-arrow:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            margin: 0 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .carousel-dot.active {
            background: white;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        footer {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            text-align: center;
            margin-top:8rem;
            /* Increased margin-top */
        }

        @media (max-width: 1100px) {
            .carousel-container {
                max-width: 90%;
                gap: 15px;
            }

            .carousel-wrapper {
                width: 100%;
                max-width: 700px;
                height: 450px;
            }
        }

        @media (max-width: 768px) {
            .carousel-container {
                max-width: 100%;
                gap: 10px;
            }

            .carousel-wrapper {
                height: 300px;
            }

            .carousel-arrow {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .slide-content h3 {
                font-size: 1.5rem;
            }

            .slide-content p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="nav-auth">
        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
        <a href="{{ route('register') }}" class="btn btn-register">Register</a>
    </div>

    <div class="container">
        <div class="logo">
            <img src="assets/images/logosia.png" alt="SIA Cloud Logo">
        </div>

        <h1>SIA CLOUD</h1>
        <p class="tagline">Manage data keuanganmu dengan mudah!</p>

        <div class="carousel-container">
            <button class="carousel-arrow carousel-arrow-left">&lt;</button>

            <div class="carousel-wrapper">
                <div class="carousel-slide active" style="background-image: url('assets/images/feature1.png')">
                    <div class="slide-content">
                        <h3>Feature 1</h3>
                        <p>Optimalkan pengelolaan data bisnis Anda dengan efisien untuk memastikan informasi penting
                            selalu akurat dan terkini.</p>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('assets/images/feature2.png')">
                    <div class="slide-content">
                        <h3>Feature 2</h3>
                        <p>Pencatatan transaksi yang mudah dan cepat untuk memastikan setiap detail keuangan Anda
                            tercatat dengan akurat dan dapat diakses kapan saja, di mana saja.</p>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('assets/images/feature3.png')">
                    <div class="slide-content">
                        <h3>Feature 3</h3>
                        <p>Buat laporan mendetail untuk membantu Anda memahami kinerja bisnis Anda</p>
                    </div>
                </div>
            </div>

            <button class="carousel-arrow carousel-arrow-right">&gt;</button>
        </div>

        <div class="carousel-dots">
            <div class="carousel-dot active" data-slide="0"></div>
            <div class="carousel-dot" data-slide="1"></div>
            <div class="carousel-dot" data-slide="2"></div>
        </div>
    </div>

    <footer>
        <p>© 2024 SIACLOUD. All rights reserved.</p>
        <h6>Developed by Muhammad Faiz Arrasyid & friends</h6>
        <a href="mailto:helpline@dagang.siacloudpro.com">helpline@dagang.siacloudpro.com</a>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            const prevBtn = document.querySelector('.carousel-arrow-left');
            const nextBtn = document.querySelector('.carousel-arrow-right');
            let currentSlide = 0;

            function updateCarousel(newSlide) {
                // Remove active class from current slide and dot
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');

                // Update current slide
                currentSlide = newSlide;

                // Add active class to new slide and dot
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }

            // Next slide
            nextBtn.addEventListener('click', () => {
                const nextSlide = (currentSlide + 1) % slides.length;
                updateCarousel(nextSlide);
            });

            // Previous slide
            prevBtn.addEventListener('click', () => {
                const prevSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateCarousel(prevSlide);
            });

            // Dot navigation
            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const slideIndex = parseInt(dot.getAttribute('data-slide'));
                    updateCarousel(slideIndex);
                });
            });

            // Auto-advance every 5 seconds
            setInterval(() => {
                const nextSlide = (currentSlide + 1) % slides.length;
                updateCarousel(nextSlide);
            }, 5000);
        });
    </script>
</body>

</html>
