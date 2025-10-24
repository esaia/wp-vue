<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DodoPaymentService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('dodopayments.api_key');
        $this->baseUrl = config('dodopayments.base_url');
    }

    /**
     * Make authenticated HTTP request to DodoPayments API
     */
    protected function makeRequest(string $method, string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->{$method}($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('DodoPayments API Error: ' . $e->getMessage());


            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Create a payment link
     * 
     * @param array $productCart Array of products with product_id and quantity
     * @param array $customer Customer data (email, name, customer_id)
     * @param array $billing Billing address data
     * @return array
     */
    public function createPayment(array $productCart, array $customer = [], array $billing = [])
    {
        $payload = [
            'product_cart' => $productCart,
        ];

        if (!empty($customer)) {
            $payload['customer'] = $customer;
        }

        if (!empty($billing)) {
            $payload['billing'] = $billing;
        }

        $payload['return_url'] = 'http://localhost:8000/';

        return $this->makeRequest('post', '/checkouts', $payload);
    }
}
