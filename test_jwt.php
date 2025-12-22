<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

echo "Testing JWTAuth::attempt...\n";

$creds = ['email' => 'yusronalgoni@gmail.com', 'password' => 'password'];

$token = JWTAuth::attempt($creds);

if ($token) {
    echo "Token received: " . substr($token, 0, 50) . "...\n";
    $user = Auth::user();
    echo "User: " . json_encode($user) . "\n";
} else {
    echo "Failed to generate token\n";
}
