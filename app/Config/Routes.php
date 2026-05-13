<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::loginPost');
$routes->get('/logout', 'AuthController::logout');

// Employé
$routes->group('employe', ['filter' => 'auth:employe'], function($routes) {
    $routes->get('dashboard',                    'EmployeController::dashboard');
    $routes->get('demandes',                     'EmployeController::index');
    $routes->get('demandes/new',                 'EmployeController::create');
    $routes->post('demandes/new',                'EmployeController::store');
    $routes->post('demandes/annuler/(:num)',     'EmployeController::annuler/$1');
    $routes->get('profil',                       'EmployeController::profil');
    $routes->post('profil',                      'EmployeController::profilUpdate');
});

// RH
$routes->group('rh', ['filter' => 'auth:rh'], function($routes) {
    $routes->get('dashboard',                    'RhController::dashboard');
    $routes->get('demandes',                     'RhController::index');
    $routes->post('demandes/approuver/(:num)',   'RhController::approuver/$1');
    $routes->post('demandes/refuser/(:num)',      'RhController::refuser/$1');
});

// Admin
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard',                    'AdminController::dashboard');
    $routes->get('employes',                     'AdminController::employes');
    $routes->get('employes/new',                 'AdminController::createEmploye');
    $routes->post('employes',                    'AdminController::storeEmploye');
    $routes->get('employes/(:num)/edit',         'AdminController::editEmploye/$1');
    $routes->post('employes/(:num)',             'AdminController::updateEmploye/$1');
    $routes->post('employes/(:num)/deactivate',  'AdminController::deactivateEmploye/$1');
    $routes->get('departements',                 'AdminController::departements');
    $routes->get('types-conge',                  'AdminController::typesConge');
    $routes->get('soldes',                       'AdminController::soldes');
});