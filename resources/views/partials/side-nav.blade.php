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

</aside>