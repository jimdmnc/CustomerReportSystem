<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CustomerDataInterface
{
    /**
     * Search customers by customer number or name.
     */
    public function searchCustomers(
        string $keyword,
        int $perPage = 15
    ): LengthAwarePaginator;

    /**
     * Retrieve a specific customer using customer number and sequence.
     */
    public function findCustomer(
        string $customerNumber,
        ?string $customerSequence = null
    ): ?array;
}