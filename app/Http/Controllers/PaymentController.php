<?php

namespace App\Http\Controllers;

use App\Models\CourseAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Services\DodoPaymentService;
use Dodopayments\Client;
use Dodopayments\CheckoutSessions\CheckoutSessionCreateParams\ProductCart;
use Illuminate\Support\Facades\Log;
use Svix\Exception\WebhookVerificationException;
use Svix\Webhook;

class PaymentController extends Controller
{
    protected DodoPaymentService $dodoPayment;

    public function __construct(DodoPaymentService $dodoPayment)
    {
        $this->dodoPayment = $dodoPayment;
    }



    public function processPayment(Request $request)
    {

        $productCart = [
            [
                'product_id' => 'pdt_r6D1CcHzq9EnFJBhhnN9A',
                'quantity' => 1
            ]
        ];

        $result = $this->dodoPayment->createPayment($productCart, [], []);

        if ($result['success']) {
            $paymentData = $result['data'];

            $url = $result['data']['checkout_url'];

            return compact('url');
        }

        return response()->json([
            'success' => false,
            'message' => $result['error']['message'] ?? 'Payment creation failed',
            'error' => $result['error'],
        ], $result['status']);
    }

    public function webhook(Request $request)
    {
        Log::info('DodoPayments Webhook: Received via Svix', [
            'webhook_id' => $request->header('webhook-id'),
            'timestamp' => $request->header('webhook-timestamp'),
            'ip' => $request->ip()
        ]);

        // Get raw payload for signature verification
        $payload = $request->getContent();
        $secret = config('dodopayments.webhook_secret');

        // Get all headers for Svix verification
        $headers = [
            'webhook-id' => $request->header('webhook-id'),
            'webhook-timestamp' => $request->header('webhook-timestamp'),
            'webhook-signature' => $request->header('webhook-signature'),
        ];

        Log::info('Webhook verification details', [
            'has_secret' => !empty($secret),
            'secret_prefix' => !empty($secret) ? substr($secret, 0, 10) . '...' : 'empty',
            'has_all_headers' => !empty($headers['webhook-id']) && !empty($headers['webhook-timestamp']) && !empty($headers['webhook-signature']),
            'payload_length' => strlen($payload)
        ]);

        // Verify Svix signature
        if (!empty($secret) && !empty($headers['webhook-signature'])) {
            try {
                $wh = new Webhook($secret);
                $wh->verify($payload, $headers);

                Log::info('✅ Svix webhook signature verified successfully');
            } catch (WebhookVerificationException $e) {
                Log::warning('❌ Invalid Svix webhook signature', [
                    'error' => $e->getMessage(),
                    'headers' => $headers
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        } else {
            Log::warning('Missing webhook secret or signature headers');
            if (app()->environment('production')) {
                return response()->json(['error' => 'Signature verification required'], 401);
            } else {
                Log::warning('⚠️ Skipping signature verification in non-production environment');
            }
        }

        // Process the webhook payload
        return $this->processWebhookPayload($payload);
    }

    /**
     * Process the verified webhook payload
     */
    protected function processWebhookPayload(string $payload)
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
        } catch (\JsonException $e) {
            Log::error('❌ Webhook JSON parsing error', [
                'error' => $e->getMessage(),
                'payload_sample' => substr($payload, 0, 200) . '...'
            ]);
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        } catch (\Exception $e) {
            Log::error('❌ Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Processing failed'], 400);
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

    protected function handlePaymentSucceeded(array $data)
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
        $this->grantCourseAccess($data);
    }

    /**
     * Grant course access to the customer after successful payment
     */
    protected function grantCourseAccess(array $paymentData)
    {
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



        // TODO: Implement your actual business logic:

        // 1. Find or create user by email
        // $user = User::firstOrCreate(
        //     ['email' => $customerEmail],
        // );

        // 2. Grant course access
        // foreach ($productCart as $product) {
        //     CourseAccess::create([
        //         'user_id' => $user->id,
        //         'product_id' => $product['product_id'],
        //         'payment_id' => $paymentId,
        //         'access_granted_at' => now(),
        //     ]);
        // }

        // 3. Send welcome email
        // Mail::to($user->email)->send(new CourseAccessGranted($user, $productCart));



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

            // // Send appropriate email
            // if ($user) {
            //     // User exists - send course access email
            //     $this->sendCourseAccessGrantedEmail($user, $paymentData);
            // } else {
            //     // User doesn't exist - send welcome + course access email
            //     $this->sendWelcomeWithCourseAccess($customerEmail, $paymentData);
            // }

            // Log::info('🎉 Course access process completed', [
            //     'user_email' => $customerEmail,
            //     'user_exists' => !is_null($user),
            //     'products_granted' => count($productCart)
            // ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to grant course access', [
                'error' => $e->getMessage(),
                'customer_email' => $customerEmail
            ]);
        }


        // // 4. Log the access grant
        // Log::info('✅ Course access should be granted', [
        //     'email' => $customerEmail,
        //     'payment_id' => $paymentId,
        //     'products_count' => count($productCart)
        // ]);
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
