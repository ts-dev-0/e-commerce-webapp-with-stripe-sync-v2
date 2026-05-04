<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        match ($event->type) {
            'product.created' => $this->handleProductCreated($event),
            'product.updated' => $this->handleProductUpdated($event),
            'product.deleted' => $this->handleProductDeleted($event),

            'price.created' => $this->handlePriceCreated($event),
            'price.updated' => $this->handlePriceUpdated($event),

            default => null,
        };
    }

    private function handleProductCreated(Event $event)
    {
        /** @var \Stripe\Product $stripeProduct */
        $stripeProduct = $event->data->object;

        Product::create([
            'stripe_product_id' => $stripeProduct->id,
            'name' => $stripeProduct->name,
        ]);
    }

    private function handlePriceCreated(Event $event): void
    {
        /** @var \Stripe\Price $stripePrice */
        $stripePrice = $event->data->object;

        Product::query()
            ->where('stripe_product_id', $stripePrice->product)
            ->update([
                'stripe_price_id' => $stripePrice->id,
                'price' => $stripePrice->unit_amount,
            ]);
    }

    private function handleProductUpdated(Event $event): void
    {
        /** @var \Stripe\Product $stripeProduct */
        $stripeProduct = $event->data->object;

        Product::query()
            ->where('stripe_product_id', $stripeProduct->id)
            ->update([
                'stripe_price_id' => $stripeProduct->id,
                'name' => $stripeProduct->name,
            ]);
    }

    private function handlePriceUpdated(Event $event): void
    {
        /** @var \Stripe\Price $stripePrice */
        $stripePrice = $event->data->object;

        Product::query()
            ->where('stripe_product_id', $stripePrice->product)
            ->update([
                'stripe_price_id' => $stripePrice->id,
                'price' => $stripePrice->unit_amount,
            ]);
    }
}
