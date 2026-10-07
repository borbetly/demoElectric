<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::login');


// route for login page
$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::login');

// route for dashboard page
$routes->get('/dashboard', 'Home::dashboard');


// route for logout
$routes->post('/logout', 'Home::logout');
