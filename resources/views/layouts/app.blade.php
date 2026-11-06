<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Luvit')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite(['resources/css/app.css'])
  @yield('styles')

  <link rel="icon" href="{{ asset('images/icones.png') }}" type="image/png">
  
  <link rel="manifest" href="/site.webmanifest" />
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css"/>

  <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css"/>
  <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/material.css"/>

  <style>
    :root { --rose: #f43f5e; --ink:#0b0b0f; }
    body{ background: radial-gradient(1200px 600px at 10% -10%, rgba(244,63,94,.15), transparent),
                       radial-gradient(900px 500px at 90% -20%, rgba(59,130,246,.1), transparent),
                       #0c0d10; color: #eaeaec; font-family: 'Inter',system-ui,-apple-system,Segoe UI,Roboto; }
    .glass{ backdrop-filter: blur(10px); background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.03)); border: 1px solid rgba(255,255,255,.12); }
    .shine{ position: relative; overflow: hidden; }
    .shine::after{ content:""; position:absolute; inset:-200%; background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,.25) 50%, transparent 70%); transform: translateX(-100%) rotate(20deg); }
    .shine:hover::after{ animation: shine 1s linear; }
    @keyframes shine{ to { transform: translateX(100%) rotate(20deg);} }
    .poster-shadow{ box-shadow: 0 15px 30px rgba(0,0,0,.5); }
    .ring-rose { box-shadow: 0 0 0 0 rgba(244,63,94,.6); animation: pulse 2s infinite; }
    @keyframes pulse { 70%{ box-shadow:0 0 0 12px rgba(244,63,94,0);} 100%{ box-shadow:0 0 0 0 rgba(244,63,94,0);} }
    .scrollbar-hide::-webkit-scrollbar{ display:none; } .scrollbar-hide{ -ms-overflow-style:none; scrollbar-width:none; }
  </style>
@vite(['resources/css/identity.css'])
</head>
<body class="min-h-dvh">

  @include('partials.header')

  <main class="pt-24 md:pt-28">@yield('content')</main>

  @include('partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.1/dist/vanilla-tilt.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@yield('scripts')
</body>
</html>