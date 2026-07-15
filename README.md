# aisdk/perplexity

<a href="https://github.com/phpaisdk/perplexity/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/perplexity/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/perplexity"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/aisdk/perplexity"></a>
<a href="https://packagist.org/packages/aisdk/perplexity"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/perplexity"></a>
<a href="https://packagist.org/packages/aisdk/perplexity"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/perplexity"></a>
<a href="https://whyphp.dev"><img src="https://img.shields.io/badge/Why_PHP-in_2026-7A86E8?style=flat-square&labelColor=18181b" alt="Why PHP in 2026"></a>

------

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
