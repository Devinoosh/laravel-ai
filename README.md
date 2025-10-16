# Laravel AI Client

A unified AI client for Laravel to interact with multiple AI providers such as **OpenAI**, **Anthropic**, and **Deepseek**.  
This package uses the **Strategy/Adapter pattern**, allowing you to easily switch providers or add new ones without changing your application code.

---

## Install

Install via Composer:

```bash
composer require devinosh/laravel-ai
```

## Add provider
>Add **\Devinosh\Ai\AiServiceProvider::class** to your **config/app.php** providers array.
If you are using Laravel 11 or later, Laravel will automatically discover the package and register the provider, so manual addition is not required.

## Publish configs

Publish the package configuration:

```php
php artisan vendor:publish --provider="Devinosh\Ai\AiServiceProvider" --tag=ai
```

This will create config/ai.php, where you can configure your API keys and default provider.

Environment Variables

Add your API keys and default provider to .env:

```dotenv
OPENAI_API_KEY=your_openai_key
ANTHROPIC_KEY=your_anthropic_key
DEEPSEEK_KEY=your_deepseek_key

AI_DEFAULT=openai
```

## Configuration

config/ai.php example:

```php
return [
    'default' => env('AI_DEFAULT', 'openai'),
    'providers' => [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'base' => env('OPENAI_BASE', 'https://api.openai.com'),
        ],
        'anthropic' => [
            'key' => env('ANTHROPIC_KEY'),
            'base' => env('ANTHROPIC_BASE', 'https://api.anthropic.com'),
        ],
        'deepseek' => [
            'key' => env('DEEPSEEK_KEY'),
            'base' => env('DEEPSEEK_BASE', 'https://api.deepseek.ai'),
        ],
    ],
];
```


## Usage
Using Dependency Injection

```php
use App\AI\Contracts\AIClientInterface;

class AiController extends Controller
{
    public function ask(AIClientInterface $ai)
    {
        $response = $ai->chat([
            ['role' => 'user', 'content' => 'Hello, AI!']
        ]);

        return response()->json($response);
    }
}

Using Factory Directly
use App\AI\Factory\AIClientFactory;

$ai = AIClientFactory::make('deepseek'); // openai, anthropic, deepseek

$response = $ai->generate('Write a short poem about autumn.');
echo $response['text'];
```


## Adding New Providers

Create a new Adapter implementing AIClientInterface.

Implement generate(), chat(), and optionally stream().

Add the new provider config to config/ai.php.

Update AIClientFactory to return the new Adapter when requested.

## Provider Capabilities

| Provider  | Text | Chat | Streaming  |
| --------- |:-----|:-----|:-----------|
| OpenAI    | ✅   |✅|Partial|
| Anthropic | ✅   |✅|No|
| Deepseek  | ✅   |✅|No|

## Response Format

All adapters normalize responses to the following format:

````json
{
  "text": "Generated text from AI",
  "meta": {
    "model": "model_name",
    "usage": { "prompt_tokens": 10, "completion_tokens": 15 },
    "raw": {}
  }
}

````
>text: The main AI output.
> 
>meta: Additional info like model, usage, and raw provider response.
> 

Notes

Streaming support is provider-specific.

Caching and rate-limiting should be implemented at the application level if needed.

Always keep API keys secure in .env.

Adapter pattern allows easy switching of providers without changing application logic.