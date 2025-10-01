<?php

namespace OpenAI\Feed;

class Product
{
    public string $id;
    public ?string $item_group_id = null;
    public ?string $item_group_title = null;
    public ?string $gtin = null;
    public ?string $mpn = null;
    public string $title;
    public ?string $description = null;
    public ?string $link = null;
    public ?string $image_link = null;
    public array $additional_image_link = [];
    public ?string $brand = null;
    public ?string $product_category = null;
    public string $condition = 'new';
    public ?string $material = null;
    public ?string $dimensions = null;
    public ?string $length = null;
    public ?string $width = null;
    public ?string $height = null;
    public ?string $weight = null;
    public ?string $age_group = null;
    public ?string $video_link = null;
    public ?string $model_3d_link = null;
    public ?string $price = null;
    public ?string $applicable_taxes_fees = null;
    public ?string $sale_price = null;
    public ?string $sale_price_effective_date = null;
    public string $availability = 'in_stock';
    public ?string $availability_date = null;
    public ?int $inventory_quantity = null;
    public ?string $expiration_date = null;
    public array $shipping = [];
    public ?string $delivery_estimate = null;
    public ?string $seller_name = null;
    public ?string $seller_url = null;
    public ?string $seller_privacy_policy = null;
    public ?string $seller_tos = null;
    public ?string $return_policy = null;
    public ?int $return_window = null;
    public ?string $enable_search = null;
    public ?string $enable_checkout = null;
    public ?string $offer_id = null;
    public ?string $offer_type = null;
    public array $raw = [];

    public function __construct(array $data)
    {
        $this->raw = $data;
        if (empty($data['id'])) {
            throw new \InvalidArgumentException('Product id is required');
        }

        if (empty($data['title'])) {
            throw new \InvalidArgumentException('Product title is required');
        }

        $this->id = (string)$data['id'];
        $this->item_group_id = $data['item_group_id'] ?? null;
        $this->item_group_title = $data['item_group_title'] ?? null;
        $this->gtin = $data['gtin'] ?? null;
        $this->mpn = $data['mpn'] ?? null;
        $this->title = (string)($data['title'] ?? '');
        $this->description = $data['description'] ?? null;
        $this->link = $data['link'] ?? null;
        $this->image_link = $data['image_link'] ?? null;
        $this->additional_image_link = $data['additional_image_link'] ?? [];
        $this->brand = $data['brand'] ?? null;
        $this->product_category = $data['product_category'] ?? null;
        $this->condition = $data['condition'] ?? 'new';
        $this->material = $data['material'] ?? null;
        $this->dimensions = $data['dimensions'] ?? null;
        $this->length = $data['length'] ?? null;
        $this->width = $data['width'] ?? null;
        $this->height = $data['height'] ?? null;
        $this->weight = $data['weight'] ?? null;
        $this->age_group = $data['age_group'] ?? null;
        $this->video_link = $data['video_link'] ?? null;
        $this->model_3d_link = $data['model_3d_link'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->applicable_taxes_fees = $data['applicable_taxes_fees'] ?? null;
        $this->sale_price = $data['sale_price'] ?? null;
        $this->sale_price_effective_date = $data['sale_price_effective_date'] ?? null;
        $this->availability = $data['availability'] ?? 'in_stock';
        $this->availability_date = $data['availability_date'] ?? null;
        $this->inventory_quantity = isset($data['inventory_quantity']) ? (int)$data['inventory_quantity'] : null;
        $this->expiration_date = $data['expiration_date'] ?? null;
        $this->shipping = $data['shipping'] ?? [];
        $this->delivery_estimate = $data['delivery_estimate'] ?? null;
        $this->seller_name = $data['seller_name'] ?? null;
        $this->seller_url = $data['seller_url'] ?? null;
        $this->seller_privacy_policy = $data['seller_privacy_policy'] ?? null;
        $this->seller_tos = $data['seller_tos'] ?? null;
        $this->return_policy = $data['return_policy'] ?? null;
        $this->return_window = isset($data['return_window']) ? (int)$data['return_window'] : null;
        $this->enable_search = $data['enable_search'] ?? null;
        $this->enable_checkout = $data['enable_checkout'] ?? null;
        $this->offer_id = $data['offer_id'] ?? null;
        $this->offer_type = $data['offer_type'] ?? null;
    }

    public function toArray(): array
    {
        $arr = [
            'id' => $this->id,
            'item_group_id' => $this->item_group_id,
            'item_group_title' => $this->item_group_title,
            'gtin' => $this->gtin,
            'mpn' => $this->mpn,
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'image_link' => $this->image_link,
            'additional_image_link' => $this->additional_image_link,
            'brand' => $this->brand,
            'product_category' => $this->product_category,
            'condition' => $this->condition,
            'material' => $this->material,
            'dimensions' => $this->dimensions,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
            'age_group' => $this->age_group,
            'video_link' => $this->video_link,
            'model_3d_link' => $this->model_3d_link,
            'price' => $this->price,
            'applicable_taxes_fees' => $this->applicable_taxes_fees,
            'sale_price' => $this->sale_price,
            'sale_price_effective_date' => $this->sale_price_effective_date,
            'availability' => $this->availability,
            'availability_date' => $this->availability_date,
            'inventory_quantity' => $this->inventory_quantity,
            'expiration_date' => $this->expiration_date,
            'shipping' => $this->shipping,
            'delivery_estimate' => $this->delivery_estimate,
            'seller_name' => $this->seller_name,
            'seller_url' => $this->seller_url,
            'seller_privacy_policy' => $this->seller_privacy_policy,
            'seller_tos' => $this->seller_tos,
            'return_policy' => $this->return_policy,
            'return_window' => $this->return_window,
            'enable_search' => $this->enable_search,
            'enable_checkout' => $this->enable_checkout,
            'offer_id' => $this->offer_id,
            'offer_type' => $this->offer_type
        ];

        return array_filter($arr, fn($v) => $v !== null && $v !== []);
    }
}
