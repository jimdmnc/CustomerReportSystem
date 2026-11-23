@extends('layouts.app')
@section('title', 'Generate Reports')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    <!-- Report Request & Email Management -->
    <div class="lg:col-span-4 space-y-6">

        <!-- Card 1: Report Request -->
        <div class="card bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Report Details</h2>

            <form action="#" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Customer
                    </label>

                    <input type="text" id="customer_search" list="customer_suggestions" placeholder="Search customer name or code..." autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">

                    <datalist id="customer_suggestions">
                        @foreach($customers as $customerOption)
                            <option
                                value="{{ $customerOption['customer_name'] }} ({{ $customerOption['customer_number'] }})"
                                data-customer-number="{{ $customerOption['customer_number'] }}"
                                data-customer-sequence="{{ $customerOption['customer_sequence'] }}"
                                data-customer-name="{{ $customerOption['customer_name'] }}"
                                data-customer-address="{{ $customerOption['address'] }}"
                            ></option>
                        @endforeach
                    </datalist>

                    <!--<input type="hidden" name="customer_number" id="customer_number">
                    <input type="hidden" name="customer_sequence" id="customer_sequence">-->
                </div>

                <!-- Customer Code & Email Address -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Customer Code</label>
                        <input type="text" name="customer_code" value="{{ old('customer_code') }}" class="w-full rounded-lg border 
                            border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email Address</label>
                        <input type="email" name="email" class="w-full rounded-lg border 
                            border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Report Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Report Type</label>
                    <select name="report_type" class="w-full rounded-lg border 
                            border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                        <option value="soa" selected>Statement of Account</option>
                        <option value="invoice_summary">Invoice Summary</option>
                        <option value="aging_report">Aging Report</option>
                    </select>
                </div>

                <!-- Date From & Date To -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Date From
                        </label>

                        <input type="date" name="date_from" id="date_from" required class="w-full rounded-lg border
                            border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Date To
                        </label>

                        <input type="date" name="date_to" id="date_to" required class="w-full rounded-lg border 
                            border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-gray-600 hover:bg-blue-700 text-white text-sm font-medium 
                            px-4 py-2 rounded-lg transition">
                        Generate PDF
                    </button>
                    <button type="reset" class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium 
                            px-4 py-2 rounded-lg border border-gray-300 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Email Management -->
        <div class="card bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Email Management</h2>

            <form action="#" method="POST" class="space-y-4">
                @csrf

                <!-- Recipient Email -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Recipient Email</label>
                    <input type="email" name="recipient_email" class="w-full rounded-lg border 
                        border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Subject</label>
                    <input type="text" name="subject" class="w-full rounded-lg border 
                        border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Message</label>
                    <textarea name="message" rows="5" class="w-full rounded-lg border 
                        border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                            
                    </textarea>
                </div>

                <!-- Attachment Badge & Status -->
                <div class="flex items-start justify-between gap-3">
                    <div id="pdf-filename-badge"
                        class="min-w-0 break-all bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-3 py-1.5 text-xs font-medium">
                        {{ $report->file_name }} ({{ $report->file_size }})
                    </div>

                    <div class="text-xs text-gray-600 shrink-0 whitespace-nowrap pt-1">
                        Status:
                        <span class="inline-block bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded">
                            {{ $report->status }}
                        </span>
                    </div>
                </div>



                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-gray-600 hover:bg-blue-700 text-white text-sm font-medium 
                            px-4 py-2 rounded-lg transition">
                        Send Email
                    </button>
                    <button type="button" class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium 
                            px-4 py-2 rounded-lg border border-gray-300 transition">
                        Save Draft
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- RIGHT COLUMN PDF Preview -->
    <div class="card bg-white lg:col-span-8 rounded-xl shadow-sm p-6 overflow-x-auto">
        <h2 class="text-base font-bold text-gray-900 mb-4">PDF Preview</h2>

        <!-- Outer Light Frame -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
            
            <!-- Inner Paper Document -->
            <div class="bg-white border border-gray-200 shadow-md rounded-sm p-8">
                
                <!-- Document Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>

                        <h3 class="text-base font-bold tracking-wide text-gray-900 uppercase">
                            Customers of Printwell
                        </h3>

                        <div class="mt-2 text-xs text-gray-600 space-y-0.5">
                            <p id="preview-customer-name" class="font-medium text-gray-800">
                                {{ $customer->name ?: 'Select a customer' }}
                            </p>

                            <p>
                                Customer Code:
                                <span id="preview-customer-code">
                                    {{ $customer->code ?: '---' }}
                                </span>
                            </p>

                            <p id="preview-customer-address">
                                {{ $customer->address ?: '---' }}
                            </p>
                        </div>

                    <span id="preview-date-range" class="text-xs text-gray-500">
                        Select report dates
                    </span>
                </div>

                <!-- Transactions Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500 font-medium">
                                <th class="py-2.5 pr-4">Date</th>
                                <th class="py-2.5 px-4">Doc No.</th>
                                <th class="py-2.5 px-4">Debit</th>
                                <th class="py-2.5 px-4">Credit</th>
                                <th class="py-2.5 pl-4">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach($transactions as $row)
                                <tr>
                                    <td>{{ $row->date }}</td>
                                    <td>{{ $row->doc_no }}</td>
                                    <td>${{ number_format($row->debit, 2) }}</td>
                                    <td>${{ number_format($row->credit, 2) }}</td>
                                    <td>${{ number_format($row->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Customer elements
    const customerSearch = document.getElementById('customer_search');
    const customerSuggestions = document.getElementById('customer_suggestions');
    const customerNumber = document.getElementById('customer_number');
    const customerSequence = document.getElementById('customer_sequence');

    // Preview elements
    const previewName = document.getElementById('preview-customer-name');
    const previewCode = document.getElementById('preview-customer-code');
    const previewAddress = document.getElementById('preview-customer-address');
    const previewDateRange = document.getElementById('preview-date-range');

    // Date elements
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');

    // Customer selection
    customerSearch.addEventListener('input', function () {

        const selectedOption = Array.from(customerSuggestions.options)
            .find(option => option.value === this.value);

        if (!selectedOption) {
            customerNumber.value = '';
            customerSequence.value = '';

            previewName.textContent = 'Select a valid customer';
            previewCode.textContent = '---';
            previewAddress.textContent = '---';

            return;
        }

        customerNumber.value = selectedOption.dataset.customerNumber;
        customerSequence.value = selectedOption.dataset.customerSequence;

        previewName.textContent = selectedOption.dataset.customerName;
        previewCode.textContent = selectedOption.dataset.customerNumber;
        previewAddress.textContent = selectedOption.dataset.customerAddress;
    });

    // Format date for PDF Preview
    function formatDate(value) {
        if (!value) return '';

        const [year, month, day] = value.split('-').map(Number);
        const date = new Date(year, month - 1, day);

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    // Update preview date range
    function updateDatePreview() {

        const from = dateFrom.value;
        const to = dateTo.value;

        if (!from || !to) {
            previewDateRange.textContent = 'Select report dates';
            return;
        }

        previewDateRange.textContent =
            `${formatDate(from)} – ${formatDate(to)}`;
    }

    // Date validation and preview
    dateFrom.addEventListener('change', function () {

        dateTo.min = this.value;

        if (dateTo.value && dateTo.value < this.value) {
            dateTo.value = '';
        }

        updateDatePreview();
    });

    dateTo.addEventListener('change', function () {

        if (dateFrom.value && this.value < dateFrom.value) {
            alert('Date To cannot be earlier than Date From.');
            this.value = '';
        }

        updateDatePreview();
    });

    // Initialize customer field from the server-selected customer
    const initialCustomer = Array.from(customerSuggestions.options)
        .find(option =>
            option.dataset.customerNumber === customerNumber.value
        );

    if (initialCustomer) {
        customerSearch.value = initialCustomer.value;
    }

});
</script>