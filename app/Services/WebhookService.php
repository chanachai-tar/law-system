<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Dispatch webhook payload to all subscribed and active API Key clients
     */
    public static function dispatch(string $event, array $payload): void
    {
        try {
            $apiKeys = ApiKey::where('is_active', true)
                ->whereNotNull('webhook_url')
                ->get();

            foreach ($apiKeys as $client) {
                // Check if subscribed to event
                $events = $client->webhook_events ?? [];
                if (!empty($events) && !in_array('*', $events) && !in_array($event, $events)) {
                    continue;
                }

                $webhookData = [
                    'event'      => $event,
                    'timestamp'  => now()->toISOString(),
                    'source'     => 'ODPC10_LAW_SYSTEM',
                    'data'       => $payload,
                ];

                // Send async HTTP POST with 2-second timeout so it never slows down the main application
                try {
                    Http::timeout(2)
                        ->withHeaders([
                            'X-LSS-Event'     => $event,
                            'X-LSS-Signature' => hash_hmac('sha256', json_encode($webhookData), $client->key),
                            'Content-Type'    => 'application/json',
                            'User-Agent'      => 'ODPC10-LSS-Webhook/1.0',
                        ])
                        ->post($client->webhook_url, $webhookData);
                } catch (\Throwable $th) {
                    Log::warning("[Webhook] Failed to deliver {$event} to {$client->webhook_url}: " . $th->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error("[Webhook] Global dispatch error: " . $e->getMessage());
        }
    }
}
