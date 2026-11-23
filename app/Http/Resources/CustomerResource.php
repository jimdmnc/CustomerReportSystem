<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** Responsibility: shape one customer row for the JSON response. */
class CustomerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'customer_number'   => trim((string) $this->cust_num),
            'customer_sequence' => (int) $this->cust_seq,
            'customer_name'     => trim((string) $this->customer_name),
        ];
    }
}