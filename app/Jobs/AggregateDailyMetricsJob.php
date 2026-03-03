<?php

namespace App\Jobs;

use App\Models\DailyMetric;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AggregateDailyMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private ?string $date = null) {}

    public function handle(): void
    {
        $date = $this->date ?? now()->subDay()->toDateString();

        $workspaces = Workspace::all();

        foreach ($workspaces as $workspace) {
            DailyMetric::updateOrCreate(
                ['workspace_id' => $workspace->id, 'date' => $date],
                [
                    'gross_revenue' => $workspace->orders()
                        ->where('status', 'paid')
                        ->whereDate('paid_at', $date)
                        ->sum('amount'),
                    'paid_orders' => $workspace->orders()
                        ->where('status', 'paid')
                        ->whereDate('paid_at', $date)
                        ->count(),
                    'new_customers' => $workspace->customers()
                        ->whereDate('created_at', $date)
                        ->count(),
                    'active_subscriptions' => DB::table('subscriptions')
                        ->where('workspace_id', $workspace->id)
                        ->where('status', 'active')
                        ->count(),
                    'credits_sold' => DB::table('credit_ledgers')
                        ->join('credit_wallets', 'credit_wallets.id', '=', 'credit_ledgers.wallet_id')
                        ->where('credit_wallets.workspace_id', $workspace->id)
                        ->where('credit_ledgers.type', 'credit')
                        ->whereDate('credit_ledgers.created_at', $date)
                        ->sum('credit_ledgers.amount'),
                    'affiliate_revenue' => DB::table('affiliate_conversions')
                        ->join('affiliate_links', 'affiliate_links.id', '=', 'affiliate_conversions.link_id')
                        ->join('affiliate_programs', 'affiliate_programs.id', '=', 'affiliate_links.program_id')
                        ->where('affiliate_programs.workspace_id', $workspace->id)
                        ->whereDate('affiliate_conversions.created_at', $date)
                        ->sum('affiliate_conversions.commission_amount'),
                ],
            );
        }
    }
}
