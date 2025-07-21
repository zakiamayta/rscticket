<header class="sticky top-0 z-50 bg-white shadow-md w-full">
  <div class="px-6 lg:px-16 xl:px-24 2xl:px-32">
    <div class="flex justify-between items-center h-16">
      <!-- Logo dan Nama -->
      <div class="flex items-center space-x-4">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
        <span class="text-4xl font-extrabold bg-gradient-to-r from-orange-500 to-yellow-400 text-transparent bg-clip-text">
          RSCTix
        </span>
      </div>
      <!-- Navigasi -->
      <div class="flex items-center space-x-8">
        <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
        <a href="#events" class="text-gray-700 hover:text-blue-600 font-medium">Events</a>
        <a href="{{ route('ticket.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow">
          Get Tickets
        </a>
      </div>
    </div>
  </div>
</header>
