@extends('layouts.app')

@section('title', 'Generate Reports')

@section('content')

    <div id="report-page"
         data-search-url="{{ route('reports.customers.search') }}"
         data-invoices-url="{{ route('reports.invoices.preview') }}"
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

                    <input
                        type="text"
                        id="customer_search"
                        placeholder="Search customer name or code..."
                        autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"
                    >

                    <!-- Search Results (filled by JS from the server) -->
                    <div
                        id="customer_suggestions"
                        class="absolute z-50 mt-1 hidden w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg"
                    >
                        <div id="customer_suggestions_list" class="max-h-60 overflow-y-auto"></div>

                        <div id="customer_status" class="hidden px-3 py-3 text-center text-sm text-gray-500"></div>
                    </div>

                    <!-- Selected Customer Values -->
                    <input type="hidden" name="customer_number" id="customer_number">
                    <input type="hidden" name="customer_sequence" id="customer_sequence">
                </div>

                <!-- Customer Code & Email Address -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Customer Code</label>
                        <input
                            type="text"
                            id="customer_code"
                            readonly
                            placeholder="Select a customer"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-600"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email Address</label>
                        <input type="email" name="email" id="email"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Report Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Report Type</label>
                    <select
                        name="report_type"
                        id="report_type"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:outline-none"
                    >
                        <option value="customer_account_report" selected>Customer Account Report</option>
                        <option value="invoice_summary">Invoice Summary</option>
                        <option value="aging_report">Aging Report</option>
                    </select>
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

        <!-- Card 2: Email Management (not wired to a backend yet) -->
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

<script>
// Customer search (step 1) + date filter on inv_date (step 2).
document.addEventListener('DOMContentLoaded', function () {

    const root = document.getElementById('report-page');
    const urls = { search: root.dataset.searchUrl, invoices: root.dataset.invoicesUrl };

    const $ = id => document.getElementById(id);

    const searchInput      = $('customer_search');
    const suggestionsBox   = $('customer_suggestions');
    const suggestionsList  = $('customer_suggestions_list');
    const statusBox        = $('customer_status');
    const customerNumber   = $('customer_number');
    const customerSequence = $('customer_sequence');
    const customerCode     = $('customer_code');
    const dateFrom         = $('date_from');
    const dateTo           = $('date_to');
    const reportForm       = $('report_form');

    const previewName      = $('preview-customer-name');
    const previewCode      = $('preview-customer-code');
    const previewAddress   = $('preview-customer-address');
    const previewDates     = $('preview-date-range');
    const previewSummary   = $('preview-summary');
    const tableBody        = $('transaction-table-body');

    let selectedCustomer = null;
    let searchTimer = null;
    let searchAbort = null;
    let invoiceAbort = null;

    // ---------- Helpers ----------
    const showSuggestions = () => suggestionsBox.classList.remove('hidden');
    const hideSuggestions = () => suggestionsBox.classList.add('hidden');

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, ch => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[ch]));
    }

    function formatDate(value) {
        if (!value) return '';
        const [y, m, d] = value.split('-').map(Number);
        return new Date(y, m - 1, d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    const formatMoney = n => Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const formatQty   = n => Number(n).toLocaleString('en-US', { maximumFractionDigits: 2 });
    const formatPrice = n => Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 5 });

    async function getJson(url, params, signal) {
        const response = await fetch(`${url}?${new URLSearchParams(params)}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            signal,
        });
        if (!response.ok) throw new Error(response.status);
        return (await response.json()).data;
    }

    function setTableMessage(message) {
        tableBody.innerHTML = `<tr><td colspan="9" class="py-6 text-center text-gray-400">${escapeHtml(message)}</td></tr>`;
    }

    function setCustomerPreview(name, code, address) {
        previewName.textContent    = name;
        previewCode.textContent    = code;
        previewAddress.textContent = address;
    }

    // ---------- Selection ----------
    function clearSelection() {
        selectedCustomer       = null;
        customerNumber.value   = '';
        customerSequence.value = '';
        customerCode.value     = '';
        previewSummary.textContent = '';
        setCustomerPreview('Select a customer', '---', '---');
        setTableMessage('Select a customer to display invoice records.');
    }

    function selectCustomer(customer) {
        selectedCustomer       = customer;
        customerNumber.value   = customer.customer_number;
        customerSequence.value = customer.customer_sequence;
        customerCode.value     = customer.customer_number;
        searchInput.value      = customer.customer_name;
        setCustomerPreview(customer.customer_name, customer.customer_number, '---');
        hideSuggestions();
        loadInvoices();
    }

    // ---------- Customer search ----------
    function renderSuggestions(customers) {
        suggestionsList.innerHTML = '';
        statusBox.classList.toggle('hidden', customers.length > 0);
        if (!customers.length) statusBox.textContent = 'No matching customers found.';

        customers.forEach(customer => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'w-full border-b border-gray-100 px-3 py-2 text-left hover:bg-blue-50 last:border-b-0';
            button.innerHTML = `
                <div class="text-sm font-medium text-gray-800">${escapeHtml(customer.customer_name)}</div>
                <div class="text-xs text-gray-500">${escapeHtml(customer.customer_number)} · Seq ${customer.customer_sequence}</div>`;
            button.addEventListener('click', () => selectCustomer(customer));
            suggestionsList.appendChild(button);
        });
    }

    async function runSearch(term) {
        if (searchAbort) searchAbort.abort();
        searchAbort = new AbortController();

        suggestionsList.innerHTML = '';
        statusBox.textContent = 'Searching...';
        statusBox.classList.remove('hidden');
        showSuggestions();

        try {
            renderSuggestions(await getJson(urls.search, { q: term }, searchAbort.signal));
        } catch (error) {
            if (error.name === 'AbortError') return;
            statusBox.textContent = 'Could not load customers. Please try again.';
        }
    }

    searchInput.addEventListener('input', function () {
        clearSelection();
        clearTimeout(searchTimer);
        const term = searchInput.value.trim();
        if (term.length < 2) return hideSuggestions();
        searchTimer = setTimeout(() => runSearch(term), 300);
    });

    searchInput.addEventListener('keydown', e => { if (e.key === 'Escape') hideSuggestions(); });

    document.addEventListener('click', function (event) {
        if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) hideSuggestions();
    });

    // ---------- Date filter (inv_date) ----------
    function updateDatePreview() {
        if (dateFrom.value && dateTo.value) {
            previewDates.textContent = `${formatDate(dateFrom.value)} – ${formatDate(dateTo.value)}`;
        } else if (dateFrom.value) {
            previewDates.textContent = `From ${formatDate(dateFrom.value)}`;
        } else if (dateTo.value) {
            previewDates.textContent = `Up to ${formatDate(dateTo.value)}`;
        } else {
            previewDates.textContent = 'All dates';
        }
    }

    function renderInvoices(lines) {
        if (!lines.length) {
            previewSummary.textContent = '0 lines';
            return setTableMessage('No invoice records found for the selected dates.');
        }

        if (lines[0].address) previewAddress.textContent = lines[0].address;

        const currency = lines[0].currency || '';
        const total = lines.reduce((sum, line) => sum + line.line_amount, 0);

        tableBody.innerHTML = lines.map(line => `
            <tr>
                <td class="py-2.5 pr-3 whitespace-nowrap">${escapeHtml(formatDate(line.inv_date))}</td>
                <td class="py-2.5 px-3 whitespace-nowrap">${escapeHtml(line.inv_num)}</td>
                <td class="py-2.5 px-3">${line.inv_line}</td>
                <td class="py-2.5 px-3">${escapeHtml(line.cust_po || '—')}</td>
                <td class="py-2.5 px-3">${escapeHtml(line.item || '—')}</td>
                <td class="py-2.5 px-3">${escapeHtml(line.do_num || '—')}</td>
                <td class="py-2.5 px-3 text-right">${formatQty(line.qty_invoiced)}</td>
                <td class="py-2.5 px-3 text-right">${formatPrice(line.unit_price)}</td>
                <td class="py-2.5 pl-3 text-right font-medium">${formatMoney(line.line_amount)}</td>
            </tr>`).join('') + `
            <tr class="border-t-2 border-gray-300 font-semibold text-gray-900">
                <td colspan="8" class="py-3 px-3 text-right">Total ${escapeHtml(currency)}</td>
                <td class="py-3 pl-3 text-right">${formatMoney(total)}</td>
            </tr>`;

        previewSummary.textContent = `${lines.length} line${lines.length === 1 ? '' : 's'}`;
    }

    async function loadInvoices() {
        if (!selectedCustomer) return setTableMessage('Select a customer first.');

        if (invoiceAbort) invoiceAbort.abort();
        invoiceAbort = new AbortController();

        const params = { customer_name: selectedCustomer.customer_name };
        if (dateFrom.value) params.date_from = dateFrom.value;   // inv_date >= date_from
        if (dateTo.value)   params.date_to   = dateTo.value;     // inv_date <= date_to (whole day)

        setTableMessage('Loading invoice records...');

        try {
            renderInvoices(await getJson(urls.invoices, params, invoiceAbort.signal));
        } catch (error) {
            if (error.name === 'AbortError') return;
            setTableMessage('Could not load invoice records. Please try again.');
        }
    }

    dateFrom.addEventListener('change', function () {
        dateTo.min = this.value;
        if (dateTo.value && dateTo.value < this.value) dateTo.value = '';
        updateDatePreview();
        loadInvoices();
    });

    dateTo.addEventListener('change', function () {
        if (dateFrom.value && this.value < dateFrom.value) {
            alert('Date To cannot be earlier than Date From.');
            this.value = '';
        }
        updateDatePreview();
        loadInvoices();
    });

    // ---------- Forms (PDF / email not built yet) ----------
    reportForm.addEventListener('submit', e => e.preventDefault());
    reportForm.addEventListener('reset', () => setTimeout(() => {
        clearSelection();
        hideSuggestions();
        dateTo.min = '';
        updateDatePreview();
    }, 0));
    $('email_form').addEventListener('submit', e => e.preventDefault());

    updateDatePreview();
});
</script>


@endsection