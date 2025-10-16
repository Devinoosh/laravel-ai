<?php
namespace Devinosh\Ai\Factory;

use Devinosh\Ai\Adapters\AnthropicClient;
use Devinosh\Ai\Adapters\DeepseekClient;
use Devinosh\Ai\Adapters\OpenAiClient;
use Devinosh\Ai\Contracts\AIClientInterface;

class AIClientFactory
{
    public static function make(string $provider): AIClientInterface
    {
        $cfg = config("ai.providers.{$provider}");
        if (!$cfg) {
            throw new \InvalidArgumentException("Unknown AI provider: {$provider}");
        }

        return match($provider) {
            'openai' => new OpenAiClient($cfg['key'], $cfg['base'] ?? null),
            'anthropic' => new AnthropicClient($cfg['key'], $cfg['base'] ?? null),
            'deepseek' => new DeepseekClient($cfg['key'], $cfg['base'] ?? null),
            default => throw new \InvalidArgumentException("No adapter for provider {$provider}"),
        };
    }
}
