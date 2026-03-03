<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WorkspacePlan;
use Inertia\Inertia;
use Inertia\Response;

class PricingController extends Controller
{
    public function __invoke(): Response
    {
        $plans = WorkspacePlan::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($plan) => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'price' => $plan->price,
                'currency' => $plan->currency,
                'interval' => $plan->interval,
                'features' => $plan->features,
                'formatted_price' => $plan->formattedPrice(),
            ]);

        return Inertia::render('Public/Pricing', [
            'plans' => $plans,
        ]);
    }
}
