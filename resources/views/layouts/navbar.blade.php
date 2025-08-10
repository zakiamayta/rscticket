<!-- Tambahkan link Google Fonts di layout utama (layouts/app.blade.php di <head>) -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg shadow-md w-full border-b border-gray-100">
  <div class="px-6 lg:px-16 xl:px-24 2xl:px-32">
    <div class="flex justify-between items-center h-16">
      
      <!-- 🔹 Logo -->
      <div class="flex items-center space-x-4">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
        <span class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-orange-500 to-yellow-400 text-transparent bg-clip-text tracking-wide drop-shadow-sm"
              style="font-family: 'Poppins', sans-serif;">
          RSCTix
        </span>
      </div>

      <!-- 🔹 Menu Desktop -->
      <nav class="hidden md:flex items-center space-x-6">
        <a href="{{ url('/') }}" class="text-gray-700 hover:text-orange-500 font-medium transition">Home</a>
        <a href="#upcoming-events" class="text-gray-700 hover:text-orange-500 font-medium transition">Events</a>

        <!-- 🔹 Search Bar -->
        <div class="relative">
          <input type="text" id="event-search" placeholder="Search events..." 
            class="w-48 px-4 py-2 rounded-full border border-gray-200 shadow-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none text-sm transition"/>
          <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </div>

        <!-- 🔹 Tombol Get Tickets -->
        <a href="{{ route('ticket.form') }}" 
           class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-full shadow-md transition-transform transform hover:scale-105">
          Get Tickets
        </a>
      </nav>

      <!-- 🔹 Mobile Menu Button -->
      <div class="md:hidden">
        <button id="menu-toggle" class="focus:outline-none p-2 rounded-md hover:bg-orange-50 transition">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- 🔹 Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 bg-white border-t border-gray-200 transition-all duration-300 ease-in-out">
    <a href="{{ url('/') }}" class="block py-2 text-gray-700 hover:text-orange-500 font-medium">Home</a>
    <a href="#upcoming-events" class="block py-2 text-gray-700 hover:text-orange-500 font-medium">Events</a>
    
    <!-- Search on Mobile -->
    <div class="mt-2 relative">
      <input type="text" id="event-search-mobile" placeholder="Search events..." 
        class="w-full px-4 py-2 rounded-full border border-gray-200 shadow-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none text-sm transition"/>
      <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/>
        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
    </div>

    <!-- Tombol Get Tickets Mobile -->
    <a href="{{ route('ticket.form') }}" 
       class="block text-center mt-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2 rounded-full shadow-md transition-transform transform hover:scale-105">
      Get Tickets
    </a>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Toggle Mobile Menu
      document.getElementById('menu-toggle').addEventListener('click', () => {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
      });

      const searchInputs = [document.getElementById('event-search'), document.getElementById('event-search-mobile')];
      const cards = document.querySelectorAll('.event-card');
      const sectionUpcoming = document.getElementById("upcoming-events");

      function filterEvents(value) {
        cards.forEach(card => {
          const title = card.querySelector('.event-title').textContent.toLowerCase();
          card.style.display = title.includes(value) ? '' : 'none';
        });
      }

      searchInputs.forEach(input => {
        input.addEventListener('input', () => {
          filterEvents(input.value.toLowerCase());
        });

        input.addEventListener('keydown', e => {
          if (e.key === 'Enter') {
            e.preventDefault();
            filterEvents(input.value.toLowerCase());
            if (sectionUpcoming) {
              sectionUpcoming.scrollIntoView({ behavior: "smooth" });
            }
          }
        });
      });
    });
  </script>
</header>
