@extends('layouts.app')

@section('title', 'Generate Reports')

@section('content')

    <div id="report-page"
         data-search-url="{{ route('reports.customers.search') }}"
         data-invoices-url="{{ route('reports.invoices.preview') }}"
         data-pdf-url="{{ route('reports.pdf') }}"
         class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    <!-- Report Request & Email Management -->
    <div class="lg:col-span-4 space-y-6">

        <!-- Card 1: Report Request -->
        <div class="card bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Report Details</h2>

            <form id="report_form" action="#" method="POST" class="space-y-4">
                @csrf

                <!-- Customer Search -->
                <div class="relative">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Customer</label>

                    <input type="text" id="customer_search" placeholder="Search customer name or code..." autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"
                    >

                    <!-- Search Results-->
                    <div
                        id="customer_suggestions"
                        class="absolute z-50 mt-1 hidden w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
                        <div id="customer_suggestions_list" class="max-h-60 overflow-y-auto"></div>

                        <div id="customer_status" class="hidden px-3 py-3 text-center text-sm text-gray-500"></div>
                    </div>

                    <!-- Selected Customer Values -->
                    <input type="hidden" name="customer_number" id="customer_number">
                    <input type="hidden" name="customer_sequence" id="customer_sequence">
                </div>

                <!-- Customer Code & Report Type -->
                <div class="flex items-end gap-2">
                    <div class="w-24 shrink-0">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Customer Code</label>
                        <input type="text" id="customer_code" readonly placeholder="Code"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-2.5 py-2 text-xs text-gray-600">
                    </div>

                    <div class="flex-1 min-w-0">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Report Type</label>
                        <select name="report_type" id="report_type"
                            class="w-full rounded-lg border border-gray-300 bg-white px-2.5 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none">
                            <option value="customer_account_report" selected>Customer Account Report</option>
                        </select>
                    </div>
                </div>



                <!-- Date From & Date To (optional filters for the preview) -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                        <input type="date" name="date_from" id="date_from"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                        <input type="date" name="date_to" id="date_to"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-gray-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Generate PDF
                    </button>
                    <button type="reset"
                            class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Email Management -->
        <div class="card bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Email Management</h2>

            <form id="email_form" action="#" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Recipient Email</label>
                    <input type="email" name="recipient_email" id="recipient_email"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Subject</label>
                    <input type="text" name="subject" id="subject"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Message</label>
                    <textarea name="message" id="message" rows="5"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <div id="pdf-filename-badge" class="bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-3 py-1.5 text-xs font-medium">
                        No report generated yet
                    </div>
                    <div class="text-xs text-gray-600">
                        Status:
                        <span id="report-status" class="inline-block bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded">Pending</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-gray-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Send Email
                    </button>
                    <button type="button"
                            class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 transition">
                        Save Draft
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- RIGHT COLUMN PDF Preview -->
    <div class="card bg-white lg:col-span-8 rounded-xl shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">PDF Preview</h2>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
            <div class="bg-white border border-gray-200 shadow-md rounded-sm p-8">

                <!-- Document Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-base font-bold tracking-wide text-gray-900 uppercase">
                            Customers of Printwell
                        </h3>

                        <div class="mt-2 text-xs text-gray-600 space-y-0.5">
                            <p id="preview-customer-name" class="font-medium text-gray-800">Select a customer</p>
                            <p>Customer Code: <span id="preview-customer-code">---</span></p>
                            <p id="preview-customer-address">---</p>
                        </div>
                    </div>

                    <div class="text-right text-xs text-gray-500 space-y-0.5">
                        <p id="preview-date-range">All dates</p>
                        <p id="preview-summary"></p>
                    </div>
                </div>

                <!-- Invoice Lines Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500 font-medium">
                                <th class="py-2.5 pr-3">Inv. Date</th>
                                <th class="py-2.5 px-3">Invoice No.</th>
                                <th class="py-2.5 px-3">Line</th>
                                <th class="py-2.5 px-3">Cust. PO</th>
                                <th class="py-2.5 px-3">Item</th>
                                <th class="py-2.5 px-3">DO No.</th>
                                <th class="py-2.5 px-3 text-right">Qty</th>
                                <th class="py-2.5 px-3 text-right">Unit Price</th>
                                <th class="py-2.5 pl-3 text-right">Amount</th>
                            </tr>
                        </thead>

                        <tbody id="transaction-table-body" class="divide-y divide-gray-100 text-gray-700">
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-400">
                                    Select a customer to display invoice records.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@vite(['resources/css/app.css', 'resources/js/app.js'])

@endsection