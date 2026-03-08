<?php

namespace AiSemanticEngine;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private GuzzleClient $httpClient;
    private string $apiKey;

    public function __construct(string $apiKey, string $baseUrl = 'https://ai.quizcore.org')
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new GuzzleClient([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'headers' => [
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * @param string|array $textOrTexts
     * @throws GuzzleException
     */
    public function embed($textOrTexts, string $model = 'fast', bool $store = false): array
    {
        if (is_string($textOrTexts)) {
            $endpoint = 'embed';
            $payload = ['text' => $textOrTexts];
        } else {
            $endpoint = 'embed-batch';
            $payload = ['texts' => $textOrTexts];
        }
        $payload['model'] = $model;
        $payload['store'] = $store;

        $response = $this->httpClient->post($endpoint, ['json' => $payload]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function similarity(string $text1, string $text2, string $model = 'fast'): array
    {
        $response = $this->httpClient->post('similarity', [
            'json' => [
                'text1' => $text1,
                'text2' => $text2,
                'model' => $model
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function storeItem(string $text, array $metadata = [], string $model = 'fast'): array
    {
        $response = $this->httpClient->post('items', [
            'json' => [
                'text' => $text,
                'metadata' => empty($metadata) ? new \stdClass() : $metadata,
                'model' => $model
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function search(string $query, int $topK = 5, string $model = 'fast'): array
    {
        $response = $this->httpClient->post('search', [
            'json' => [
                'query' => $query,
                'top_k' => $topK,
                'model' => $model
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
