<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'RSCTix')</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
  </style>

  @stack('styles')
</head>
<body class="text-gray-800">
  @include('layouts.navbar')

  <main class="min-h-screen">
    @yield('content')
  </main>
</body>
</html>
