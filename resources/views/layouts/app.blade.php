<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Reports')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/partials/side-nav.css',
        'resources/css/pages/customers.css',
        'resources/js/app.js'
    ])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex">

    @include('partials.side-nav')

    <div class="flex-1 flex flex-col">
        
        <!-- Top Navigation Bar -->
        <nav class="h-16 bg-white border-b border-gray-200 px-6 flex items-center">
            <span class="font-bold text-xl text-gray-800">@yield('title', 'Customer Reports')</span>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>
