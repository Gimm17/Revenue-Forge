<?php

namespace App\Services\Mayar;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MayarApiClient
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('mayar.base_url'), '/');
        $this->apiKey = config('mayar.api_key');
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    private function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->{strtolower($method)}(
                $this->baseUrl . '/' . ltrim($endpoint, '/'),
                $options['json'] ?? $options['query'] ?? []
            );

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            Log::error('Mayar API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $response->json('message', 'API request failed'), 'status' => $response->status()];
        } catch (\Exception $e) {
            Log::error('Mayar API Exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('mayar.webhook_secret');
        if (! $secret) return false;
        $computed = hash_hmac('sha256', $payload, $secret);
        return hash_equals($computed, $signature);
    }
}
