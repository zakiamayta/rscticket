<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    {{-- ...style custom scrollbar tetap... --}}
</head>
<body class="bg-gray-100 font-[Inter,sans-serif] min-h-screen text-gray-800">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 text-blue-600">...</div>
            <h1 class="text-xl font-bold text-gray-900">Admin Dashboard</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200 shadow-sm">
                Logout
            </button>
        </form>
    </header>

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-100 px-6 py-3 flex gap-4">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.absensi') }}" class="{{ request()->routeIs('admin.absensi') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
            Absensi
        </a>
        {{-- Tambah menu lain jika ada --}}
    </nav>

    {{-- Content --}}
    <main class="container mx-auto px-6 py-6">
        @yield('content')
    </main>

    @yield('scripts')

</body>
</html>
