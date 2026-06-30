<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Parallax Site')</title>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);
            color: white;
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            padding: 2rem;
        }
    </style>

    @stack('styles')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    @stack('scripts-head')
</head>

<body>

    @yield('content')

    @stack('scripts')

    <script>
        gsap.from(".glass", {
            y: 100,
            opacity: 0,
            duration: 1.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".glass",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });
    </script>
</body>

</html>
