<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Data\Enums\OfferType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferCreateController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Offers/Create', [
            'offerTypes' => collect(OfferType::cases())->map(fn ($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'color' => $type->color(),
            ]),
        ]);
    }
}
