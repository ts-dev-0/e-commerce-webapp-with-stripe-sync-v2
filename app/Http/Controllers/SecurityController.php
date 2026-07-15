<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('account/security/index', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
