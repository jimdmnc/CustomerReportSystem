document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('customer-search');
    const customerList = document.getElementById('customer-list');
    const paginationLinks = document.getElementById('pagination-links');

    if (!searchInput || !customerList) return;

    let debounceTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        const baseUrl = this.dataset.url;

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`${baseUrl}?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                const customers = result.data;

                // Update pagination buttons to match filtered results
                if (paginationLinks) {
                    paginationLinks.innerHTML = result.links || '';
                }

                // Keep URL in sync without reloading
                const nextUrl = query ? `${baseUrl}?search=${encodeURIComponent(query)}` : baseUrl;
                window.history.replaceState({}, '', nextUrl);

                if (customers.length === 0) {
                    customerList.innerHTML = `
                        <li class="p-6 text-center text-xs text-gray-400">
                            No customers found.
                        </li>`;
                    return;
                }

                customerList.innerHTML = customers.map(item => `
                    <li class="p-3.5 text-sm flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">${escapeHtml(item.name)}</span>
                        <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded">
                            ${escapeHtml(item.cust_num)}
                        </span>
                    </li>
                `).join('');
            } catch (error) {
                console.error('Live search error:', error);
            }
        }, 200);
    });

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, match => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[match]));
    }
});
