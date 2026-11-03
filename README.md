# PHP AI SDK — Perplexity

Search-grounded text generation and streaming with Perplexity Sonar. Part of the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/perplexity
```

Set `PERPLEXITY_API_KEY` in your environment.

## Usage

```php
use AiSdk\Generate;
use AiSdk\Perplexity;

$result = Generate::text('Explain closures in PHP in one sentence.')
    ->model(Perplexity::model('sonar-pro'))
    ->run();

echo $result->text;
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/perplexity) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.
