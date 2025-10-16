<?php

namespace Devinosh\Ai\Adapters;

use Devinosh\Ai\Contracts\AIClientInterface;
use Illuminate\Support\Facades\Http;

class OpenAiClient implements AIClientInterface
{
    protected string $key;
    protected string $base;

    public function __construct(string $key, string $base = 'https://api.openai.com')
    {
        $this->key = $key;
        $this->base = $base;
    }

    public function generate(string $prompt, array $options = []): array
    {
        $resp = Http::withToken($this->key)
            ->post("{$this->base}/v1/completions", array_merge([
                'model' => $options['model'] ?? 'gpt-4o-mini',
                'prompt' => $prompt,
                'max_tokens' => $options['max_tokens'] ?? 512,
            ], $options));

        $body = $resp->throw()->json();
        // normalize
        return [
            'text' => $body['choices'][0]['text'] ?? '',
            'meta' => $body,
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        $resp = Http::withToken($this->key)
            ->post("{$this->base}/v1/chat/completions", [
                'model' => $options['model'] ?? 'gpt-4o-mini',
                'messages' => $messages,
            ]);
        $body = $resp->throw()->json();
        return [
            'text' => $body['choices'][0]['message']['content'] ?? '',
            'meta' => $body,
        ];
    }

    public function stream(array $messages, callable $onChunk, array $options = []): void
    {
        // پیاده‌سازی ساده: استفاده از SSE / stream response و پاس دادن chunkها به onChunk
        // برای نمونه کامل باید از Guzzle و stream handling استفاده کنی
        throw new \Exception('Streaming not implemented in this example.');
    }
}
