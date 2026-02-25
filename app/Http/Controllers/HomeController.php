<?php

namespace App\Http\Controllers;

use App\Actions\User\Home\HomeIndex;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(HomeIndex $action)
    {
        $data = $action->handle();

        return Inertia::render('home', [
            'data' => $data,
        ]);
    }
}
