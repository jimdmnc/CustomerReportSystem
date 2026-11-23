


<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop"
     onclick="toggleSidebar()"
     class="fixed inset-0 bg-gray-900/50 z-20 hidden md:hidden transition-opacity">
</div>

<!-- Sidebar -->
<aside id="mobile-sidebar"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
    
    <!-- Top Brand Box -->
    <div class="flex items-center justify-between px-4 h-16 bg-gray-900 shrink-0">
        <span class="text-white font-bold tracking-wider uppercase text-sm md:mx-auto">
            CUSTOMER REPORTS
        </span>

        <!-- Close Button (Mobile Only) -->
        <button type="button"
                onclick="toggleSidebar()"
                class="md:hidden text-gray-300 hover:text-white p-1.5 rounded-lg focus:outline-none"
                aria-label="Close sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-3 py-4 bg-white space-y-1">
            <a href="{{ route('customers') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('customers') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-people-fill"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Generate Report</span>
            </a>
        </nav>
    </div>

</aside>
