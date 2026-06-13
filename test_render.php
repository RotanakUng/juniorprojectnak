<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/users', 'GET');
$user = App\Models\User::first();
$app['auth']->login($user);

$response = $app->handle($request);
echo $response->getContent();
