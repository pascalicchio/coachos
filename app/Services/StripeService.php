<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCustomer($organization)
    {
        return Customer::create([
            'name' => $organization->name,
            'email' => $organization->owner->email,
            'metadata' => [
                'organization_id' => $organization->id,
            ],
        ]);
    }

    public function createSubscription($customerId, $priceId)
    {
        return Subscription::create([
            'customer' => $customerId,
            'items' => [['price' => $priceId]],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
        ]);
    }

    public function cancelSubscription($subscriptionId)
    {
        return Subscription::retrieve($subscriptionId)->cancel();
    }

    public function createPaymentIntent($amount, $currency = 'usd', $metadata = [])
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // cents
            'currency' => $currency,
            'metadata' => $metadata,
        ]);
    }

    public function getCheckoutSession($priceId, $successUrl, $cancelUrl)
    {
        return \Stripe\Checkout\Session::create([
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'line_items' => [['price' => $priceId, 'quantity' => 1]],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }
}
