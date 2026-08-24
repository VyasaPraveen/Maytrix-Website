<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;
use App\Models\Payment;

/**
 * Payment gateway integration point for Razorpay (India) and Stripe / PayPal
 * (international), per MOU Clause 4.
 *
 * This class is the single, documented place where the live gateway SDK calls
 * are wired in once the client's accounts + keys are provided. It already:
 *   - resolves keys from the dashboard Settings first, then .env;
 *   - records every payment attempt in the `payments` table;
 *   - returns a normalised "intent" the checkout view/JS can consume.
 *
 * To go live, drop in the official SDK call where marked TODO and verify the
 * webhook/callback signature in verify().
 */
final class PaymentGateway
{
    public function __construct(private string $gateway = 'razorpay') {}

    /** Public/publishable key for the front-end checkout widget. */
    public function publicKey(): string
    {
        $s = new Setting();
        return match ($this->gateway) {
            'razorpay' => $s->get('razorpay_key_id') ?: (string) Config::get('payment.razorpay.key_id', ''),
            'stripe'   => $s->get('stripe_public_key') ?: (string) Config::get('payment.stripe.public', ''),
            'paypal'   => $s->get('paypal_client_id') ?: (string) Config::get('payment.paypal.client_id', ''),
            default    => '',
        };
    }

    private function secretKey(): string
    {
        return match ($this->gateway) {
            'razorpay' => (string) Config::get('payment.razorpay.key_secret', ''),
            'stripe'   => (string) Config::get('payment.stripe.secret', ''),
            'paypal'   => (string) Config::get('payment.paypal.secret', ''),
            default    => '',
        };
    }

    public function isConfigured(): bool
    {
        return $this->publicKey() !== '' && $this->secretKey() !== '';
    }

    /**
     * Create a payment order/intent for an amount.
     *
     * @param string $context 'enrolment' | 'booking'
     * @return array{ok:bool, gateway:string, public_key:string, amount:float, currency:string, reference:?string, payment_id:int, message:?string}
     */
    public function createOrder(float $amount, string $currency, string $context, ?int $contextId): array
    {
        $paymentId = (new Payment())->create([
            'context'    => $context,
            'context_id' => $contextId,
            'gateway'    => $this->gateway,
            'amount'     => $amount,
            'currency'   => $currency,
            'status'     => 'created',
            'created_at' => now(),
        ]);

        if (!$this->isConfigured()) {
            return [
                'ok' => false, 'gateway' => $this->gateway, 'public_key' => '',
                'amount' => $amount, 'currency' => $currency, 'reference' => null,
                'payment_id' => $paymentId,
                'message' => 'Payment gateway keys are not configured yet. Add them in Settings / .env.',
            ];
        }

        // TODO (go-live): call the official SDK to create the order/intent, e.g.
        //   Razorpay:  $order = (new \Razorpay\Api\Api($id,$secret))->order->create([...]);
        //   Stripe:    $intent = \Stripe\PaymentIntent::create([...]);
        // then persist the returned id:
        //   (new Payment())->update($paymentId, ['gateway_ref' => $order['id']]);
        $reference = null;

        return [
            'ok' => true, 'gateway' => $this->gateway, 'public_key' => $this->publicKey(),
            'amount' => $amount, 'currency' => $currency, 'reference' => $reference,
            'payment_id' => $paymentId, 'message' => null,
        ];
    }

    /**
     * Verify a gateway callback/webhook signature and mark the payment paid.
     * Wire the gateway-specific signature check where marked TODO.
     */
    public function verify(int $paymentId, array $payload): bool
    {
        // TODO (go-live): verify signature with the gateway SDK/HMAC before trusting.
        $verified = false;

        (new Payment())->update($paymentId, [
            'status'      => $verified ? 'paid' : 'failed',
            'gateway_ref' => $payload['reference'] ?? null,
            'payload'     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);
        return $verified;
    }
}
