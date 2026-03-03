<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WorkspacePlan;
use Inertia\Inertia;
use Inertia\Response;

class PublicLandingController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Public/Landing');
    }
}
