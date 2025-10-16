<?php
namespace Devinosh\Ai\Contracts;

interface AIClientInterface
{
    /**
     * Generate text (simple sync)
     *
     * @param string $prompt
     * @param array $options
     * @return array ['text' => string, 'meta' => [...]]
     */
    public function generate(string $prompt, array $options = []): array;

    /**
     * Chat style (messages array)
     */
    public function chat(array $messages, array $options = []): array;

    /**
     * Optional streaming support
     */
    public function stream(array $messages, callable $onChunk, array $options = []): void;
}
