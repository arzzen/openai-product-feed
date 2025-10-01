# OpenAI Product Feed PHP

This package generates Product Feeds compliant with the OpenAI Product Feed Spec:\n
https://developers.openai.com/commerce/specs/feed

It supports exporting in multiple formats:

- **JSON** (with `feed_id`, `feed_version`, `generated_at`)
- **CSV** (flattened structure)
- **XML**

## Installation

You can install this library by using [Composer]. You can also view more info about this on [Packagist].

Add this to the require section in your composer.json file.

```bash
{
    "require": {
        "arzzen/openai-product-feed": "^1.0"
    }
}
```

## Usage

_Command Line Usage:_

The package also includes a CLI script:

```
php bin/generate-feed.php --input=examples/products.json --format=json --output=feed.json
php bin/generate-feed.php --input=examples/products.csv --format=csv  > feed.csv
php bin/generate-feed.php --input=examples/products.json --format=xml  > feed.xml
```

_Or via PHP code:_

```php
require 'vendor/autoload.php';

use OpenAI\Feed\FeedGenerator;

// Feed metadata
$fg = new FeedGenerator([
    'feed_id' => 'example_store_feed_2025_09',
    'feed_version' => '1.0',
    'generated_at' => gmdate('c'),
]);

// Example $data array for a single product
$data = [
    "id" => "SKU-TRAIL-SHOE-BLK-10",
    "item_group_id" => "SHOE-TRAIL-2025",
    "item_group_title" => "Men's Trail Running Shoes",
    "gtin" => "0123456789012",
    "mpn" => "TRAIL-2025",
    "title" => "Men's Trail Running Shoes — Black / Size 10",
    "description" => "Lightweight waterproof trail running shoes with cushioned midsole and aggressive outsole for rocky terrain. Breathable mesh upper with reinforced toe cap.",
    "link" => "https://example-store.com/product/trail-shoe",
    "image_link" => "https://example-store.com/images/trail-shoe-black-10.jpg",
    "additional_image_link" => [
        "https://example-store.com/images/trail-shoe-black-10-side.jpg",
        "https://example-store.com/images/trail-shoe-black-10-sole.jpg"
    ],
    "brand" => "ExampleBrand",
    "product_category" => "Apparel & Accessories > Shoes",
    "condition" => "new",
    "material" => "Synthetic mesh / Rubber outsole",
    "weight" => "1.2 lb",
    "color" => "Black",
    "size" => "10",
    "size_system" => "US",
    "gender" => "male",
    "price" => "129.99 USD",
    "sale_price" => "99.99 USD",
    "sale_price_effective_date" => "2025-10-01/2025-10-07",
    "applicable_taxes_fees" => "0.00 USD",
    "availability" => "in_stock",
    "inventory_quantity" => 42,
    "shipping" => [
        "US:CA:Standard:7.00 USD",
        "US:ALL:Express:16.00 USD"
    ],
    "delivery_estimate" => "2025-10-03",
    "seller_name" => "Example Store",
    "seller_url" => "https://example-store.com/store/example-store",
    "seller_privacy_policy" => "https://example-store.com/privacy",
    "seller_tos" => "https://example-store.com/terms",
    "return_policy" => "https://example-store.com/returns",
    "return_window" => 30,
    "enable_search" => "true",
    "enable_checkout" => "true",
    "offer_id" => "SKU-TRAIL-SHOE-BLK-10|ExampleStore|129.99",
    "offer_type" => "standard"
];

// Add product to feed (you can use in iteration)
$fg->addProductFromArray($data);

// Export feed as array
print_r($fg->toArray());

// Export feed in JSON
echo $fg->toJson();

// Export feed in CSV
echo $fg->toCsv();

// Export feed in XML
echo $fg->toXml();
```

#### Notes

- Minimum PHP version: 7.4 (works with PHP 8.x as well).
- Required fields are validated before export (e.g., `id`, `title`, `price`, `availability`, `enable_search`).
- Supports multiple product entries in a single feed.
