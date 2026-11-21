<!-- Sidebar -->
<aside class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200 shrink-0">
    
    <!-- Top Brand Box (Matches h-16 of your top nav bar) -->
    <div class="flex items-center justify-center h-16 bg-gray-900 shrink-0">
        <span class="text-white font-bold tracking-wider uppercase">CUSTOMER REPORTS</span>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-3 py-4 bg-white space-y-1">

            <a href="{{ route('customers') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('customers') ? 'bg-blue-50 text-black-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-people-fill"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('generate-reports') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('generate-reports') ? 'bg-blue-50 text-black-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Generate Report</span>
            </a>

        </nav>
    </div>

    <!-- Main content -->
    <!--<div class="flex flex-col flex-1 overflow-y-auto">
        <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
            <div class="flex items-center px-4">
                <button class="text-gray-500 focus:outline-none focus:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <input class="mx-4 w-full border rounded-md px-4 py-2" type="text" placeholder="Search">
            </div>
            <div class="flex items-center pr-4">

                <button
                    class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l-7-7 7-7m5 14l7-7-7-7" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-4">
            <h1 class="text-2xl font-bold">Welcome to Daystar Daycare </h1>
            <p class="mt-2 text-gray-600">Raising tomorrow's leaders.</p>
        </div>
    </div>-->

</aside>