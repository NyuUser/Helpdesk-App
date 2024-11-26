<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//--- LOGIN
$route['sys/authentication'] = 'main/login';
//--- REGISTRATION
$route['sys/registration'] = 'main/registration';

//--- DASHBOARDS
//ADMIN Dashboard
$route['sys/admin/dashboard'] = 'main/admin_dashboard';
//USER Dashboard
$route['sys/users/dashboard'] = 'main/users_dashboard';


//--- USER ROUTES ---//
//clickable link to the details page, msrf
$route['sys/users/details/concern/msrf/(:any)'] = 'main/service_form_msrf_details/$1';
//clickable link to the details page, tracc concern
$route['sys/users/details/concern/tracc_concern/(:any)'] = 'main/tracc_concern_form_details/$1';
//clickable link to the details page, tracc request
$route['sys/users/details/concern/tracc_request/(:any)'] = 'main/tracc_request_form_details/$1';


//--- DATATABLE of USER in TRACC CONCERN
$route['sys/users/list/tickets/tracc_concern'] = 'main/service_form_tracc_concern_list';
//--- TICKET CREATION of USER for TRACC CONCERN
$route['sys/users/create/tickets/tracc_concern'] = 'main/user_creation_tickets_tracc_concern';
//--- DATATABLE of USER in MSRF
$route['sys/users/list/tickets/msrf'] = 'main/service_form_msrf_list';
//--- TICKET CREATION of USER for MSRF
$route['sys/users/create/tickets/msrf'] = 'main/users_creation_tickets_msrf';
//--- DATATABLE of USER in TRACC REQUEST FORM
$route['sys/users/list/tickets/tracc_request'] = 'main/service_form_tracc_request_list';
//--- TICKET CREATION of USER for TRACC REQUEST
$route['sys/users/create/tickets/tracc_request'] = 'main/user_creation_tickets_tracc_request';


//--- FORM CREATION of USER for TRACC REQUEST FORM
$route['sys/users/create/tickets/trf_customer_request_form_tms'] = 'main/user_creation_tickets_customer_request_forms_tms';
//--- 
$route['sys/users/create/tickets/trf_customer_shipping_setup'] = 'main/user_creation_tickets_customer_shipping_setup';
//--- 
$route['sys/users/create/tickets/trf_employee_request_form'] = 'main/user_creation_tickets_employee_request_form';
//---
$route['sys/users/create/tickets/trf_item_request_form'] = 'main/user_creation_tickets_item_request_form';
//---
$route['sys/users/create/tickets/trf_supplier_request_form_tms'] = 'main/user_creation_supplier_request_form_tms';


//--- ADMIN ROUTES ---//
//--- DATATABLE of ADMIN TRACC CONCERN
$route['sys/admin/list/ticket/tracc_concern'] = 'main/admin_list_tracc_concern';
//--- DATATABLE of ADMIN for EMPLOYEES/USERS
$route['sys/admin/users'] = 'main/admin_users';
//--- DATATABLE of ADMIN for MSRF
$route['sys/admin/list/ticket/msrf'] = 'main/admin_list_tickets'; 
//--- DATATABLE of ADMIN for DEPARTMENTS
$route['sys/admin/team'] = 'main/admin_team';
//--- DATATABLE of ADMIN for TRACC REQUEST
$route['sys/admin/list/ticket/tracc_request'] = 'main/admin_list_tracc_request';
//--- DATATABLE of ADMIN for PRINT REPORTS
$route['sys/admin/print'] = 'main/admin_print_report';


//--- TICKET APPROVAL for MSRF and TRACC CONCERN and TRACC REQUEST
$route['sys/admin/approved/(:any)/(:any)'] = 'main/admin_approval_list/$1/$2';


//--- Admin CREATION of EMPLOYEE
$route['sys/admin/add/employee'] = 'main/admin_list_employee';
//--- Admin UPDATING of EMPLOYEE
$route['sys/admin/update/employee/(:any)'] = 'main/list_update_employee/$1';
//--- Admin DELETING of EMPLOYEE
$route['sys/admin/delete/employee/(:any)'] = 'main/employee_delete/$1';
//--- Admin CREATION of DEPARTMENTS
$route['sys/admin/add/department'] = 'main/admin_list_department';
//--- Admin UPDATING of DEPARTMENTS
$route['sys/admin/update/department/(:num)'] = 'main/list_update_department/$1';
//--- Admin DELETING of DEPARTMENTS
$route['sys/admin/delete/department/(:any)'] = 'main/department_delete/$1';

//
$route['Main/download_file/(:any)'] = 'Main/download_file/$1';


//--- System LOGOUT
$route['sys/logout'] = 'main/logout';


$route['default_controller'] = 'main/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;