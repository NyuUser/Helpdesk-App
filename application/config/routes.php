<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//--- LOGIN
$route['sys/authentication'] = 'main/login';
//--- REGISTRATION
$route['sys/registration'] = 'main/registration';

//--- DASHBOARDS
//ADMIN Dashboard
$route['sys/admin/dashboard'] = 'AdminDashboard_controller/admin_dashboard';
//USER Dashboard
$route['sys/users/dashboard'] = 'UsersDashboard_controller/users_dashboard';


//--- USER ROUTES ---//
//--- DATATABLE of USER in MSRF
$route['sys/users/list/tickets/msrf'] = 'UsersMSRF_controller/service_form_msrf_list';
//--- TICKET CREATION of USER for MSRF
$route['sys/users/create/tickets/msrf'] = 'UsersMSRF_controller/users_creation_tickets_msrf';
//clickable link to the details page, msrf
$route['sys/users/details/concern/msrf/(:any)'] = 'UsersMSRF_controller/service_form_msrf_details/$1';


//--- DATATABLE of USER in TRACC CONCERN
$route['sys/users/list/tickets/tracc_concern'] = 'UsersTraccCon_controller/service_form_tracc_concern_list';
//--- TICKET CREATION of USER for TRACC CONCERN
$route['sys/users/create/tickets/tracc_concern'] = 'UsersTraccCon_controller/user_creation_tickets_tracc_concern';
//clickable link to the details page, tracc concern
$route['sys/users/details/concern/tracc_concern/(:any)'] = 'UsersTraccCon_controller/tracc_concern_form_details/$1';

//--- DATATABLE of USER in TRACC REQUEST FORM
$route['sys/users/list/tickets/tracc_request'] = 'UsersTraccReq_controller/service_form_tracc_request_list';
//--- TICKET CREATION of USER for TRACC REQUEST
$route['sys/users/create/tickets/tracc_request'] = 'UsersTraccReq_controller/user_creation_tickets_tracc_request';
//clickable link to the details page, tracc request
$route['sys/users/details/concern/tracc_request/(:any)'] = 'UsersTraccReq_controller/tracc_request_form_details/$1';


//--- FORM CREATION of USER for TRACC REQUEST FORM PDF
$route['sys/users/create/tickets/trf_customer_request_form_tms'] = 'UsersTraccReq_controller/user_creation_tickets_customer_request_forms_tms';
//--- 
$route['sys/users/create/tickets/trf_customer_shipping_setup'] = 'UsersTraccReq_controller/user_creation_tickets_customer_shipping_setup';
//--- 
$route['sys/users/create/tickets/trf_employee_request_form'] = 'UsersTraccReq_controller/user_creation_tickets_employee_request_form';
//---
$route['sys/users/create/tickets/trf_item_request_form'] = 'UsersTraccReq_controller/user_creation_tickets_item_request_form';
//---
$route['sys/users/create/tickets/trf_supplier_request_form_tms'] = 'UsersTraccReq_controller/user_creation_tickets_supplier_request_form_tms';

//--- DETAILS FORM of USER for TRACC REQUEST FORM PDF
$route['sys/users/details/concern/customer_req_form/(:any)'] = 'UsersTraccReq_controller/customer_request_form_rf_details/$1';

$route['sys/users/details/concern/customer_req_ship_setup/(:any)'] = 'UsersTraccReq_controller/customer_request_form_ss_details/$1';

$route['sys/users/details/concern/customer_req_item_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_ir_details/$1';

$route['sys/users/details/concern/customer_req_employee_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_er_details/$1';

$route['sys/users/details/concern/customer_req_supplier_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_sr_details/$1';


//--- ADMIN ROUTES ---//
//--- DATATABLE of ADMIN for MSRF
$route['sys/admin/list/ticket/msrf'] = 'AdminMSRF_controller/admin_list_tickets'; 
//--- DATATABLE of ADMIN TRACC CONCERN
$route['sys/admin/list/ticket/tracc_concern'] = 'AdminTraccCon_controller/admin_list_tracc_concern';
//--- DATATABLE of ADMIN for TRACC REQUEST
$route['sys/admin/list/ticket/tracc_request'] = 'AdminTraccReq_controller/admin_list_tracc_request';
//--- DATATABLE of ADMIN for EMPLOYEES/USERS
$route['sys/admin/users'] = 'AdminUsers_controller/admin_users';
//--- DATATABLE of ADMIN for DEPARTMENTS
$route['sys/admin/team'] = 'AdminDept_controller/admin_team';
//--- DATATABLE of ADMIN for MSRF Closed Ticket
$route['sys/admin/list/ticket/msrf_closed'] = 'AdminMSRF_controller/admin_closed_tickets';
//--- DATATABLE of ADMIN for Tracc Concern Closed Ticket
$route['sys/admin/list/ticket/tracc_concerns_closed'] = 'AdminTraccCon_controller/admin_closed_tickets';
//--- DATATABLE of ADMIN for Tracc Request Closed Ticket
$route['sys/admin/list/ticket/tracc_request_closed'] = 'AdminTraccReq_controller/admin_closed_tickets';

//--- ADMIN for PRINT REPORTS
$route['sys/admin/print'] = 'AdminGenerateReport_controller/admin_print_report';


//--- PDF REPORTS VIEWING ADMIN ---//
$route['sys/admin/customer_request_form_pdf'] = 'main/customer_request_form_pdf_view';
$route['sys/admin/customer_shipping_setup_pdf'] = 'main/customer_shipping_setup_pdf_view';
$route['sys/admin/employee_request_form_pdf'] = 'main/employee_request_form_pdf_view';
$route['sys/admin/item_request_form_pdf'] = 'main/item_request_form_pdf_view';
$route['sys/admin/supplier_request_form_pdf'] = 'main/supplier_request_form_pdf_view';


//--- TICKET APPROVAL for MSRF and TRACC CONCERN and TRACC REQUEST
$route['sys/admin/approved/(:any)/(:any)'] = 'main/admin_approval_list/$1/$2';

$route['sys/admin/list/closed_tickets/(:any)/(:any)'] = 'main/get_closed_tickets/$1/$2';


//--- Admin CREATION of EMPLOYEE
$route['sys/admin/add/employee'] = 'AdminUsers_controller/admin_list_employee';
//--- Admin UPDATING of EMPLOYEE
$route['sys/admin/update/employee/(:any)'] = 'AdminUsers_controller/list_update_employee/$1';
//--- Admin DELETING of EMPLOYEE
$route['sys/admin/delete/employee/(:any)'] = 'AdminUsers_controller/employee_delete/$1';


//--- Admin CREATION of DEPARTMENTS
$route['sys/admin/add/department'] = 'AdminDept_controller/admin_list_department';
//--- Admin UPDATING of DEPARTMENTS
$route['sys/admin/update/department/(:num)'] = 'AdminDept_controller/list_update_department/$1';
//--- Admin DELETING of DEPARTMENTS
$route['sys/admin/delete/department/(:any)'] = 'AdminDept_controller/department_delete/$1';

//
$route['Main/download_file/(:any)'] = 'Main/download_file/$1';

//
$route['sys/users/details/concern/customer_req_employee_req/update/(:any)'] = 'UsersTraccReq_controller/update_employee_request/$1';

$route['sys/users/details/concern/customer_req_supplier_req/update/(:any)'] = 'UsersTraccReq_controller/update_supplier_request/$1';

$route['sys/users/details/concern/customer_req_shipping_setup/update/(:any)'] = 'UsersTraccReq_controller/update_shipping_setup/$1';

//--- System LOGOUT
$route['sys/logout'] = 'main/logout';


$route['sys/users/details/concern/customer_req/(:any)'] = 'main/customer_request_form_details/$1';

$route['default_controller'] = 'main/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;