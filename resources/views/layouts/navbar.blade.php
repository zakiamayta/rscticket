<header class="sticky top-0 z-50 bg-white shadow-md w-full">
  <div class="px-6 lg:px-16 xl:px-24 2xl:px-32">
    <div class="flex justify-between items-center h-16">
      <div class="flex items-center space-x-4">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
        <span class="text-2xl md:text-3xl font-extrabold bg-gradient-to-r from-orange-500 to-yellow-400 text-transparent bg-clip-text">
          RSCTix
        </span>
      </div>
      <div class="md:hidden">
        <button id="menu-toggle" class="focus:outline-none">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <nav class="hidden md:flex items-center space-x-6">
        <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
        <a href="#events" class="text-gray-700 hover:text-blue-600 font-medium">Events</a>
        <a href="{{ route('ticket.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow">
          Get Tickets
        </a>
      </nav>
    </div>
  </div>
  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
    <a href="{{ url('/') }}" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Home</a>
    <a href="#events" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Events</a>
    <a href="{{ route('ticket.create') }}" class="block text-center mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-full shadow">
      Get Tickets
    </a>
  </div>

  <script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    });
  </script>
</header>
