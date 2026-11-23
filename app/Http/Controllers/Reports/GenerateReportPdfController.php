<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoicePreviewRequest;
use App\Repositories\InvoiceRepository;
use App\Services\Reports\CustomerAccountPdfService;

/** Responsibility: turn the same filters used by the preview into a PDF response. */
class GenerateReportPdfController extends Controller
{
    public function __invoke(
        InvoicePreviewRequest $request,
        InvoiceRepository $invoices,
        CustomerAccountPdfService $pdf
    ) {
        $data = $request->validated();

        $lines = $invoices->forCustomerName(
            $data['customer_name'],
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
        );

        abort_if(empty($lines), 404, 'No invoice records found for the selected customer and dates.');

        return $pdf
            ->make($lines, $data['date_from'] ?? null, $data['date_to'] ?? null)
            ->stream($pdf->fileName($lines[0]->customer_name, $data['date_from'] ?? null, $data['date_to'] ?? null));
    }
}