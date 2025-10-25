<?php

namespace App\Services;

use App\Models\CourseAccess;
use App\Models\User;
use App\Traits\APIResponsible;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DodoPaymentService
{
    use APIResponsible;

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


    /**
     * Process the verified webhook payload
     */
    public function processWebhookPayload(string $payload)
    {
        try {
            $event = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

            Log::info('🎯 Dodo Webhook Event Received', [
                'event_type' => $event['type'] ?? 'unknown',
                'business_id' => $event['business_id'] ?? 'unknown',
                'payload_type' => $event['data']['payload_type'] ?? 'unknown',
                'status' => $event['data']['status'] ?? 'unknown',
                'payment_id' => $event['data']['payment_id'] ?? 'unknown'
            ]);

            // Process events based on the 'type' field
            $this->handleWebhookEvent($event);

            return response()->json(['status' => 'success', 'message' => 'Webhook processed'], 200);

            return $this->success('success', 'Webhook processed');
        } catch (\JsonException $e) {
            Log::error('❌ Webhook JSON parsing error', [
                'error' => $e->getMessage(),
                'payload_sample' => substr($payload, 0, 200) . '...'
            ]);


            return $this->error('Invalid JSON payload', code: 400);
        } catch (\Exception $e) {
            Log::error('❌ Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->error('Processing failed', code: 400);
        }
    }

    protected function handleWebhookEvent(array $event)
    {
        $eventType = $event['type'] ?? 'unknown';

        Log::info("🔄 Processing webhook event: {$eventType}");

        switch ($eventType) {
            case 'payment.succeeded':
                $this->handlePaymentSucceeded($event['data'] ?? []);
                break;

            case 'payment.failed':
                $this->handlePaymentFailed($event['data'] ?? []);
                break;

            case 'payment.refunded':
                $this->handlePaymentRefunded($event['data'] ?? []);
                break;

            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event['data'] ?? []);
                break;

            default:
                Log::info('🤔 Unhandled webhook event type', [
                    'event' => $eventType,
                    'business_id' => $event['business_id'] ?? null
                ]);
        }
    }

    protected function handlePaymentSucceeded(array $paymentData)
    {
        Log::info('💰 Payment succeeded webhook', [
            'payment_id' => $data['payment_id'] ?? null,
            'checkout_session_id' => $data['checkout_session_id'] ?? null,
            'customer_id' => $data['customer']['customer_id'] ?? null,
            'customer_email' => $data['customer']['email'] ?? null,
            'customer_name' => $data['customer']['name'] ?? null,
            'amount' => $data['total_amount'] ?? null,
            'currency' => $data['currency'] ?? null,
            'status' => $data['status'] ?? null,
            'product_cart' => $data['product_cart'] ?? []
        ]);

        // TODO: Implement your business logic here
        $customerEmail = $paymentData['customer']['email'] ?? null;
        $paymentId = $paymentData['payment_id'] ?? null;
        $checkoutSessionId = $paymentData['checkout_session_id'] ?? null;
        $productCart = $paymentData['product_cart'] ?? [];

        if (!$customerEmail) {
            Log::warning('📧 Cannot grant access: missing customer email');
            return;
        }

        Log::info('🎓 Granting course access', [
            'customer_email' => $customerEmail,
            'payment_id' => $paymentId,
            'products' => $productCart
        ]);

        try {
            // Check if user exists with this email
            $user = User::where('email', $customerEmail)->first();
            $userId = $user?->id;

            // Grant access for each product in the cart
            foreach ($productCart as $product) {
                CourseAccess::create([
                    'user_email' => $customerEmail,
                    'product_id' => $product['product_id'],
                    'payment_id' => $paymentId,
                    'checkout_session_id' => $checkoutSessionId,
                    'status' => $userId ? 'active' : 'pending',
                    'access_granted_at' => now(),
                    'access_expires_at' => null,

                    'metadata' => [
                        'customer_data' => $paymentData['customer'],
                        'billing_data' => $paymentData['billing'] ?? null,
                        'payment_amount' => $paymentData['total_amount'] ?? null,
                        'payment_currency' => $paymentData['currency'] ?? null,
                        'purchased_at' => $paymentData['created_at'] ?? now()->toISOString(),
                    ],
                ]);

                Log::info('✅ Course access record created', [
                    'user_email' => $customerEmail,
                    'user_id' => $userId,
                    'product_id' => $product['product_id'],
                    'status' => $userId ? 'active' : 'pending_user'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Failed to grant course access', [
                'error' => $e->getMessage(),
                'customer_email' => $customerEmail
            ]);
        }
    }



    protected function handlePaymentFailed(array $data)
    {
        Log::error('💥 Payment failed webhook', [
            'payment_id' => $data['payment_id'] ?? null,
            'customer_email' => $data['customer']['email'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_message' => $data['error_message'] ?? null
        ]);

        // TODO: Handle failed payment logic
        // - Notify customer
        // - Update payment status in database
    }

    protected function handlePaymentRefunded(array $data)
    {
        Log::info('↩️ Payment refunded webhook', [
            'payment_id' => $data['payment_id'] ?? null,
            'customer_email' => $data['customer']['email'] ?? null,
            'refunds' => $data['refunds'] ?? []
        ]);

        // TODO: Handle refund logic
        // - Revoke course access
        // - Update user subscription
    }

    protected function handleCheckoutCompleted(array $data)
    {
        Log::info('🛒 Checkout session completed webhook', [
            'checkout_session_id' => $data['checkout_session_id'] ?? null,
            'payment_status' => $data['status'] ?? null,
            'customer_email' => $data['customer']['email'] ?? null
        ]);
    }
}
