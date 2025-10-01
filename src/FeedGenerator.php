<?php

namespace OpenAI\Feed;

use League\Csv\Writer;
use SplTempFileObject;

class FeedGenerator
{
    /** @var Product[] */
    protected array $products = [];
    protected array $meta = [];

    public function __construct(array $meta = [])
    {
        $this->meta = array_merge([
            'feed_id' => 'store_feed_' . date('Y_m_d_His'),
            'feed_version' => '1.0',
            'generated_at' => gmdate('c'),
        ], $meta);
    }

    public function addProduct(Product $p): void
    {
        $this->products[] = $p;
    }

    public function addProductFromArray(array $data): void
    {
        // Validate required fields per spec: id, title, description, link, image_link, price, availability, enable_search, enable_checkout, seller_name, seller_url, return_policy, return_window
        // We'll validate a minimal subset but allow flexibility.
        $required = [
            'id',
            'title',
            'description',
            'link',
            'image_link',
            'price',
            'availability',
            'enable_search'
        ];

        foreach ($required as $r) {
            if (!isset($data[$r]) || $data[$r] === '') {
                // throw? We'll allow missing optional ones but for required we throw
                throw new \InvalidArgumentException("Missing required field: $r");
            }
        }

        // enforce enable_search/enable_checkout lowercase strings 'true'/'false'
        if (isset($data['enable_search'])) {
            $data['enable_search'] = strtolower((string)$data['enable_search']);
        }

        if (isset($data['enable_checkout'])) {
            $data['enable_checkout'] = strtolower((string)$data['enable_checkout']);
        }

        $this->addProduct(new Product($data));
    }

    public function toJson(bool $pretty = true): string
    {
        $obj = $this->meta;
        $obj['products'] = array_map(fn($p) => $p->toArray(), $this->products);
        return $pretty ? json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : json_encode($obj);
    }

    public function toCsv(string $delimiter = ','): string
    {
        $headers = $this->collectCsvHeaders();
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter($delimiter);
        $csv->insertOne($headers);
        foreach ($this->products as $p) {
            $arr = $p->toArray();
            $row = [];
            foreach ($headers as $h) {
                $val = $arr[$h] ?? '';
                if (is_array($val)) $val = implode('|', $val);
                $row[] = $val;
            }
            $csv->insertOne($row);
        }
        return (string)$csv;
    }

    protected function collectCsvHeaders(): array
    {
        $fields = [];
        foreach ($this->products as $p) {
            foreach (array_keys($p->toArray()) as $k) {
                if (!in_array($k, $fields)) $fields[] = $k;
            }
        }

        $preferred = [
            'id',
            'item_group_id',
            'item_group_title',
            'gtin',
            'mpn',
            'title',
            'description',
            'link',
            'image_link',
            'additional_image_link',
            'brand',
            'product_category',
            'condition',
            'material',
            'weight',
            'color',
            'size',
            'size_system',
            'gender',
            'price',
            'sale_price',
            'sale_price_effective_date',
            'applicable_taxes_fees',
            'availability',
            'inventory_quantity',
            'shipping',
            'delivery_estimate',
            'seller_name',
            'seller_url',
            'seller_privacy_policy',
            'seller_tos',
            'return_policy',
            'return_window',
            'enable_search',
            'enable_checkout',
            'offer_id',
            'offer_type'
        ];

        $ordered = [];
        foreach ($preferred as $k) {
            if (in_array($k, $fields)) {
                $ordered[] = $k;
            }
        }

        foreach ($fields as $k) {
            if (!in_array($k, $ordered)) {
                $ordered[] = $k;
            }
        }

        return $ordered;
    }

    public function toXml(string $root = 'feed', string $productNode = 'product'): string
    {
        $xml = new \SimpleXMLElement("<{$root}></{$root}>");
        foreach ($this->meta as $k => $v) {
            $xml->addChild($k, htmlspecialchars((string)$v));
        }

        $productsNode = $xml->addChild('products');
        foreach ($this->products as $p) {
            $pn = $productsNode->addChild($productNode);
            $this->arrayToXml($p->toArray(), $pn);
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }

    protected function arrayToXml(array $arr, \SimpleXMLElement $xmlNode)
    {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                // sequential -> repeated child nodes or item array
                if ($this->isSequential($v)) {
                    $child = $xmlNode->addChild($k);
                    foreach ($v as $val) {
                        $child->addChild('item', htmlspecialchars((string)$val));
                    }
                } else {
                    $child = $xmlNode->addChild($k);
                    $this->arrayToXml($v, $child);
                }
            } else {
                $xmlNode->addChild($k, htmlspecialchars((string)$v));
            }
        }
    }

    protected function isSequential(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }

        return array_keys($arr) === range(0, count($arr) - 1);
    }
}
