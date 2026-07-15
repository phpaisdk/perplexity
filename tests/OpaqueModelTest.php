<?php

declare(strict_types=1);

use AiSdk\Generate;
use AiSdk\Perplexity;

afterEach(function () {
    Generate::reset();
    Perplexity::reset();
});

it('uses adapter capabilities for opaque Perplexity model ids', function () {
    Perplexity::create(['apiKey' => 'perplexity-test']);
    $model = Perplexity::model('vendor/private-model');

    expect($model->modelId())->toBe('vendor/private-model');
});
