# OpenAI Product Feed PHP

This package generates Product Feeds compliant with the OpenAI Product Feed Spec:
https://developers.openai.com/commerce/specs/feed

Supports JSON (feed wrapper), CSV (flattened), and XML output.

## Install

```bash
composer require
```

## Usage

CLI:

```
php bin/generate-feed.php --input=examples/products.json --format=json --output=feed.json
php bin/generate-feed.php --input=examples/products.csv --format=csv  > feed.csv
php bin/generate-feed.php --input=examples/products.json --format=xml  > feed.xml
```

Or via code:

```php
require 'vendor/autoload.php';
use OpenAI\Feed\FeedGenerator;

$fg = new FeedGenerator(['feed_id'=>'example_store_feed_2025_09']);
$fg->addProductFromArray([...]);
file_put_contents('feed.json', $fg->toJson());
```
