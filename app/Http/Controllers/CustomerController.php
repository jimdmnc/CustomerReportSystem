<?php

namespace App\Http\Controllers;

use App\Contracts\CustomerDataInterface;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerDataInterface $customerData
    ) {}

    public function index()
    {
        // Retrieve customers from the database through the existing service.
        $customerResults = $this->customerData->searchCustomers('', 100);

        // Get the customer records as an array for the Blade dropdown.
        $customers = $customerResults->items();

        // Use the first customer as the initial PDF Preview customer.
        $firstCustomer = $customers[0] ?? null;

        $customer = (object) [
            'name' => $firstCustomer['customer_name'] ?? '',
            'code' => $firstCustomer['customer_number'] ?? '',
            'sequence' => $firstCustomer['customer_sequence'] ?? '',
            'address' => $firstCustomer['address'] ?? '',
            'email' => '',
        ];

        // Temporary preview data until we connect the report API.
        $transactions = [];

        $report = (object) [
            'title' => 'Customers',
            'date_from' => '',
            'date_to' => '',
            'date_range' => '',
            'file_name' => 'CustomerAccountReport.pdf',
            'file_size' => 'Pending',
            'status' => 'Pending',
            'total' => 0,
        ];

        return view('pages.generate-reports', compact(
            'customers',
            'customer',
            'transactions',
            'report'
        ));
    }
}