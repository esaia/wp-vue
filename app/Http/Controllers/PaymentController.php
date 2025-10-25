<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\DodoPaymentService;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $wpVueCourse = Course::first();

        if ($user) {
            $hasCourseAccess = $user->containsCourse($wpVueCourse->product_id);

            if ($hasCourseAccess) {
                return $this->error('Course already purchased', 422);
            }
        }


        $productCart = [
            [
                'product_id' => $wpVueCourse->product_id,
                'quantity' => 1
            ]
        ];

        $customer = $user ? ['email' => $user->email] : [];


        $result = $this->dodoPayment->createPayment($productCart, $customer, []);

        if ($result['success']) {
            $paymentData = $result['data'];

            $url = $result['data']['checkout_url'];

            return $this->success(data: compact('url'));
        }

        return $this->error('Payment creation failed', ['error' => $result['error']], $result['status']);
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

                return $this->error('Invalid signature', code: 401);
            }
        } else {
            Log::warning('Missing webhook secret or signature headers');
            if (app()->environment('production')) {
                return $this->error('Signature verification required', code: 401);
            } else {
                Log::warning('⚠️ Skipping signature verification in non-production environment');
            }
        }

        // Process the webhook payload
        return $this->dodoPayment->processWebhookPayload($payload);
    }
}
