<?php

namespace App\Http\Controllers\App;

use App\Actions\Customers\SendPortalMagicLinkAction;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendCustomerPortalLinkController extends Controller
{
    public function __invoke(Request $request, Customer $customer, SendPortalMagicLinkAction $action): RedirectResponse
    {
        // Ensure customer belongs to the active workspace
        if ($customer->workspace_id !== $request->attributes->get('workspace')->id) {
            abort(403);
        }

        $result = $action->execute($customer);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
