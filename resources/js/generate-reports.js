// Step 1 — Responsibility: customer search box only.
document.addEventListener('DOMContentLoaded', function () {

    const searchUrl = document.getElementById('report-page').dataset.searchUrl;

    const searchInput      = document.getElementById('customer_search');
    const suggestionsBox   = document.getElementById('customer_suggestions');
    const suggestionsList  = document.getElementById('customer_suggestions_list');
    const statusBox        = document.getElementById('customer_status');
    const customerNumber   = document.getElementById('customer_number');
    const customerSequence = document.getElementById('customer_sequence');
    const customerCode     = document.getElementById('customer_code');
    const reportForm       = document.getElementById('report_form');

    let searchTimer = null;
    let searchAbort = null;

    const showSuggestions = () => suggestionsBox.classList.remove('hidden');
    const hideSuggestions = () => suggestionsBox.classList.add('hidden');

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, ch => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[ch]));
    }

    function clearSelection() {
        customerNumber.value   = '';
        customerSequence.value = '';
        customerCode.value     = '';
    }

    function selectCustomer(customer) {
        customerNumber.value   = customer.customer_number;
        customerSequence.value = customer.customer_sequence;
        customerCode.value     = customer.customer_number;   // Customer Code fills automatically
        searchInput.value      = customer.customer_name;
        hideSuggestions();
    }

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
            const response = await fetch(`${searchUrl}?${new URLSearchParams({ q: term })}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: searchAbort.signal,
            });
            if (!response.ok) throw new Error(response.status);
            renderSuggestions((await response.json()).data);
        } catch (error) {
            if (error.name === 'AbortError') return;
            statusBox.textContent = 'Could not load customers. Please try again.';
        }
    }

    searchInput.addEventListener('input', function () {
        clearSelection();
        clearTimeout(searchTimer);

        const term = searchInput.value.trim();
        if (term.length < 2) {
            hideSuggestions();
            return;
        }
        searchTimer = setTimeout(() => runSearch(term), 300);
    });

    searchInput.addEventListener('keydown', e => { if (e.key === 'Escape') hideSuggestions(); });

    document.addEventListener('click', function (event) {
        if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) hideSuggestions();
    });

    reportForm.addEventListener('submit', e => e.preventDefault());          // not built yet
    reportForm.addEventListener('reset', () => setTimeout(() => { clearSelection(); hideSuggestions(); }, 0));
    document.getElementById('email_form').addEventListener('submit', e => e.preventDefault());
});