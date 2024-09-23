<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//login
$route['sys/authentication'] = 'main/login';
//registration
$route['sys/registration'] = 'main/registration';

//dashboards
$route['sys/admin/dashboard'] = 'main/admin_dashboard';
$route['sys/users/dashboard'] = 'main/users_dashboard';

//users routes
//clickable link to the details page, msrf
$route['sys/users/details/concern/msrf/(:any)'] = 'main/service_form_msrf_details/$1';
$route['sys/users/details/concern/tracc_concern/(:any)'] = 'main/tracc_concern_form_details/$1';

//bagong gagawin
$route['sys/users/list/tickets/tracc_concern'] = 'main/service_form_tracc_concern_list';
$route['sys/users/create/tickets/tracc_concern'] = 'main/user_creation_tickets_tracc_concern';
//bagong gagawin
$route['sys/admin/list/ticket/tracc_concern'] = 'main/admin_list_tracc_concern';


$route['sys/users/list/tickets/msrf'] = 'main/service_form_msrf_list';
//$route['sys/users/create/tickets/tracc'] = 'main/users_creation_tickets_tracc';
$route['sys/users/create/tickets/msrf'] = 'main/users_creation_tickets_msrf';

//admin routes
$route['sys/admin/users'] = 'main/admin_users';
$route['sys/admin/add/employee'] = 'main/admin_list_employee';
$route['sys/admin/update/employee/(:any)'] = 'main/list_update_employee/$1';
$route['sys/admin/delete/employee/(:any)'] = 'main/employee_delete/$1';
$route['sys/admin/team'] = 'main/admin_team';
$route['sys/admin/list/ticket/msrf'] = 'main/admin_list_tickets'; 

//kunin
$route['sys/admin/approved/(:any)/(:any)'] = 'main/admin_approval_list/$1/$2';
$route['sys/logout'] = 'main/logout';
$route['sys/admin/add/department'] = 'main/admin_list_department';
$route['sys/admin/update/department/(:num)'] = 'main/list_update_department/$1';
$route['sys/admin/delete/department/(:any)'] = 'main/department_delete/$1';



$route['default_controller'] = 'main/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;