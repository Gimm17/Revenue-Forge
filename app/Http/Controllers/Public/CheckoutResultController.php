<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutResultController extends Controller
{
    public function success(): Response
    {
        return Inertia::render('Public/CheckoutSuccess');
    }

    public function cancel(): Response
    {
        return Inertia::render('Public/CheckoutCancel');
    }
}
