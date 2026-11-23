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
<!-- resources/views/layouts/app.blade.php -->
<body class="bg-gray-100 text-gray-900 min-h-screen">

    @include('partials.side-nav')

    <div class="flex-1 flex flex-col">

        <nav class="h-16 bg-white border-b border-gray-200 px-6 flex items-center shrink-0">
            <span class="font-bold text-xl text-gray-900">
                @yield('title', 'Customer Reports')
            </span>
        </nav>

        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
