<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** Responsibility: shape one invoice line row for the JSON response. */
class InvoiceLineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'customer_name' => trim((string) $this->customer_name),
            'address'      => trim((string) $this->address),
            'inv_num'      => trim((string) $this->inv_num),
            'inv_seq'      => (int) $this->inv_seq,
            'inv_line'     => (int) $this->inv_line,
            'inv_date'     => $this->inv_date ? Carbon::parse($this->inv_date)->toDateString() : null,
            'ship_date'    => $this->ship_date ? Carbon::parse($this->ship_date)->toDateString() : null,
            'co_num'       => $this->co_num ? trim($this->co_num) : null,
            'cust_po'      => $this->cust_po ? trim($this->cust_po) : null,
            'item'         => $this->item ? trim($this->item) : null,
            'do_num'       => $this->do_num ? trim($this->do_num) : null,
            'currency'     => trim((string) $this->curr_code),
            'qty_invoiced' => (float) $this->qty_invoiced,
            'unit_price'   => (float) $this->unit_price,
            'unit_cost'    => (float) $this->unit_cost,
            'line_amount'  => (float) $this->line_amount,
        ];
    }
}