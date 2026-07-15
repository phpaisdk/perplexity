<?php

declare(strict_types=1);

namespace AiSdk\Perplexity;

use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\TextModelInterface;
use AiSdk\Contracts\TextProviderInterface;
use AiSdk\Perplexity\Models\PerplexityTextModel;

final class PerplexityProvider extends BaseProvider implements TextProviderInterface
{
    public function __construct(public readonly PerplexityOptions $options) {}

    public function name(): string
    {
        return PerplexityOptions::PROVIDER_NAME;
    }

    protected function textModel(string $modelId): TextModelInterface
    {
        return new PerplexityTextModel($modelId, $this->options);
    }

}
