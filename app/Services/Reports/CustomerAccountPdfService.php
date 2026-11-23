<?php

namespace App\Services\Reports;

use App\Http\Resources\InvoiceLineResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

/** Responsibility: build the Customer Account Report PDF from invoice rows. */
class CustomerAccountPdfService
{
    /** @param array<int, object> $lines rows from InvoiceRepository (must not be empty) */
    public function make(array $lines, ?string $dateFrom, ?string $dateTo): DomPdf
    {
        $first = $lines[0];
        $rows  = InvoiceLineResource::collection(collect($lines))->resolve();

        return Pdf::loadView('pages.pdf.customer-account', [
            'customerName'   => trim((string) $first->customer_name),
            'customerCode'   => trim((string) $first->cust_num),
            'customerAddress' => trim((string) $first->address),
            'currency'       => trim((string) $first->curr_code),
            'period'         => $this->periodLabel($dateFrom, $dateTo),
            'generatedAt'    => now()->format('M j, Y g:i A'),
            'rows'           => $rows,
            'total'          => array_sum(array_column($rows, 'line_amount')),
        ])->setPaper('a4', 'portrait');
    }

    public function fileName(string $customerName, ?string $dateFrom, ?string $dateTo): string
    {
        $parts = ['customer-account', Str::slug(Str::limit(trim($customerName), 40, ''))];

        if ($dateFrom || $dateTo) {
            $parts[] = ($dateFrom ?: 'start') . '_to_' . ($dateTo ?: 'latest');
        }

        return implode('-', $parts) . '.pdf';
    }

    private function periodLabel(?string $dateFrom, ?string $dateTo): string
    {
        $format = fn (string $d) => Carbon::createFromFormat('Y-m-d', $d)->format('M j, Y');

        return match (true) {
            $dateFrom && $dateTo => $format($dateFrom) . ' – ' . $format($dateTo),
            (bool) $dateFrom     => 'From ' . $format($dateFrom),
            (bool) $dateTo       => 'Up to ' . $format($dateTo),
            default              => 'All dates',
        };
    }
}