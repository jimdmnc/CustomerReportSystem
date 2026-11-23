<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSearchRequest;
use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepository;

/** Responsibility: answer the customer-name search box. */
class CustomerSearchController extends Controller
{
    public function __invoke(CustomerSearchRequest $request, CustomerRepository $customers)
    {
        return CustomerResource::collection(
            collect($customers->search($request->validated('q')))
        );
    }
}