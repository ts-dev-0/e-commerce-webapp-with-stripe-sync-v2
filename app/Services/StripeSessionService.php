<?php

namespace App\Services;

use Stripe\Checkout\Session;

class StripeSessionService
{
    public function retrieveStripeSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }
}
