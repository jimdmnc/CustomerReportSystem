<?php

namespace App\Services;

use App\Contracts\CustomerDataInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CustomerDataService implements CustomerDataInterface
{
    /**
     * Search customers by customer number or name.
     */
    public function searchCustomers(
        string $keyword,
        int $perPage = 15
    ): LengthAwarePaginator {

        $query = DB::table('dbo.custaddr')
            ->select([
                'cust_num',
                'cust_seq',
                'name',
                'addr##1',
                'addr##2',
                'addr##3',
                'addr##4',
                'zip',
                'country',
                'curr_code',
                'credit_limit',
            ]);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('cust_num', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%");
            });
        }

        return $query
            ->orderBy('name')
            ->paginate($perPage)
            ->through(fn ($customer) => $this->formatCustomer($customer));
    }

    /**
     * Retrieve a specific customer.
     */
    public function findCustomer(
        string $customerNumber,
        ?string $customerSequence = null
    ): ?array {

        $query = DB::table('dbo.custaddr')
            ->where('cust_num', $customerNumber);

        if ($customerSequence !== null) {
            $query->where('cust_seq', $customerSequence);
        }

        $customer = $query
            ->orderBy('cust_seq')
            ->first();

        return $customer
            ? $this->formatCustomer($customer)
            : null;
    }

    /**
     * Format customer information.
     */
    private function formatCustomer(object $customer): array
    {
        $addressParts = [
            $customer->{'addr##1'} ?? '',
            $customer->{'addr##2'} ?? '',
            $customer->{'addr##3'} ?? '',
            $customer->{'addr##4'} ?? '',
            $customer->zip ?? '',
            $customer->country ?? '',
        ];

        $address = implode(', ', array_filter(
            array_map('trim', $addressParts),
            fn ($value) => $value !== ''
        ));

        return [
            'customer_number' => $customer->cust_num,
            'customer_sequence' => $customer->cust_seq,
            'customer_name' => $customer->name,
            'address' => $address,
            'currency' => $customer->curr_code,
            'credit_limit' => $customer->credit_limit,
        ];
    }
}