<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth\LoginController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Authentication Routes
$routes->get('/', 'Auth\LoginController::index');
$routes->get('login', 'Auth\LoginController::index');
$routes->post('login/authenticate', 'Auth\LoginController::authenticate');
$routes->get('logout', 'Auth\LogoutController::index');

// Admin Routes
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // States
    $routes->get('states', 'Admin\StateController::index');
    $routes->post('states/store', 'Admin\StateController::store');
    $routes->get('states/edit/(:num)', 'Admin\StateController::edit/$1');
    $routes->post('states/update/(:num)', 'Admin\StateController::update/$1');
    $routes->get('states/toggle-status/(:num)', 'Admin\StateController::toggleStatus/$1');
    
    // Cities
    $routes->get('cities', 'Admin\CityController::index');
    $routes->post('cities/store', 'Admin\CityController::store');
    $routes->get('cities/edit/(:num)', 'Admin\CityController::edit/$1');
    $routes->post('cities/update/(:num)', 'Admin\CityController::update/$1');
    $routes->get('cities/toggle-status/(:num)', 'Admin\CityController::toggleStatus/$1');
    $routes->get('cities/by-state/(:num)', 'Admin\CityController::getByState/$1');
    
    // Employees
    $routes->get('employees', 'Admin\EmployeeController::index');
    $routes->post('employees/store', 'Admin\EmployeeController::store');
    $routes->get('employees/edit/(:num)', 'Admin\EmployeeController::edit/$1');
    $routes->post('employees/update/(:num)', 'Admin\EmployeeController::update/$1');
    $routes->get('employees/toggle-status/(:num)', 'Admin\EmployeeController::toggleStatus/$1');
    
    // Leads
    $routes->get('leads', 'Admin\LeadController::index');
    $routes->post('leads/store', 'Admin\LeadController::store');
    $routes->get('leads/edit/(:num)', 'Admin\LeadController::edit/$1');
    $routes->post('leads/update/(:num)', 'Admin\LeadController::update/$1');
    $routes->post('leads/update-field/(:num)', 'Admin\LeadController::updateField/$1');
    $routes->get('leads/call/(:num)', 'Admin\LeadController::call/$1');
    $routes->post('leads/log-call/(:num)', 'Admin\LeadController::logCall/$1');
    $routes->get('leads/logs/(:num)', 'Admin\LeadController::logs/$1');
    $routes->get('leads/import', 'Admin\LeadController::import');
    $routes->get('leads/sample-csv', 'Admin\LeadController::sampleCsv');
    $routes->post('leads/process-import', 'Admin\LeadController::processImport');
    $routes->get('leads/export', 'Admin\LeadController::export');
});

// Employee Routes
$routes->group('employee', ['filter' => 'employee'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'Employee\DashboardController::index');
    
    // Leads
    $routes->get('leads', 'Employee\LeadController::index');
    $routes->post('leads/store', 'Employee\LeadController::store');
    $routes->get('leads/edit/(:num)', 'Employee\LeadController::edit/$1');
    $routes->post('leads/update/(:num)', 'Employee\LeadController::update/$1');
    $routes->post('leads/update-field/(:num)', 'Employee\LeadController::updateField/$1');
    $routes->get('leads/call/(:num)', 'Employee\LeadController::call/$1');
    $routes->post('leads/log-call/(:num)', 'Employee\LeadController::logCall/$1');
    $routes->get('leads/logs/(:num)', 'Employee\LeadController::logs/$1');
    $routes->get('leads/import', 'Employee\LeadController::import');
    $routes->get('leads/sample-csv', 'Employee\LeadController::sampleCsv');
    $routes->post('leads/process-import', 'Employee\LeadController::processImport');
    $routes->get('leads/export', 'Employee\LeadController::export');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

