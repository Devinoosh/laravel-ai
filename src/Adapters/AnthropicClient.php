<?php

namespace Devinosh\Ai\Adapters;
use Devinosh\Ai\Contracts\AIClientInterface;
use Illuminate\Support\Facades\Http;

class AnthropicClient implements AIClientInterface
{
    protected string $key;
    protected string $base;

    public function __construct(string $key, string $base = 'https://api.anthropic.com')
    {
        $this->key = $key;
        $this->base = $base;
    }

    public function generate(string $prompt, array $options = []): array
    {
        $resp = Http::withHeaders([
            'Authorization' => "Bearer {$this->key}",
            'Content-Type' => 'application/json',
        ])->post("{$this->base}/v1/complete", array_merge([
            'model' => $options['model'] ?? 'claude-v1',
            'prompt' => $prompt,
            'max_tokens_to_sample' => $options['max_tokens'] ?? 512,
        ], $options));

        $body = $resp->throw()->json();

        return [
            'text' => $body['completion'] ?? '',
            'meta' => $body,
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        // Anthropic از format مشابه text generation استفاده می‌کند
        $prompt = '';
        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';
            $content = $msg['content'] ?? '';
            $prompt .= "\n{$role}: {$content}";
        }
        $prompt .= "\nassistant:";

        return $this->generate($prompt, $options);
    }

    public function stream(array $messages, callable $onChunk, array $options = []): void
    {
        // پیاده‌سازی اولیه: اگر بخواهی می‌توانیم SSE یا curl streaming اضافه کنیم
        throw new \Exception('Anthropic streaming not implemented yet.');
    }
}
