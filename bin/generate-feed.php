#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use OpenAI\Feed\FeedGenerator;

$options = getopt('', ['input:', 'format::', 'output::']);
$input = $options['input'] ?? null;
$format = strtolower($options['format'] ?? 'json');
$output = $options['output'] ?? null;

if (!$input) {
    echo "Usage: generate-feed.php --input=path/to/products.csv|json --format=json|csv|xml [--output=path]\n";
    exit(1);
}

$meta = [
    'feed_id' => $options['feed_id'] ?? 'example_store_feed_' . uniqid(),
    'feed_version' => '1.0',
    'generated_at' => gmdate('c')
];
$fg = new FeedGenerator($meta);

$ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
if (in_array($ext, ['csv', 'tsv'])) {
    if (!file_exists($input)) {
        echo "Input not found: $input\n";
        exit(2);
    }

    $delim = $ext === 'tsv' ? "\t" : ",";
    $fh = fopen($input, 'r');
    $headers = fgetcsv($fh, 0, $delim);
    while ($row = fgetcsv($fh, 0, $delim)) {
        $data = [];
        foreach ($headers as $i => $h) {
            $data[$h] = $row[$i] ?? null;
        }

        if (isset($data['additional_image_link'])) {
            $data['additional_image_link'] = array_filter(array_map('trim', explode('|', $data['additional_image_link'])));
        }

        if (isset($data['shipping'])) {
            $data['shipping'] = array_filter(array_map('trim', explode('|', $data['shipping'])));
        }
        print_r($data);
        $fg->addProductFromArray($data);
    }
    fclose($fh);
} else {
    if (!file_exists($input)) {
        echo "Input not found: $input\n";
        exit(2);
    }

    $json = json_decode(file_get_contents($input), true);
    if (isset($json['products']) && is_array($json['products'])) {
        $items = $json['products'];
    } elseif (is_array($json)) {
        $items = $json;
    } else {
        echo "Unsupported JSON structure in input\n";
        exit(3);
    }

    foreach ($items as $it) {
        $fg->addProductFromArray($it);
    }
}

switch ($format) {
    case 'json':
        $out = $fg->toJson(true);
        break;
    case 'csv':
        $out = $fg->toCsv();
        break;
    case 'xml':
        $out = $fg->toXml();
        break;
    default:
        echo "Unknown format: $format\n";
        exit(4);
}

if ($output) {
    file_put_contents($output, $out);
    echo "Wrote $format feed to $output\n";
} else {
    echo $out;
}
