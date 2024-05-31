<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['sys/authentication'] = 'main/login';
$route['sys/admin/dashboard'] = 'main/admin_dashboard';
$route['sys/users/dashboard'] = 'main/users_dashboard';
$route['sys/users/create/tickets/msrf'] = 'main/users_creation_tickets_msfr';
$route['sys/users/create/tickets/tracc'] = 'main/users_creation_tickets_tracc';


$route['sys/admin/users'] = 'main/admin_users';
$route['sys/admin/add/employee'] = 'main/admin_list_employee';
$route['sys/admin/update/employee/(:any)'] = 'main/list_update_employee/$1';
$route['sys/admin/team'] = 'main/admin_team';
$route['sys/admin/list/ticket'] = 'main/admin_list_tickets';
$route['sys/admin/approved/(:any)/(:any)'] = 'main/admin_approval_list/$1/$2';
$route['sys/logout'] = 'main/logout';

$route['default_controller'] = 'main/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;