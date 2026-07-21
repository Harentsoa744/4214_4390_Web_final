<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Redirection de la racine vers le login client
$routes->get('/', function() {
    return redirect()->to('/login');
});

// ==========================================
// CLIENT ROUTES
// ==========================================
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('client', ['filter' => 'clientAuth'], function($routes) {
    $routes->get('dashboard', 'ClientController::dashboard');
    $routes->get('history', 'ClientController::history');
    
    // Transactions
    $routes->get('deposit', 'TransactionController::deposit');
    $routes->post('deposit', 'TransactionController::processDeposit');
    
    $routes->get('withdraw', 'TransactionController::withdraw');
    $routes->post('withdraw', 'TransactionController::processWithdraw');
    
    $routes->get('transfer', 'TransactionController::transfer');
    $routes->post('transfer', 'TransactionController::processTransfer');

    $routes->get('epargne', 'EpargneController::index');
});

// ==========================================
// OPERATOR ROUTES
// ==========================================
$routes->get('/operator/login', '\App\Controllers\Operator\AuthController::login');
$routes->post('/operator/login', '\App\Controllers\Operator\AuthController::processLogin');
$routes->get('/operator/logout', '\App\Controllers\Operator\AuthController::logout');

$routes->group('operator', ['namespace' => 'App\Controllers\Operator', 'filter' => 'operatorAuth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    
    // Prefixes
    $routes->get('prefixes', 'PrefixController::index');
    $routes->post('prefixes', 'PrefixController::store');
    $routes->post('prefixes/toggle/(:num)', 'PrefixController::toggle/$1');
    $routes->post('prefixes/delete/(:num)', 'PrefixController::delete/$1');
    
    // Operation Types
    $routes->get('operation-types', 'OperationTypeController::index');
    $routes->post('operation-types', 'OperationTypeController::store');
    $routes->post('operation-types/toggle/(:num)', 'OperationTypeController::toggle/$1');
    
    // Fee Brackets
    $routes->get('fees', 'FeeBracketController::index');
    $routes->post('fees', 'FeeBracketController::store');
    $routes->post('fees/delete/(:num)', 'FeeBracketController::delete/$1');
    
    // Clients
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/(:num)', 'ClientController::show/$1');

    // V2: Operators Management
    $routes->get('operators', 'OperatorManagementController::index');
    $routes->post('operators', 'OperatorManagementController::store');
    $routes->post('operators/toggle/(:num)', 'OperatorManagementController::toggle/$1');

    // V2: Commissions Management
    $routes->get('commissions', 'CommissionController::index');
    $routes->post('commissions', 'CommissionController::store');
    $routes->post('commissions/toggle/(:num)', 'CommissionController::toggle/$1');
    $routes->post('commissions/delete/(:num)', 'CommissionController::delete/$1');
    
    // V2: Settlements
    $routes->get('settlements', 'SettlementController::index');
    $routes->post('settlements/markAsSent/(:num)', 'SettlementController::markAsSent/$1');

});
