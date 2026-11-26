const reportService = {
    async searchCustomers(query, signal) {
        const response = await fetch(`/generate-reports/customers/search?q=${encodeURIComponent(query)}`, {
            signal,
            headers: { Accept: 'application/json' },
        });
        if (!response.ok) throw new Error('Unable to search customers.');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
    },

    generatePdf(params) {
        const query = new URLSearchParams(params);
        const openedWindow = window.open(`/generate-reports/pdf?${query.toString()}`, '_blank');
        if (!openedWindow) throw new Error('Pop-up blocked. Allow pop-ups and try again.');
        return openedWindow;
    },

    async sendEmail(data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const response = await fetch('/generate-reports/email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            body: JSON.stringify(data),
        });
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Failed to send report email.');
        }
        return response.json().catch(() => ({}));
    },
};

export default reportService;
