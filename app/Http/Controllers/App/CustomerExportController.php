<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $workspace = $request->attributes->get('workspace');

        $customers = Customer::where('workspace_id', $workspace->id)
            ->withCount('orders')
            ->withSum('orders', 'amount')
            ->orderByDesc('created_at')
            ->get();

        return response()->streamDownload(function () use ($customers) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, [
                'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent', 'Joined At',
            ]);

            // Rows
            foreach ($customers as $c) {
                fputcsv($handle, [
                    $c->name,
                    $c->email,
                    $c->phone ?? '',
                    $c->orders_count,
                    $c->orders_sum_amount ?? 0,
                    $c->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'customers-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
