<?php

namespace Devinosh\Ai\Adapters;

use Devinosh\Ai\Contracts\AIClientInterface;
use Illuminate\Support\Facades\Http;

class DeepseekClient implements AIClientInterface
{
    protected string $key;
    protected string $base;

    public function __construct(string $key, string $base = 'https://api.deepseek.ai')
    {
        $this->key = $key;
        $this->base = $base;
    }

    public function generate(string $prompt, array $options = []): array
    {
        $resp = Http::withToken($this->key)
            ->post("{$this->base}/v1/generate-text", array_merge([
                'model' => $options['model'] ?? 'default',
                'input' => $prompt,
                'max_tokens' => $options['max_tokens'] ?? 512,
            ], $options));

        $body = $resp->throw()->json();

        return [
            'text' => $body['output'] ?? '',
            'meta' => $body,
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        $resp = Http::withToken($this->key)
            ->post("{$this->base}/v1/chat", [
                'model' => $options['model'] ?? 'default',
                'messages' => $messages,
            ]);

        $body = $resp->throw()->json();

        return [
            'text' => $body['response'] ?? '',
            'meta' => $body,
        ];
    }

    public function stream(array $messages, callable $onChunk, array $options = []): void
    {
        // پیاده‌سازی stream بسته به مستندات Deepseek ممکن است متفاوت باشد
        throw new \Exception('Deepseek streaming not implemented yet.');
    }
}
