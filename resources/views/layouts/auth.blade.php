<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Luvit')</title>
  @vite(['resources/css/app.css'])
  <link rel="icon" href="{{ asset('images/icones.png') }}" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root { --rose: #f43f5e; --ink:#0b0b0f; }
    body{ background: radial-gradient(1200px 600px at 10% -10%, rgba(244,63,94,.15), transparent),
                       radial-gradient(900px 500px at 90% -20%, rgba(59,130,246,.1), transparent),
                       #0c0d10; color: #eaeaec; font-family: 'Inter',system-ui,-apple-system,Segoe UI,Roboto; }
    .glass{ backdrop-filter: blur(10px); background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.03)); border: 1px solid rgba(255,255,255,.12); }
    html, body { min-height: 100%; overflow-x: hidden; }
  </style>
@vite(['resources/css/identity.css'])
</head>
<body>
  <main>@yield('content')</main>
  @yield('scripts')
</body>
</html>