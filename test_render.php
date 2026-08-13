<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/ojt/logbooks/create', 'GET');
$request->setLaravelSession(Illuminate\Support\Facades\Session::getHandler());
try {
    $response = $app->handle($request);
    $content = $response->getContent();
    $pos = strpos($content, 'x-data=');
    if ($pos !== false) {
        echo substr($content, $pos, 3000);
    } else {
        echo "x-data not found in response\n";
    }
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
