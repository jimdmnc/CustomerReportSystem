import { Checkbox } from '@headlessui/react';

export default function ReportActions({
    isAllSelected,
    isProcessing,
    isLoadingResults = false,
    reportResults = [],
    selectedRowIds = [],
    onToggleSelectAll,
    onToggleRow,
    onPreview,
    onPrintAndSend,
}) {
    return (
        <div>
            {/* 3 Action Buttons */}
            <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <button
                    type="button"
                    onClick={() => onToggleSelectAll(!isAllSelected)}
                    className={`flex w-full items-center justify-center gap-2.5 rounded-lg border px-4 py-2.5 text-sm font-medium transition ${
                        isAllSelected
                            ? 'border-gray-300 bg-blue-50 text-black-700'
                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                    }`}
                >
                    <Checkbox
                        checked={isAllSelected}
                        onChange={onToggleSelectAll}
                        onClick={(e) => e.stopPropagation()}
                        className="group flex size-4 items-center justify-center rounded border border-gray-300 bg-white data-checked:border-gray-600 data-checked:bg-gray-600"
                    >
                        <svg
                            className="size-3 stroke-white opacity-0 group-data-checked:opacity-100"
                            viewBox="0 0 14 14"
                            fill="none"
                        >
                            <path
                                d="M3 8L6 11L11 3.5"
                                strokeWidth={2}
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </Checkbox>
                    <span>Select All</span>
                </button>

                <button
                    type="button"
                    onClick={onPreview}
                    disabled={isProcessing}
                    className="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                >
                    Preview
                </button>

                <button
                    type="button"
                    onClick={onPrintAndSend}
                    disabled={isProcessing}
                    className="w-full rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#6b727d] disabled:opacity-50"
                >
                    {isProcessing ? 'Processing...' : 'Print & Send'}
                </button>
            </div>

            {/* Report Results List with Checkboxes */}
            <div className="mt-6 border-t border-gray-200 pt-5">
                <div className="flex items-center justify-between mb-3">
                    <h3 className="text-sm font-bold text-gray-900">Report Results</h3>
                    <span className="text-xs font-medium text-gray-500">
                        {selectedRowIds.length} of {reportResults.length} selected
                    </span>
                </div>

                {isLoadingResults ? (
                    <div className="rounded-lg border border-gray-200 bg-gray-50 py-8 text-center text-sm text-gray-500">
                        Loading report records...
                    </div>
                ) : reportResults.length === 0 ? (
                    <div className="rounded-lg border border-dashed border-gray-300 py-8 text-center text-sm text-gray-500">
                        No report results yet. Select a customer and date range, then click Process.
                    </div>
                ) : (
                    <div className="overflow-x-auto rounded-lg border border-gray-200">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th scope="col" className="w-10 px-3 py-2.5 text-left">
                                        <Checkbox
                                            checked={isAllSelected}
                                            onChange={onToggleSelectAll}
                                            aria-label="Select all rows"
                                            className="group flex size-4 cursor-pointer items-center justify-center rounded border border-gray-300 bg-white data-[checked]:border-blue-600 data-[checked]:bg-blue-600"
                                        >
                                            <svg
                                                className="size-3 stroke-white opacity-0 group-data-checked:opacity-100"
                                                viewBox="0 0 14 14"
                                                fill="none"
                                            >
                                                <path
                                                    d="M3 8L6 11L11 3.5"
                                                    strokeWidth={2}
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                />
                                            </svg>
                                        </Checkbox>
                                    </th>
                                    <th className="px-3 py-2.5 text-left text-xs font-semibold text-gray-600">
                                        Customer Code
                                    </th>
                                    <th className="px-3 py-2.5 text-left text-xs font-semibold text-gray-600">
                                        Customer Name
                                    </th>
                                    <th className="px-3 py-2.5 text-left text-xs font-semibold text-gray-600">
                                        Invoice No.
                                    </th>
                                    <th className="px-3 py-2.5 text-left text-xs font-semibold text-gray-600">
                                        Date
                                    </th>
                                    <th className="px-3 py-2.5 text-right text-xs font-semibold text-gray-600">
                                        Amount
                                    </th>
                                    <th className="px-3 py-2.5 text-center text-xs font-semibold text-gray-600">
                                        Status
                                    </th>
                                    <th className="px-3 py-2.5 text-center text-xs font-semibold text-gray-600">
                                        E-mail Address
                                    </th>
                                </tr>
                            </thead>

                            <tbody className="divide-y divide-gray-200 bg-white">
                                {reportResults.map((row) => {
                                    const isChecked = selectedRowIds.includes(row.id);

                                    return (
                                        <tr
                                            key={row.id}
                                            onClick={() => onToggleRow(row.id)}
                                            className={`cursor-pointer transition-colors ${
                                                isChecked ? 'bg-blue-50/60' : 'hover:bg-gray-50'
                                            }`}
                                        >
                                            <td
                                                className="px-3 py-2.5"
                                                onClick={(e) => e.stopPropagation()}
                                            >
                                                <Checkbox
                                                    checked={isChecked}
                                                    onChange={() => onToggleRow(row.id)}
                                                    className="group flex size-4 cursor-pointer items-center justify-center rounded border border-gray-300 bg-white data-[checked]:border-blue-600 data-[checked]:bg-blue-600"
                                                >
                                                    <svg
                                                        className="size-3 stroke-white opacity-0 group-data-checked:opacity-100"
                                                        viewBox="0 0 14 14"
                                                        fill="none"
                                                    >
                                                        <path
                                                            d="M3 8L6 11L11 3.5"
                                                            strokeWidth={2}
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                    </svg>
                                                </Checkbox>
                                            </td>
                                            <td className="px-3 py-2.5 font-medium text-gray-900 whitespace-nowrap">
                                                {row.invoice_number}
                                            </td>
                                            <td className="px-3 py-2.5 text-gray-600 whitespace-nowrap">
                                                {row.date}
                                            </td>
                                            <td className="px-3 py-2.5 text-right font-medium text-gray-900 whitespace-nowrap">
                                                {row.amount}
                                            </td>
                                            <td className="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span className="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                                    {row.status}
                                                </span>
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </div>
    );
}
