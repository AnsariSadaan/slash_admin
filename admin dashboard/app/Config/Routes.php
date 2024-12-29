<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['GET', 'POST'], '/register', 'Register::Register');
$routes->match(['GET', 'POST'], '/adduser', 'Adduser::adduser');

$routes->match(['GET', 'POST'], '/logout', 'Logout::Logout');
$routes->match(['GET', 'POST'], '/login', 'Login::Login');



$routes->match(['GET', 'POST'], '/dashboard', 'User::Dashboard');
$routes->match(['GET', 'POST'], '/update-user', 'User::updateUser');
$routes->get('/delete-user/(:num)', 'User::deleteUser/$1');
$routes->delete('/delete-user/(:num)', 'User::deleteUser/$1');



// $routes->get('/campaign', 'Campaign::addCampaign');
$routes->post('/campaign/store', 'Campaign::storeCampaign');
$routes->get('/showCampaign', 'Campaign::showCampaign');
$routes->post('/update-campaign', 'Campaign::updateCampaign');
$routes->get('/delete-campaign/(:num)', 'Campaign::deleteCampaign/$1');
$routes->delete('/delete-campaign/(:num)', 'Campaign::deleteCampaign/$1');



$routes->get('/accesslevel', 'AccessLevel::accessLevel');
$routes->post('/update-role/(:num)', 'AccessLevel::updateRole/$1');


$routes->match(['GET', 'POST'],'/chat', 'Chat::chat');

