<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'About::index');
$routes->get('services', 'Services::index');
$routes->match(['get', 'post'], 'contact', 'Contact::index');
$routes->get('register', 'Register::index');
$routes->post('register', 'Register::create');
$routes->get('login', 'Auth::login', ['as' => 'auth.login']);
$routes->post('login', 'Auth::login');
$routes->post('logout', 'Auth::logout', ['as' => 'auth.logout']);

// The dashboard and customer-account manager require an authenticated session.
$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('dashboard', 'Auth::dashboard', ['as' => 'auth.dashboard']);

    $routes->get('accounts', 'CustomerAccounts::index', ['as' => 'accounts.index']);
    $routes->get('accounts/new', 'CustomerAccounts::new', ['as' => 'accounts.new']);
    $routes->post('accounts', 'CustomerAccounts::create', ['as' => 'accounts.create']);
    $routes->get('accounts/(:num)', 'CustomerAccounts::show/$1', ['as' => 'accounts.show']);
    $routes->get('accounts/(:num)/edit', 'CustomerAccounts::edit/$1', ['as' => 'accounts.edit']);
    $routes->post('accounts/(:num)/update', 'CustomerAccounts::update/$1', ['as' => 'accounts.update']);
    $routes->post('accounts/(:num)/delete', 'CustomerAccounts::delete/$1', ['as' => 'accounts.delete']);
});
