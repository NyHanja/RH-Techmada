<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('rh', function ($routes) {
    $routes->get('dashboard',                  'RhController::dashboard');
    $routes->get('demandes',                   'RhController::index');
    $routes->post('demandes/approuver/(:num)', 'RhController::approuver/$1');
    $routes->post('demandes/refuser/(:num)',    'RhController::refuser/$1');
});
