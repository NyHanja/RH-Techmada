<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');

// Auth
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::loginPost');
$routes->get('/logout', 'AuthController::logout');

//employe
$routes->group('employe', ['filter' => 'auth:employe'], function($routes) {
    $routes->get('dashboard',        'EmployeController::dashboard');
    $routes->get('demandes',         'EmployeController::index');
    $routes->get('demandes/new',     'EmployeController::create');
    $routes->post('demandes/new',    'EmployeController::store');
    $routes->post('demandes/annuler/(:num)', 'EmployeController::annuler/$1');
    $routes->get('profil',           'EmployeController::profil');
    $routes->post('profil',          'EmployeController::profilUpdate');
});


