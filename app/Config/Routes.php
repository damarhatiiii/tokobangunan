<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('analysis', 'Dashboard::analysis');

    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');

    $routes->get('reports', 'Reports::index');
    $routes->get('reports/stock-pdf', 'Reports::stockPdf');
    $routes->get('reports/sales-pdf', 'Reports::salesPdf');
    $routes->get('reports/forecast-pdf', 'Reports::forecastPdf');
    $routes->get('reports/inbound-pdf', 'Reports::inboundPdf');
    $routes->get('reports/outbound-pdf', 'Reports::outboundPdf');

    $routes->get('products', 'Products::index');
    $routes->get('products/show/(:num)', 'Products::show/$1');

    $routes->get('sales', 'Sales::index');
    $routes->get('sales/receipt/(:num)', 'Sales::receipt/$1');

    $routes->group('', ['filter' => 'roles:admin'], static function ($routes) {
        $routes->get('users', 'Users::index');
        $routes->get('users/create', 'Users::create');
        $routes->post('users/store', 'Users::store');
        $routes->get('users/edit/(:num)', 'Users::edit/$1');
        $routes->post('users/update/(:num)', 'Users::update/$1');
        $routes->post('users/delete/(:num)', 'Users::delete/$1');
    });

    $routes->group('', ['filter' => 'roles:admin,petugas'], static function ($routes) {
        $routes->get('categories', 'Categories::index');
        $routes->get('categories/create', 'Categories::create');
        $routes->post('categories/store', 'Categories::store');
        $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
        $routes->post('categories/update/(:num)', 'Categories::update/$1');
        $routes->post('categories/delete/(:num)', 'Categories::delete/$1');

        $routes->get('satuan', 'Satuan::index');
        $routes->get('satuan/create', 'Satuan::create');
        $routes->post('satuan/store', 'Satuan::store');
        $routes->get('satuan/edit/(:num)', 'Satuan::edit/$1');
        $routes->post('satuan/update/(:num)', 'Satuan::update/$1');
        $routes->post('satuan/delete/(:num)', 'Satuan::delete/$1');

        $routes->get('suppliers', 'Suppliers::index');
        $routes->get('suppliers/create', 'Suppliers::create');
        $routes->post('suppliers/store', 'Suppliers::store');
        $routes->get('suppliers/edit/(:num)', 'Suppliers::edit/$1');
        $routes->post('suppliers/update/(:num)', 'Suppliers::update/$1');
        $routes->post('suppliers/delete/(:num)', 'Suppliers::delete/$1');

        $routes->get('products/create', 'Products::create');
        $routes->post('products/store', 'Products::store');
        $routes->get('products/edit/(:num)', 'Products::edit/$1');
        $routes->post('products/update/(:num)', 'Products::update/$1');
        $routes->post('products/delete/(:num)', 'Products::delete/$1');

        $routes->get('barang-masuk', 'BarangMasuk::index');
        $routes->get('barang-masuk/create', 'BarangMasuk::create');
        $routes->post('barang-masuk/store', 'BarangMasuk::store');
        $routes->get('barang-masuk/receipt/(:num)', 'BarangMasuk::receipt/$1');

        $routes->get('sales/create', 'Sales::create');
        $routes->post('sales/store', 'Sales::store');
    });
});

