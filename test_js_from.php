<?php
require 'vendor/autoload.php';

$map = ['1' => 'EXC', '2' => 'DZ'];
$payload = ['track' => ['groups' => [['items' => [['status' => 'K']]]]]];

echo "categoryMap: " . Illuminate\Support\Js::from($map)->toHtml() . "\n\n";
echo "existingSopPayload: " . Illuminate\Support\Js::from($payload)->toHtml() . "\n\n";

// Test with empty array
echo "empty array: " . Illuminate\Support\Js::from([])->toHtml() . "\n";
