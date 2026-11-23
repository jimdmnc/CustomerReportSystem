<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Reports')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/side-nav.css',
        'resources/js/app.js'
    ])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">

    @include('partials.side-nav')

    <!-- md:pl-64 offsets the fixed w-64 sidebar on desktop; min-w-0 prevents horizontal blowout -->
    <div class="flex-1 flex flex-col min-w-0 md:pl-64">

        <!-- Top Navigation Bar -->
        <nav class="h-16 bg-white border-b border-gray-200 px-4 md:px-6 flex items-center gap-3 shrink-0">
            
            <!-- Hamburger Button (Only visible when screen is smaller than md / 768px) -->
            <button type="button"
                    onclick="toggleSidebar()"
                    class="md:hidden p-2 -ml-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none"
                    aria-label="Open sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <span class="font-bold text-xl text-gray-900">
                @yield('title', 'Customer Reports')
            </span>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (sidebar) sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
