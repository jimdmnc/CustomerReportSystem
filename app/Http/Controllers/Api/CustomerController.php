<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CustomerDataInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerDataInterface $customerData
    ) {}

    /**
     * Search customers.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $customers = $this->customerData->searchCustomers(
            $validated['search'] ?? '',
            $validated['per_page'] ?? 15
        );

        return response()->json($customers);
    }

    /**
     * Display a specific customer.
     */
    public function show(
        string $customerNumber,
        ?string $customerSequence = null
    ): JsonResponse {
        $customer = $this->customerData->findCustomer(
            $customerNumber,
            $customerSequence
        );

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'data' => $customer,
        ]);
    }
}