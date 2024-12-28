<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccReq_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminTraccReq_model');
    }

    //TRACC REQUEST List of Ticket for ADMIN
	public function admin_list_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$data['active_menu'] = $active_menu;

				if ($this->input->post()) {
					$trf_number = $this->input->post('trf_number');
					$approval_stat = $this->input->post('app_stat');

					$process = $this->AdminTraccReq_model->status_approval_trf($trf_number, $app_stat);
					
					if (isset($process[0]) && $process[0] == 1) {
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}

					redirect(base_url()."sys/admin/list/ticket/tracc_request");
				}
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/tickets_tracc_request', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function admin_closed_tickets() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'closed_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'closed_tickets_list';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/closed_tracc_req', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// ADMIN FORM for Customer Request form (PDF ni mam hanna)
	public function customer_request_form_pdf_view($active_menu = 'customer_request_form_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			
			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['customer_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_customer_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Customer Request Form
	public function cus_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data = $this->AdminTraccReq_model->get_ticket_checkbox_customer_req($ticket['recid']); 

				$formData = [
					'recid' 						=> $ticket['recid'],
					'ticket_id'						=> $ticket['ticket_id'],
					'requested_by' 					=> $ticket['requested_by'],
					'companies' 					=> $companies,
					'date' 							=> $ticket['date'],
					'customer_code' 				=> $ticket['customer_code'],
					'customer_name' 				=> $ticket['customer_name'],
					'tin_no' 						=> $ticket['tin_no'],
					'terms' 						=> $ticket['terms'],
					'customer_address' 				=> $ticket['customer_address'],
					'contact_person' 				=> $ticket['contact_person'],
					'office_tel_no' 				=> $ticket['office_tel_no'],
					'pricelist' 					=> $ticket['pricelist'],
					'payment_group' 				=> $ticket['payment_group'],
					'contact_no' 					=> $ticket['contact_no'],
					'territory'			 			=> $ticket['territory'],
					'salesman' 						=> $ticket['salesman'], 
					'business_style' 				=> $ticket['business_style'], 
					'email' 						=> $ticket['email'],
					'shipping_code' 				=> $ticket['shipping_code'],
					'route_code' 					=> $ticket['route_code'],
					'customer_shipping_address' 	=> $ticket['customer_shipping_address'],
					'landmark' 						=> $ticket['landmark'],
					'window_time_start' 			=> $ticket['window_time_start'],
					'window_time_end' 				=> $ticket['window_time_end'],
					'special_instruction' 			=> $ticket['special_instruction'],
					'created_at' 					=> $ticket['created_at'],
					'approved_by' 					=> $ticket['approved_by'],
					'approved_date' 				=> $ticket['approved_date'],
					'checkbox_data'		 			=> $checkbox_data,
				];

				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_customer_request_form_admin', $formData, TRUE);			
				$data[] = [
					'tab_id' 						=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'count' 						=> $ticket['count'],
					'recid'							=> $ticket['recid'],
					'form_html'	 					=> $formHtml,
				];  	
			}
			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// Update CRF Ticket Remarks
	public function update_crf_ticket_remarks() {
		$recid = $this->input->post('recid'); 
		$result = $this->AdminTraccReq_model->update_crf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	// ADMIN FORM for Customer Shipping Setup form (PDF ni mam hanna)
	public function customer_shipping_setup_pdf_view($active_menu = 'customer_shipping_setup_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			
			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['customer_shipping_setup_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_customer_shipping_setup_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Customer Shipping Setup
	public function cus_ship_setup_JTtabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_ship_setup();
		// print_r($tickets); die();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$formData = [
					'recid' 					=> $ticket['recid'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'requested_by'	 			=> $ticket['requested_by'],
					'companies' 				=> $companies,
					'shipping_code' 			=> $ticket['shipping_code'],
					'route_code' 				=> $ticket['route_code'],
					'customer_address' 			=> $ticket['customer_address'],
					'landmark' 					=> $ticket['landmark'],
					'window_time_start' 		=> $ticket['window_time_start'],
					'window_time_end' 			=> $ticket['window_time_end'],
					'special_instruction' 		=> $ticket['special_instruction'],
					'monday' 					=> $ticket['monday'],
					'tuesday' 					=> $ticket['tuesday'],
					'wednesday' 				=> $ticket['wednesday'],
					'thursday' 					=> $ticket['thursday'],
					'friday' 					=> $ticket['friday'],
					'saturday' 					=> $ticket['saturday'],
					'sunday'					=> $ticket['sunday'],
					'created_at' 				=> $ticket['created_at'],
					'approved_by' 				=> $ticket['approved_by'],
					'approved_date' 			=> $ticket['approved_date'],
				];

				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_customer_shipping_setup_form_admin', $formData, TRUE);			
				$data[] = [
					'tab_id' 					=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'count' 					=> $ticket['count'],
					'recid' 					=> $ticket['recid'],
					'form_html' 				=> $formHtml,
				];  	
			}
			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// Update CSS Ticket Remarks
	public function update_css_ticket_remarks() {
		$recid = $this->input->post('recid'); 
		$result = $this->AdminTraccReq_model->update_css_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	// ADMIN FORM for Employee Request form (PDF ni mam hanna)
	public function employee_request_form_pdf_view($active_menu = 'employee_request_form_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['employee_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_employee_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Employee Request Form
	public function emp_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_employee_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {
				
				$formData = [
					'recid' 					=> $ticket['recid'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'requested_by' 				=> $ticket['requested_by'],
					'name' 						=> $ticket['name'],
					'department' 				=> $ticket['department'],
					'department_desc' 			=> $ticket['department_desc'],
					'position' 					=> $ticket['position'],
					'address' 					=> $ticket['address'],
					'tel_no_mob_no' 			=> $ticket['tel_no_mob_no'],
					'tin_no' 					=> $ticket['tin_no'],
					'contact_person' 			=> $ticket['contact_person'],
					'created_at' 				=> $ticket['created_at'],
					'approved_by' 				=> $ticket['approved_by'],
					'approved_date' 			=> $ticket['approved_date'],
				];
				
				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_employee_request_form_admin', $formData, TRUE);
				// print_r($formData);
				// die();
			

				$data[] = [
					'tab_id' 					=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'count' 					=> $ticket['count'],
					'recid' 					=> $ticket['recid'],
					'form_html' 				=> $formHtml,
				];
			}

			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	public function update_erf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$result = $this->AdminTraccReq_model->update_erf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}
}
?>