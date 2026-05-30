<?php

namespace App\Services;

use Stripe\Checkout\Session;

class StripSessioneService
{
    public function retrieveStripeSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }
}
