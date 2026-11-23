<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Reports')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/side-nav.css',
        'resources/css/pages/customers.css',
        'resources/js/app.js'
    ])
    <script defer src="https://jsdelivr.net"></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex">

    @include('partials.side-nav')

    <div class="flex-1 flex flex-col">
        
        <!-- Top Navigation Bar -->
<!-- Top Navigation Bar (Exact same h-16 height as sidebar header) -->
    <nav class="h-16 bg-white border-b border-gray-200 px-6 flex items-center shrink-0">
        <span class="font-bold text-xl text-gray-900">@yield('title', 'Customer Reports')</span>
    </nav>


        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
