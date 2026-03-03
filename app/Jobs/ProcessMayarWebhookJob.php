<?php

namespace App\Jobs;

use App\Actions\Webhook\ProcessMayarWebhookAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMayarWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 300, 1800, 3600]; // 1m, 5m, 30m, 1h -> Exponential backoff

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $payload,
        public string $eventType
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ProcessMayarWebhookAction $action): void
    {
        $action->execute($this->payload, $this->eventType);
    }
}
