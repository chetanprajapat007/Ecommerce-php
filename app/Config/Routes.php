<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root to login
$routes->get('/', function() {
    return redirect()->to('/login');
});

// Authentication Routes
$routes->get('login', 'Auth\LoginController::index');
$routes->post('login/authenticate', 'Auth\LoginController::authenticate');
$routes->get('logout', 'Auth\LogoutController::index');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // State Management
    $routes->get('states', 'Admin\StateController::index');
    $routes->post('states/store', 'Admin\StateController::store');
    $routes->get('states/edit/(:num)', 'Admin\StateController::edit/$1');
    $routes->post('states/update/(:num)', 'Admin\StateController::update/$1');
    $routes->get('states/delete/(:num)', 'Admin\StateController::delete/$1');
    
    // City Management
    $routes->get('cities', 'Admin\CityController::index');
    $routes->post('cities/store', 'Admin\CityController::store');
    $routes->get('cities/edit/(:num)', 'Admin\CityController::edit/$1');
    $routes->post('cities/update/(:num)', 'Admin\CityController::update/$1');
    $routes->get('cities/delete/(:num)', 'Admin\CityController::delete/$1');
    $routes->get('cities/by-state/(:num)', 'Admin\CityController::getCitiesByState/$1');
    
    // Employee Management
    $routes->get('employees', 'Admin\EmployeeController::index');
    $routes->post('employees/store', 'Admin\EmployeeController::store');
    $routes->get('employees/edit/(:num)', 'Admin\EmployeeController::edit/$1');
    $routes->post('employees/update/(:num)', 'Admin\EmployeeController::update/$1');
    $routes->get('employees/toggle-status/(:num)', 'Admin\EmployeeController::toggleStatus/$1');
    
    // Lead Management
    $routes->get('leads', 'Admin\LeadController::index');
    $routes->post('leads/store', 'Admin\LeadController::store');
    $routes->get('leads/edit/(:num)', 'Admin\LeadController::edit/$1');
    $routes->post('leads/update/(:num)', 'Admin\LeadController::update/$1');
    $routes->post('leads/update-field/(:num)', 'Admin\LeadController::updateField/$1');
    $routes->get('leads/call/(:num)', 'Admin\LeadController::call/$1');
    $routes->post('leads/log-call/(:num)', 'Admin\LeadController::logCall/$1');
    $routes->get('leads/logs/(:num)', 'Admin\LeadController::logs/$1');
    $routes->get('leads/import', 'Admin\LeadController::import');
    $routes->post('leads/process-import', 'Admin\LeadController::processImport');
    $routes->get('leads/export', 'Admin\LeadController::export');
    $routes->get('leads/sample-csv', 'Admin\LeadController::sampleCsv');
});

// Employee Routes
$routes->group('employee', function($routes) {
    $routes->get('dashboard', 'Employee\DashboardController::index');
    
    // Lead Management
    $routes->get('leads', 'Employee\LeadController::index');
    $routes->post('leads/store', 'Employee\LeadController::store');
    $routes->get('leads/edit/(:num)', 'Employee\LeadController::edit/$1');
    $routes->post('leads/update/(:num)', 'Employee\LeadController::update/$1');
    $routes->post('leads/update-field/(:num)', 'Employee\LeadController::updateField/$1');
    $routes->get('leads/call/(:num)', 'Employee\LeadController::call/$1');
    $routes->post('leads/log-call/(:num)', 'Employee\LeadController::logCall/$1');
    $routes->get('leads/logs/(:num)', 'Employee\LeadController::logs/$1');
    $routes->get('leads/import', 'Employee\LeadController::import');
    $routes->post('leads/process-import', 'Employee\LeadController::processImport');
    $routes->get('leads/export', 'Employee\LeadController::export');
    $routes->get('leads/sample-csv', 'Employee\LeadController::sampleCsv');
});
