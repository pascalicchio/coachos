<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    '/api/login',
    'POST',
    [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
    ]
);

$response = $kernel->handle($request);

echo $response->getContent();
