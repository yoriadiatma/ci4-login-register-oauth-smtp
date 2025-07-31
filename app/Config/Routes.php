<?php

use App\Controllers\EmailController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/send-email', 'EmailController::index');

$routes->get('/auth/login', [\App\Controllers\AuthController::class, 'login']);
$routes->post('/auth/login', [\App\Controllers\AuthController::class, 'login']);
$routes->get('/auth/register', [\App\Controllers\AuthController::class, 'register']);
$routes->post('/auth/register', [\App\Controllers\AuthController::class, 'register']);
$routes->get('/auth/logout', [\App\Controllers\AuthController::class, 'logout']); // logout

$routes->get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);

$routes->get('/auth/verify/(:segment)', [\App\Controllers\AuthController::class, 'verify/$1']);
$routes->match(['GET', 'POST'], 'auth/forgot', [\App\Controllers\AuthController::class, 'forgot']);
$routes->match(['GET', 'POST'], 'auth/reset/(:segment)', [\App\Controllers\AuthController::class, 'reset/$1']);

$routes->get('auth/google', 'AuthController::google');
$routes->get('auth/google/callback', 'AuthController::googleCallback');

// Rute untuk Toko dan Pembayaran
// Pastikan user sudah login sebelum mengakses toko
$routes->get('/shop', 'ShopController::index'); 
$routes->get('/shop/history', 'ShopController::history');
$routes->post('/midtrans/process', 'MidtransController::process');
$routes->post('/midtrans/notification', 'MidtransController::notification');
