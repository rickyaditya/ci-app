<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();
$session = Services::session();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('\Frontend\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', '\Frontend\Home::index');

/**
 * User auth routes
 */
$routes->post('users/auth', '\Frontend\Users::auth');
$routes->get('users/logout', '\Frontend\Users::logout');

/**
 * Reset password routes
 */
$routes->match(['get', 'post'], 'users/register', '\Frontend\Users::register');
$routes->match(['get', 'post'], 'users/reset-password', '\Frontend\Users::reset_password');
$routes->post('users/confirmation-reset', '\Frontend\Users::confirm_reset_password');

/**
 * Backend routes
 */
$routes->get('backend/login', '\Backend\Admin::login');
$routes->post('backend/auth', '\Backend\Admin::auth');
$routes->get('backend/logout', '\Backend\Admin::logout');
$routes->match(['get', 'post'],'backend/register', '\Backend\Admin::register');
$routes->group('backend', ['filter' => 'backend_auth'], function($routes) {
    // Main menu
    $routes->get('dashboard', '\Backend\Admin::index');
    // News admin
    $routes->get('news', '\Backend\News::index');
    $routes->match(['get', 'post'], 'news/create', '\Backend\News::create');
    $routes->get('news/delete/(:any)', '\Backend\News::deleteNews/$1');
    $routes->match(['get', 'post'], 'news/edit/(:any)', '\Backend\News::editNews/$1');
    // Users admin
    $routes->get('users', '\Backend\Users::index');
    $routes->get('users/delete/(:any)', '\Backend\Users::deleteUser/$1');
    // Admin setting
    $routes->match(['get', 'post'], 'settings/(:any)', '\Backend\Admin::getAdminData');
});

/**
 * Pages routes
 */
$routes->get('(:any)', '\Frontend\Pages::view/$1');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
