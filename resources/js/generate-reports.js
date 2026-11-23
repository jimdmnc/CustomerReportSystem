// Customer search (step 1) + date filter on inv_date (step 2).
document.addEventListener('DOMContentLoaded', function () {

    const root = document.getElementById('report-page');
    const urls = { search: root.dataset.searchUrl, invoices: root.dataset.invoicesUrl, pdf: root.dataset.pdfUrl };

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
    let loadedLineCount = 0;      // rows currently shown in the preview

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
        loadedLineCount = 0;
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
        loadedLineCount = lines.length;
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
        loadedLineCount = 0;

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

    // ---------- Forms (email not built yet) ----------
    reportForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!selectedCustomer) {
            alert('Please search and select a valid customer.');
            searchInput.focus();
            return;
        }
        if (loadedLineCount === 0) {
            alert('There are no invoice records to export for this selection.');
            return;
        }

        const params = new URLSearchParams({ customer_name: selectedCustomer.customer_name });
        if (dateFrom.value) params.set('date_from', dateFrom.value);
        if (dateTo.value)   params.set('date_to', dateTo.value);

        window.open(`${urls.pdf}?${params}`, '_blank');   // opens the PDF in a new tab
    });
    reportForm.addEventListener('reset', () => setTimeout(() => {
        clearSelection();
        hideSuggestions();
        dateTo.min = '';
        updateDatePreview();
    }, 0));
    $('email_form').addEventListener('submit', e => e.preventDefault());

    updateDatePreview();
});