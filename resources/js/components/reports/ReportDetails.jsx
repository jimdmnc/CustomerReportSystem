import CustomerRangeSelect from './CustomerRangeSelect';

export default function ReportDetails({
    customers = [],
    customerFrom,
    setCustomerFrom,
    customerTo,
    setCustomerTo,
    isAllSelected,
    dateFrom,
    setDateFrom,
    dateTo,
    setDateTo,
    onProcess,
    onReset,
}) {

    return (
        <section className="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 className="mb-5 text-base font-bold text-gray-900">Sales Invoice</h2>
            <form onSubmit={onProcess} className="space-y-4">
                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <CustomerRangeSelect
                        label="Customer Code From:"
                        placeholder="From:"
                        customers={customers}
                        value={customerFrom}
                        onChange={setCustomerFrom}
                        disabled={isAllSelected}
                    />
                    <CustomerRangeSelect
                        label="Customer Code To:"
                        placeholder="To:"
                        customers={customers}
                        value={customerTo}
                        onChange={setCustomerTo}
                        disabled={isAllSelected}
                    />
                </div>

                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label className="mb-1 block text-xs font-medium text-gray-600">Date From</label>
                        <input
                            type="date"
                            value={dateFrom}
                            max={dateTo || undefined}
                            onChange={(e) => setDateFrom(e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800"
                        />
                    </div>
                    <div>
                        <label className="mb-1 block text-xs font-medium text-gray-600">Date To</label>
                        <input
                            type="date"
                            value={dateTo}
                            min={dateFrom || undefined}
                            onChange={(e) => setDateTo(e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800"
                        />
                    </div>
                </div>

                <div className="flex gap-3 pt-2">
                    <button
                        type="submit"
                        className="flex-1 rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#6b727d]">
                        Process
                    </button>
                    <button
                        type="button"
                        onClick={onReset}
                        className="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Reset
                    </button>
                </div>
            </form>
        </section>
    );
}
