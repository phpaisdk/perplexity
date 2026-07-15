# aisdk/perplexity

Official Perplexity provider for the PHP AI SDK. Uses Perplexity's Chat Completions API.

## Installation

```bash
composer require aisdk/perplexity
```

## Basic Usage

```php
use AiSdk\Generate;
use AiSdk\Perplexity;

$result = Generate::text('What happened in technology today?')
    ->model(Perplexity::model('sonar-pro'))
    ->run();

echo $result->text;
```

## Configuration

| Variable | Description | Default |
|---|---|---|
| `PERPLEXITY_API_KEY` | API key for authentication | Required |
| `PERPLEXITY_BASE_URL` | Base URL for API requests | `https://api.perplexity.ai` |

## Supported Capabilities

| Capability | Support |
|---|---|
| Text generation | Native |
| Streaming | Native |
| Tool calling | Native |
| Structured output | Adapted (`json_object` with an explicit JSON instruction) |
| Text input | Native |

Provider-specific fields can be passed through `providerOptions('perplexity', [...])`. Model IDs are opaque strings and Perplexity remains the authority on model availability.

## Testing

```bash
composer test:all
```

## Links

- [Perplexity API documentation](https://docs.perplexity.ai/)
- [Core Package](https://github.com/phpaisdk/core)
- [OpenAI-Compatible Package](https://github.com/phpaisdk/openai-compatible)
