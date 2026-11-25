@extends('layouts.app')
@section('title', 'Customers')

@section('content')

    <div class="mb-4 relative">
        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text" id="customer-search" data-url="{{ route('customers') }}" value="{{ request('search') }}"
            placeholder="Type to search customer name or code..." autocomplete="off"
            class="w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
    </div>

    <ul id="customer-list" class="divide-y divide-gray-200 bg-white rounded-xl border border-gray-200 shadow-sm">
        @forelse($customers as $item)
            <li class="p-3.5 text-sm flex justify-between items-center hover:bg-gray-50 transition">
                <span class="font-medium text-gray-900">{{ $item->name }}</span>
                <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded">
                    {{ $item->cust_num }}
                </span>
            </li>
        @empty
            <li class="p-6 text-center text-xs text-gray-400">No customers found.</li>
        @endforelse
    </ul>

    @if($customers->hasPages())
        <div class="mt-4">
            {{ $customers->onEachSide(1)->links() }}
        </div>
    @endif


@endsection

@push('scripts')
    @vite('resources/js/customers.js')
@endpush
