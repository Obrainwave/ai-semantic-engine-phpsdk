# AI Semantic Engine PHP SDK

The official PHP client for the AI Semantic Engine.

## Installation

```bash
composer require obrainwave/ai-semantic-engine-phpsdk
```

## Usage

```php
<?php
require 'vendor/autoload.php';
use AiSemanticEngine\Client;

$client = new Client('http://localhost:8200', 'YOUR_API_KEY');

// 1. Embed Text
$embeddings = $client->embed("Hello world");

// 2. Compute Similarity
$score = $client->similarity("Apple", "Orange");

// 3. Store Knowledge
$client->store("Machine learning is fascinating.", ["topic" => "AI"]);

// 4. Search Knowledge
$results = $client->search("Tell me about AI", 3);
print_r($results);
```

## Endpoints Supported

- `embed` and `embed-batch`
- `similarity`
- `store` (items endpoint)
- `search`
