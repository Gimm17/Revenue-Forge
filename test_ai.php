<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Clear config cache to pick up new .env values
echo "Providers configured:\n";
$providers = config('revenueforge.ai.providers', []);
foreach ($providers as $p) {
    $keyStatus = empty($p['api_key']) ? '❌ no key' : '✅ key set';
    echo "  - {$p['name']} [{$p['model']}] {$keyStatus}\n";
}
echo "\nTesting generation...\n\n";

$service = app(\App\Services\AI\OfferGeneratorService::class);
$result = $service->generate([
    'business_type'   => 'SAAS',
    'target_audience' => 'Freelancers',
    'offer_type'      => 'one_time',
    'goal'            => 'Increase SaaS subscribers',
    'price_range'     => 'IDR 99K - 299K',
    'tone'            => 'casual',
    'cta_style'       => 'direct',
]);

if ($result['success']) {
    echo "✅ SUCCESS! Provider used: {$result['model_used']}\n";
    echo "Title: " . ($result['data']['title'] ?? '-') . "\n";
    echo "Tagline: " . ($result['data']['tagline'] ?? '-') . "\n";
    echo "Benefits:\n";
    foreach (($result['data']['benefits'] ?? []) as $b) {
        echo "  - $b\n";
    }
} else {
    echo "❌ FAILED: " . $result['error'] . "\n";
    if ($result['all_failed'] ?? false) {
        echo "⚠️  SEMUA PROVIDER HABIS LIMIT\n";
    }
}

$result = $service->generate([
    'business_type' => 'SAAS',
    'target_audience' => 'Designers',
    'offer_type' => 'one_time',
    'goal' => 'sell more',
    'price_range' => '50k',
    'tone' => 'casual',
    'cta_style' => 'direct'
]);

print_r($result);
