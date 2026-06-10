<?php

namespace App\Services;

use Stripe\Checkout\Session;

class StripSessionService
{
    public function retrieveStripeSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }
}
