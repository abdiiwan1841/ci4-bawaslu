<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
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

/*AUTH*/
$routes->get('/login', 'Auth::index');
$routes->post('/signin', 'Auth::signIn');
$routes->get('/logout', 'Auth::logout');

/*REDIRECT PAGE */
$routes->get('/forbidden', 'Auth::redirectForbiddenAccess');
$routes->get('/not-found', 'Auth::redirectPageNotFound');

/*FORGOT PASSWORD*/
$routes->get('/forgot-password', 'ForgotPassword::index');
$routes->post('/forgot-password-submit', 'ForgotPassword::submit');
$routes->get('/forgot-password-redirect', 'ForgotPassword::redirectAfterSubmit');
$routes->get('/forgot-password-confirm/(:any)', 'ForgotPassword::confirmEmailLink/$1');
$routes->post('/forgot-password-save-new-password', 'ForgotPassword::saveNewPassword');
$routes->get('/forgot-password-success', 'ForgotPassword::redirectSuccessSaveNewPasssword');

/*USER */
$routes->get('/profile', 'User::profile');
// $routes->get('/profile/edit', 'User::profile_edit');
$routes->post('/profile/update', 'User::profile_update');



// $routes->get('/', 'Auth::index');



/**
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
