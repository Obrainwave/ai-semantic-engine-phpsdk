<?php

require __DIR__ . '/vendor/autoload.php';

use AiSemanticEngine\Client;

function getApiKey() {
    $email = 'php_sdk_' . time() . '@example.com';
    $ch = curl_init('http://localhost:8200/signup');
    $payload = json_encode([
        'name' => 'PHP SDK Tester ' . time(),
        'email' => $email,
        'password' => 'password'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    return $data['api_key'];
}

echo "Getting test API key...\n";
$apiKey = getApiKey();

echo "Initializing SDK with key: $apiKey\n";
$client = new Client($apiKey, 'http://localhost:8200');

echo "\n--- Testing embed() ---\n";
$embedRes = $client->embed(['Hello from PHP SDK', 'Second sentence']);
echo "Result keys: " . implode(', ', array_keys($embedRes)) . "\n";
echo "Embedded texts count: " . count($embedRes['embeddings']) . "\n";

echo "\n--- Testing similarity() ---\n";
$simRes = $client->similarity('Python SDK', 'PHP SDK');
echo "Similarity Score: " . $simRes['similarity_score'] . "\n";

echo "\n--- Testing storeItem() ---\n";
$storeRes = $client->storeItem('PHP SDK is fully functional', ['language' => 'php']);
echo "Stored Item ID: " . $storeRes['id'] . "\n";

echo "\n--- Testing search() ---\n";
$searchRes = $client->search('php', 2);
echo "Got " . count($searchRes['results']) . " results.\n";
if (!empty($searchRes['results'])) {
    echo "Top result text: " . $searchRes['results'][0]['text'] . "\n";
}
