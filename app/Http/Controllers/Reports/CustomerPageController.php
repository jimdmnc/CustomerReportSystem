<?php

namespace App\Http\Controllers\Reports;

use App\Contracts\CustomerDataInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CustomerPageController extends Controller
{
    public function __construct(
        private CustomerDataInterface $customerData
    ) {}

    public function __invoke(): View
    {
        $customers = $this->customerData->searchCustomers('', 15);

        return view('pages.customers', compact('customers'));
    }
}