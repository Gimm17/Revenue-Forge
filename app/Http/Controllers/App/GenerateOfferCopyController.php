<?php

namespace App\Http\Controllers\App;

use App\Actions\AI\GenerateOfferAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateOfferCopyRequest;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerateOfferCopyController extends Controller
{
    public function __invoke(
        GenerateOfferCopyRequest $request,
        GenerateOfferAction $action,
    ): JsonResponse {
        $workspace = $request->attributes->get('workspace');
        $offerId = $request->input('offer_id');
        $offer = $offerId ? Offer::find($offerId) : null;

        $result = $action->execute(
            input: $request->validated(),
            workspaceId: $workspace->id,
            userId: $request->user()->id,
            offer: $offer,
        );

        return response()->json($result);
    }
}
