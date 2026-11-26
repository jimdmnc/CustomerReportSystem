import ReportDetails from '../components/reports/ReportDetails';
import ReportActions from '../components/reports/ReportActions';
import useReportForm from '../hooks/useReportForm';

export default function GenerateReportsPage({ customers = [] }) {
    const reportForm = useReportForm(customers);

    return (
        <div className="grid grid-cols-1 items-start gap-12 lg:grid-cols-12">
            <div className="space-y-6 lg:col-span-12">
                <ReportDetails
                    customers={customers}
                    customerFrom={reportForm.customerFrom}
                    setCustomerFrom={reportForm.setCustomerFrom}
                    customerTo={reportForm.customerTo}
                    setCustomerTo={reportForm.setCustomerTo}
                    isAllSelected={reportForm.isAllSelected}
                    dateFrom={reportForm.dateFrom}
                    setDateFrom={reportForm.setDateFrom}
                    dateTo={reportForm.dateTo}
                    setDateTo={reportForm.setDateTo}
                    onProcess={reportForm.handleProcess}
                    onReset={reportForm.handleReset}
                />

                <ReportActions
                    isAllSelected={reportForm.isAllSelected}
                    isProcessing={reportForm.isProcessing}
                    onToggleSelectAll={reportForm.toggleSelectAll}
                    onPreview={reportForm.handlePreview}
                    onPrintAndSend={reportForm.handlePrintAndSend}
                />
            </div>
        </div>
    );
}
