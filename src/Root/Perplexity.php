<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\Contracts\Model;
use AiSdk\Perplexity\PerplexityOptions;
use AiSdk\Perplexity\PerplexityProvider;

/**
 * Friendly facade for the Perplexity provider.
 *
 *   $model = Perplexity::model('sonar-pro');
 */
final class Perplexity
{
    private static ?PerplexityProvider $default = null;

    /**
     * @param  array<string, mixed>  $config
     */
    public static function create(array $config = []): PerplexityProvider
    {
        return self::$default = new PerplexityProvider(PerplexityOptions::fromArray($config));
    }

    public static function default(): PerplexityProvider
    {
        return self::$default ??= self::create();
    }

    public static function reset(): void
    {
        self::$default = null;
    }

    public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}
