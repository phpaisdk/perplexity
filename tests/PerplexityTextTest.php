<?php

declare(strict_types=1);

use AiSdk\Generate;
use AiSdk\Perplexity;
use AiSdk\Perplexity\Tests\Fakes\FakeHttpClient;
use AiSdk\Schema;
use AiSdk\Support\Sdk;

afterEach(function () {
    Generate::reset();
    Perplexity::reset();
});

function configurePerplexityWith(FakeHttpClient $client): void
{
    $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
    Generate::configure(new Sdk(
        httpClient: $client,
        requestFactory: $factory,
        streamFactory: $factory,
    ));
}

it('generates text end to end through the Perplexity vertical', function () {
    $client = new FakeHttpClient(200, json_encode([
        'id' => 'chatcmpl_perplexity',
        'object' => 'chat.completion',
        'created' => 1710000000,
        'model' => 'sonar-pro',
        'system_fingerprint' => 'fp_perplexity',
        'choices' => [['index' => 0, 'message' => ['content' => 'Hello from Perplexity'], 'finish_reason' => 'stop']],
        'usage' => ['prompt_tokens' => 7, 'completion_tokens' => 3],
    ]));
    configurePerplexityWith($client);

    Perplexity::create(['apiKey' => 'perplexity-test']);

    $result = Generate::text('Hi')->model(Perplexity::model('sonar-pro'))->run();

    expect($result->text)->toBe('Hello from Perplexity')
        ->and($result->usage->inputTokens)->toBe(7)
        ->and($result->providerMetadata['perplexity']['id'])->toBe('chatcmpl_perplexity')
        ->and($result->providerMetadata['perplexity']['model'])->toBe('sonar-pro')
        ->and($result->providerMetadata['perplexity']['choice_finish_reason'])->toBe('stop');

    $body = $client->sentBody();
    expect($body['model'])->toBe('sonar-pro')
        ->and($body['messages'][0]['role'])->toBe('user')
        ->and($body['stream'])->toBeFalse();

    expect($client->lastRequest->getHeaderLine('Authorization'))->toBe('Bearer perplexity-test');
});

it('normalizes provider-neutral text usage fields', function () {
    $client = new FakeHttpClient(200, json_encode([
        'choices' => [['index' => 0, 'message' => ['content' => 'Hello from Perplexity'], 'finish_reason' => 'stop']],
        'usage' => ['input_tokens' => 13, 'output_tokens' => 6, 'total_tokens' => 19],
    ]));
    configurePerplexityWith($client);

    Perplexity::create(['apiKey' => 'perplexity-test']);

    $result = Generate::text('Hi')->model(Perplexity::model('sonar-pro'))->run();

    expect($result->usage->inputTokens)->toBe(13)
        ->and($result->usage->outputTokens)->toBe(6)
        ->and($result->usage->totalTokens)->toBe(19);
});

it('maps a 429 to a rate limit exception', function () {
    $client = new FakeHttpClient(429, json_encode(['error' => ['message' => 'slow down']]));
    configurePerplexityWith($client);
    Perplexity::create(['apiKey' => 'perplexity-test']);

    Generate::text('Hi')->model(Perplexity::model('sonar-pro'))->run();
})->throws(\AiSdk\Exceptions\RateLimitException::class);

it('falls back to json_object structured output for models without json_schema support', function () {
    $client = new FakeHttpClient(200, json_encode([
        'choices' => [['message' => ['content' => '{"city":"Lahore","country":"Pakistan"}'], 'finish_reason' => 'stop']],
        'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 7],
    ]));
    configurePerplexityWith($client);
    Perplexity::create(['apiKey' => 'perplexity-test']);

    $result = Generate::text('Extract the city and country from: Lahore, Pakistan.')
        ->model(Perplexity::model('perplexity-small-latest'))
        ->output(Schema::object(
            name: 'address',
            properties: [
                Schema::string(name: 'city')->required(),
                Schema::string(name: 'country')->required(),
            ],
        ))
        ->run();

    $body = $client->sentBody();

    expect($body['response_format'])->toBe(['type' => 'json_object'])
        ->and($body['messages'][0]['role'])->toBe('system')
        ->and($body['messages'][0]['content'])->toContain('valid JSON object')
        ->and($result->output)->toBe(['city' => 'Lahore', 'country' => 'Pakistan']);
});

it('accepts opaque text model ids without a model inventory', function () {
    Perplexity::create(['apiKey' => 'perplexity-test']);

    expect(Perplexity::model('future-private-model')->modelId())->toBe('future-private-model');
});

it('sends requests to the documented Perplexity endpoint', function () {
    $client = new FakeHttpClient(200, json_encode(['choices' => [['index' => 0, 'message' => ['content' => 'ok'], 'finish_reason' => 'stop']], 'usage' => []]));
    configurePerplexityWith($client);
    Perplexity::create(['apiKey' => 'perplexity-test']);

    Generate::text('Hi')->model(Perplexity::model('sonar'))->run();

    expect((string) $client->lastRequest->getUri())->toBe('https://api.perplexity.ai/chat/completions');
});
