import { useState } from 'react';
import reportService from '../services/reportService';

export default function useReportForm(customers = []) {
    const [customerFrom, setCustomerFrom] = useState(null);
    const [customerTo, setCustomerTo] = useState(null);
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [isAllSelected, setIsAllSelected] = useState(false);
    const [isProcessing, setIsProcessing] = useState(false);

    const validateForm = () => {
        if (!isAllSelected && (!customerFrom || !customerTo)) {
            alert('Please select both Customer Code From and To, then select invoice.');
            return false;
        }
        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert('Date From cannot be later than Date To.');
            return false;
        }
        return true;
    };

    const getReportPayload = () => ({
        select_all: isAllSelected,
        customer_code_from: customerFrom?.code ?? '',
        customer_code_to: customerTo?.code ?? '',
        date_from: dateFrom,
        date_to: dateTo,
    });

    const toggleSelectAll = (checked) => {
        setIsAllSelected(checked);
        if (checked && customers.length > 0) {
            setCustomerFrom(customers);
            setCustomerTo(customers[customers.length - 1]);
        } else if (!checked) {
            setCustomerFrom(null);
            setCustomerTo(null);
        }
    };

    const handleProcess = (event) => {
        event.preventDefault();
        if (!validateForm()) return;
        try {
            reportService.generatePdf(getReportPayload());
        } catch (error) {
            alert(error.message);
        }
    };

    const handlePreview = () => {
        if (!validateForm()) return;
        reportService.generatePdf({ ...getReportPayload(), preview: true });
    };

    const handlePrintAndSend = async () => {
        if (!validateForm()) return;
        setIsProcessing(true);
        try {
            const payload = getReportPayload();
            reportService.generatePdf(payload);
            await reportService.sendEmail(payload);
            alert('Report printed and sent successfully.');
        } catch (error) {
            alert(error.message || 'Unable to print and send report.');
        } finally {
            setIsProcessing(false);
        }
    };

    const handleReset = () => {
        setCustomerFrom(null);
        setCustomerTo(null);
        setDateFrom('');
        setDateTo('');
        setIsAllSelected(false);
    };

    return {
        customerFrom,
        setCustomerFrom,
        customerTo,
        setCustomerTo,
        dateFrom,
        setDateFrom,
        dateTo,
        setDateTo,
        isAllSelected,
        isProcessing,
        toggleSelectAll,
        handleProcess,
        handlePreview,
        handlePrintAndSend,
        handleReset,
    };
}
