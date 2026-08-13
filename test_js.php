<?php
require 'vendor/autoload.php';

$categoryMap = [1 => 'EXC', 2 => 'DZ'];
$formPayload = [];

$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE;

$categoryMapJson = json_encode($categoryMap, $jsonFlags);
$formPayloadJson = json_encode($formPayload, $jsonFlags);

$xdata = "{
    categoryId: '1',
    categoryMap: {$categoryMapJson},
    existingSopPayload: {$formPayloadJson},
    get totalHm() {
        let calc = parseFloat(this.hmEnd) - parseFloat(this.hmStart);
        return isNaN(calc) || calc < 0 ? '0.0' : calc.toFixed(1);
    },
    get hmError() {
        const start = parseFloat(this.hmStart);
        const end = parseFloat(this.hmEnd);
        if (this.hmStart === '' || this.hmEnd === '' || isNaN(start) || isNaN(end)) {
            return '';
        }
        if (end < start) {
            return 'HM Akhir tidak boleh lebih kecil dari HM Awal.';
        }
        return '';
    },
    fillExistingChecklist() {
        this.\$root.querySelectorAll('[name]').forEach((field) => {
            if (!field.name.startsWith('sop_payload[')) return;
        });
    }
}";

echo "x-data string:\n";
echo $xdata . "\n\n";

// Try to decode as JSON (not perfect but checks for obvious issues)
$jsTest = str_replace(['get ', 'let ', 'const ', 'return ', 'if (', 'parseFloat(', 'isNaN(', 'this.', "this.\$root", "!field.name.startsWith(", "field.name.matchAll("], '', $xdata);
$jsTest = preg_replace('/\s+/', '', $jsTest);

// Just check if braces are balanced
$open = substr_count($xdata, '{');
$close = substr_count($xdata, '}');
echo "Open braces: $open, Close braces: $close\n";

if ($open === $close) {
    echo "Braces balanced.\n";
} else {
    echo "WARNING: Braces NOT balanced!\n";
}

// Check for obvious syntax issues
if (preg_match('/,\s*}/', $xdata)) {
    echo "WARNING: Trailing comma before closing brace!\n";
} else {
    echo "No trailing comma issue.\n";
}
