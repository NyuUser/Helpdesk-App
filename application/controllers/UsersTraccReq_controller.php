<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersTraccReq_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('UsersTraccReq_model');
	}

    //Generate TRF Number
	public function GenerateTRFNo() {
		$lastTRF = $this->Main_model->getLastTRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastTRF, -4);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newTRFNumber = 'TRF-' . sprintf('%04d', $newNumber);

        return $newTRFNumber;
	}

    // DATATABLE na makikita ni USERS sa get_tracc_request_form
	public function service_form_tracc_request_list(){
		$id = $this->session->userdata('login_data')['user_id'];
		$dept_id = $this->session->userdata('login_data')['dept_id'];

		$this->load->helper('form');
		$this->load->library('session');

		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');

		$user_details = $this->Main_model->user_details();
		$department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);
		$getdepartment = $this->Main_model->GetDepartmentID();

		if ($this->form_validation->run() == FALSE){
			$trfNumber = $this->GenerateTRFNo();

			$data['user_details'] = $user_details[1];
			$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];
			$data['dept_id'] = $dept_id;

			if($department_data[0] == "ok"){
				$data['departments'] = $department_data[1];
			} else {
				$data['departments'] = array();
				echo "No departments found.";
			}

			if(time() > $this->session->userdata('data')['expires_at']) {
				$this->session->unset_userdata('data');
			}

			$data['getdept'] = $getdepartment[1];

			$data['trfNumber'] = $trfNumber; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/users_TRF/tracc_request_form_list', $data); 
			$this->load->view('users/footer', $data); 
	
		} else {
			$process = $this->UsersTraccReq_model->trf_add_ticket();

			if ($process[0] == 1){
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}

		}
	}

    // USER CREATION of TICKET for TRACC REQUEST
	public function user_creation_tickets_tracc_request() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
	
		if ($this->form_validation->run() == FALSE) {
			$trf = $this->GenerateTRFNo();
	
			$data['trf'] = $trf;
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/tracc_request_form_creation', $data);
			$this->load->view('users/footer');
		} else {
			$file_path = null;
			if (!empty($_FILES['uploaded_files']['name'])) {
				$config['upload_path'] = FCPATH . 'uploads/tracc_request/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg';
				$config['max_size'] = 5048;
				$config['file_name'] = time() . '_' . $_FILES['uploaded_files']['name'];
	
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_files')) {
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url() . 'sys/users/create/tickets/tracc_request');
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name'];
				}
			}
	
			$checkbox_data_newadd = [
				'checkbox_item'                 => $this->input->post('checkbox_item') ? 1 : 0,
				'checkbox_customer'             => $this->input->post('checkbox_customer') ? 1 : 0,
				'checkbox_supplier'             => $this->input->post('checkbox_supplier') ? 1 : 0,
				'checkbox_whs'                  => $this->input->post('checkbox_whs') ? 1 : 0,
				'checkbox_bin'                  => $this->input->post('checkbox_bin') ? 1 : 0,
				'checkbox_cus_ship_setup'       => $this->input->post('checkbox_cus_ship_setup') ? 1 : 0,
				'checkbox_employee_req_form'    => $this->input->post('checkbox_employee_req_form') ? 1 : 0,
				'checkbox_others_newadd'        => $this->input->post('checkbox_others_newadd') ? 1 : 0, 
				'others_text_newadd'            => $this->input->post('others_text_newadd')
			];

			$checkbox_data_update = [
				'checkbox_system_date_lock'     => $this->input->post('checkbox_system_date_lock') ? 1 : 0,
				'checkbox_user_file_access'     => $this->input->post('checkbox_user_file_access') ? 1 : 0,
				'checkbox_item_dets'            => $this->input->post('checkbox_item_dets') ? 1 : 0,
				'checkbox_customer_dets'        => $this->input->post('checkbox_customer_dets') ? 1 : 0,
				'checkbox_supplier_dets'        => $this->input->post('checkbox_supplier_dets') ? 1 : 0,
				'checkbox_employee_dets'        => $this->input->post('checkbox_employee_dets') ? 1 : 0,
				'checkbox_others_update'        => $this->input->post('checkbox_others_update') ? 1 : 0,
				'others_text_update'            => $this->input->post('others_text_update')
			]; 

			$checkbox_data_account = [
				'checkbox_tracc_orien'          => $this->input->post('checkbox_tracc_orien') ? 1 : 0,
				'checkbox_create_lmi'           => $this->input->post('checkbox_create_lmi') ? 1 : 0,
				'checkbox_create_lpi'           => $this->input->post('checkbox_create_lpi') ? 1 : 0,
				'checkbox_create_rgdi'          => $this->input->post('checkbox_create_rgdi') ? 1 : 0,
				'checkbox_create_sv'            => $this->input->post('checkbox_create_sv') ? 1 : 0,
				'checkbox_gps_account'          => $this->input->post('checkbox_gps_account') ? 1 : 0,
				'checkbox_others_account'       => $this->input->post('checkbox_others_account') ? 1 : 0,
				'others_text_account'           => $this->input->post('others_text_account')
			];
	
			$comp_checkbox_values = isset($_POST['comp_checkbox_value']) ? $_POST['comp_checkbox_value'] : [];
			$imploded_values = implode(',', $comp_checkbox_values);
	
			$process = $this->UsersTraccReq_model->trf_add_ticket($file_path, $imploded_values, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account);

			$newadd = [
				'Item Request Form'             => $checkbox_data_newadd['checkbox_item'],
				'Customer Request Form'         => $checkbox_data_newadd['checkbox_customer'],
				'Supplier Request Form'         => $checkbox_data_newadd['checkbox_supplier'],
				'Customer Shipping Setup'       => $checkbox_data_newadd['checkbox_cus_ship_setup'],
				'Employee Request Form'         => $checkbox_data_newadd['checkbox_employee_req_form'],
			];
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				$this->session->set_userdata('data', ['checkbox_data' => $newadd, 'expires_at' => time() + (5 * 60)]);
				redirect(base_url() . 'sys/users/list/tickets/tracc_request');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url() . 'sys/users/create/tickets/tracc_request');
			}
		}
	}

    // Update TRACC REQUEST
    public function update_status_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$trf_number = $this->input->post('trf_number', true);
		$date_need = $this->input->post('date_need', true);
		$complete_details = $this->input->post('complete_details', true);
		$selected_companies = $this->input->post('comp_checkbox_value', true);

		$new_add_data = [
			'item' 							=> $this->input->post('checkbox_item', true) ? 1 : 0,
			'customer' 						=> $this->input->post('checkbox_customer', true) ? 1 : 0,
			'supplier' 						=> $this->input->post('checkbox_supplier', true) ? 1 : 0,
			'warehouse' 					=> $this->input->post('checkbox_whs', true) ? 1 : 0,
			'bin_number' 					=> $this->input->post('checkbox_bin', true) ? 1 : 0,
			'customer_shipping_setup' 		=> $this->input->post('checkbox_cus_ship_setup', true) ? 1 : 0,
			'employee_request_form' 		=> $this->input->post('checkbox_employee_req_form', true) ? 1 : 0,
			'others' 						=> $this->input->post('checkbox_others_newadd', true) ? 1 : 0,
			'others_description_add' 		=> $this->input->post('others_text_newadd', true),
		];

		$data_update = [
			'system_date_lock'				=> $this->input->post('checkbox_system_date_lock', true) ? 1 : 0,
			'user_file_access'				=> $this->input->post('checkbox_user_file_access', true) ? 1 : 0,
			'item_details'					=> $this->input->post('checkbox_item_dets', true) ? 1 : 0,
			'customer_details'				=> $this->input->post('checkbox_customer_dets', true) ? 1 : 0,
			'supplier_details'				=> $this->input->post('checkbox_supplier_dets', true) ? 1 : 0,
			'employee_details'				=> $this->input->post('checkbox_employee_dets', true) ? 1 : 0,
			'others'						=> $this->input->post('checkbox_others_update', true) ? 1 : 0,
			'others_description_update'		=> $this->input->post('others_text_update', true),
		];

		$data_account = [
			'tracc_orientation'				=> $this->input->post('checkbox_tracc_orien', true) ? 1 : 0,
			'lmi'							=> $this->input->post('checkbox_create_lmi', true) ? 1 : 0,
			'rgdi'							=> $this->input->post('checkbox_create_rgdi', true) ? 1 : 0,
			'lpi'							=> $this->input->post('checkbox_create_lpi', true) ? 1 : 0,
			'sv'							=> $this->input->post('checkbox_create_sv', true) ? 1 : 0,
			'gps_account'					=> $this->input->post('checkbox_gps_account', true) ? 1 : 0,
			'others'						=> $this->input->post('checkbox_others', true) ? 1 : 0,
			'others_description_acc'		=> $this->input->post('others_text_acc', true),
			
		];

		$process = $this->Main_model->UpdateTraccReq($trf_number, $date_need, $complete_details, $selected_companies);
		$process1 = $this->Main_model->UpdateTRNewAdd($trf_number, $new_add_data);
		$process2 = $this->Main_model->UpdateTRUpdate($trf_number, $data_update);
		$process3 = $this->Main_model->UpdateTRAccount($trf_number, $data_account);
		// print_r($process1);
		// die();

		if ($process[0] == 1 || $process1[0] == 1 || $process2[0] == 1 || $process3[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url()."sys/users/list/tickets/tracc_request");
		} else {
			$this->session->set_flashdata('error', $process[0]);
			redirect(base_url()."sys/users/list/tickets/tracc_request");
		}
	}

    //Tracc Request details USERS
	public function tracc_request_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTRF = $this->Main_model->getTicketsTRF($id);
			$getCheckboxDataNewAdd = $this->Main_model->getCheckboxDataNewAdd($id);
			$getCheckeboxDataUpdate = $this->Main_model->getCheckboxDataUpdate($id);
			$getCheckboxDataAccount = $this->Main_model->getCheckboxDataAccount($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['trf'] = $getTRF[1];
				$data['checkbox_data_newadd'] = $getCheckboxDataNewAdd;
				$data['checkbox_data_update'] = $getCheckeboxDataUpdate;
				$data['checkbox_data_account'] = $getCheckboxDataAccount;

				$selected_companies = isset($data['trf']['company']) ? explode(',', $data['trf']['company']) : [];
				$data['selected_companies'] = $selected_companies; 

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/tracc_request_form_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

    // USER CREATION FORM of CUSTOMER REQUEST FORM TMS (pdf ni mam hanna)
	public function user_creation_tickets_customer_request_forms_tms() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->UsersTraccReq_model->get_customer_from_tracc_req_mf_new_add();

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/trf_customer_request_form_creation', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

    // USER CREATION {FUNCTION} of CUSTOMER REQUEST FORM TMS (pdf ni mam hanna)
	public function user_creation_customer_request_form_pdf() {
		$crf_comp_checkbox_values = isset($_POST['crf_comp_checkbox_value']) ? $_POST['crf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $crf_comp_checkbox_values);

		$checkbox_cus_req_form_del = [
			'checkbox_outright'             => isset($_POST['checkbox_outright']) ? 1 : 0,
			'checkbox_consignment'          => isset($_POST['checkbox_consignment']) ? 1 : 0,
			'checkbox_cus_a_supplier'       => isset($_POST['checkbox_cus_a_supplier']) ? 1 : 0,
			'checkbox_online'               => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_walkIn'               => isset($_POST['checkbox_walkIn']) ? 1 : 0,
			'checkbox_monday'               => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday'              => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday'            => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday'             => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday'               => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday'             => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday'               => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];
	
		$process = $this->UsersTraccReq_model->add_customer_request_form_pdf($imploded_values, $checkbox_cus_req_form_del);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms'); 
		}
	}

    // USER CREATION FORM of CUSTOMER SHIPPING SETUP (pdf ni mam hanna)
	public function user_creation_tickets_customer_shipping_setup() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->UsersTraccReq_model->get_customer_shipping_setup_from_tracc_req_mf_new_add();
	
		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/trf_customer_shipping_setup', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

    // USER CREATION {FUCNTION} of CUSTOMER SHIPPING SETUP (pdf ni mam hanna)
	public function user_creation_customer_shipping_setup_pdf() {
		$css_comp_checkbox_value = isset($_POST['css_comp_checkbox_value']) ? $_POST['css_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $css_comp_checkbox_value);

		$checkbox_cus_ship_setup = [
			'checkbox_monday'           => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday'          => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday'        => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday'         => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday'           => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday'         => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday'           => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];

		$process = $this->UsersTraccReq_model->add_customer_shipping_setup_pdf($imploded_values, $checkbox_cus_ship_setup);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_shipping_setup');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_shipping_setup');  
		}
	}

    // USER CREATION FORM of EMPLOYEE REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_employee_request_form() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->UsersTraccReq_model->get_employee_request_form_from_tracc_req_mf_new_add();
	
		// New: Fetch all departments for the dropdown
		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];
	
		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			// New: Pass departments for the dropdown
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/trf_employee_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

    // USER CREATION {FUCNTION} of EMPLOYEE REQUEST FORM (pdf ni mam hanna)
	public function user_creation_employee_request_form_pdf() {
		$process = $this->UsersTraccReq_model->add_employee_request_form_pdf();

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_employee_request_form');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_employee_request_form');  
		}
	}

    // USER CREATION FORM of ITEM REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_item_request_form() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->UsersTraccReq_model->get_item_request_form_from_tracc_req_mf_new_add();

		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			// New: Pass departments for the dropdown
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/trf_item_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

    // USER CREATION {FUCNTION} of ITEM REQUEST FORM (pdf ni mam hanna)
	public function user_creation_item_request_form_pdf() {
		$trf_number = $this->input->post('trf_number', true);
		$irf_comp_checkbox_value = isset($_POST['irf_comp_checkbox_value']) ? $_POST['irf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $irf_comp_checkbox_value);

		$checkbox_item_req_form = [
			'checkbox_inventory'            => isset($_POST['checkbox_inventory']) ? 1 : 0,
			'checkbox_non_inventory'        => isset($_POST['checkbox_non_inventory']) ? 1 : 0,
			'checkbox_services'             => isset($_POST['checkbox_services']) ? 1 : 0,
			'checkbox_charges'              => isset($_POST['checkbox_charges']) ? 1 : 0,
			'checkbox_watsons'              => isset($_POST['checkbox_watsons']) ? 1 : 0,
			'checkbox_other_accounts'       => isset($_POST['checkbox_other_accounts']) ? 1 : 0,
			'checkbox_online'               => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_all_accounts'         => isset($_POST['checkbox_all_accounts']) ? 1 : 0,
			'checkbox_trade'                => isset($_POST['checkbox_trade']) ? 1 : 0,
			'checkbox_non_trade'            => isset($_POST['checkbox_non_trade']) ? 1 : 0,
			'checkbox_batch_required_yes'   => isset($_POST['checkbox_batch_required_yes']) ? 1 : 0,
			'checkbox_batch_required_no'    => isset($_POST['checkbox_batch_required_no']) ? 1 : 0,
 
		];

		$process = $this->UsersTraccReq_model->add_item_request_form_pdf($imploded_values, $checkbox_item_req_form);

		$rows_data = $this->input->post('rows_gl', true);

		if ($process[0] == 1 && !empty($rows_data)) {
			// Prepare structured data for rows insertion
			$insert_data_gl_setup = [];
			foreach ($rows_data as $row) {
				if (!empty($row['uom']) && !empty($row['barcode'])) { // Basic validation
					$insert_data_gl_setup[] = [
						'ticket_id'         => $trf_number,
						'uom'               => $row['uom'],
						'barcode'           => $row['barcode'],
						'length'            => $row['length'],
						'height'            => $row['height'],
						'width'             => $row['width'],
						'weight'            => $row['weight'],
					];
				}
			}

			if (!empty($insert_data_gl_setup)) {
				$this->UsersTraccReq_model->insert_batch_rows_gl_setup($insert_data_gl_setup);
			}
		}

		$rows_data = $this->input->post('rows_whs',true);

		if ($process[0] == 1 && !empty($rows_data)) {
			$insert_data_whs_setup = [];
			foreach ($rows_data as $row){
				if(!empty($row['warehouse']) && !empty($row['warehouse_no'])) {
					$insert_data_wh_setup[] = [
						'ticket_id'         => $trf_number,
						'warehouse'         => $row['warehouse'],
						'warehouse_no'      => $row['warehouse_no'],
						'storage_location'  => $row['storage_location'],
						'storage_type'      => $row['storage_type'],
						'fixed_bin'         => $row['fixed_bin'],
						'min_qty'           => $row['min_qty'],
						'max_qty'           => $row['max_qty'],
						'replen_qty'        => $row['replen_qty'],
						'control_qty'       => $row['control_qty'],
						'round_qty'         => $row['round_qty'],
						'uom'               => $row['uom'],
					];
				}
			}

			if (!empty($insert_data_wh_setup)) {
				$this->UsersTraccReq_model->insert_batch_rows_whs_setup($insert_data_wh_setup);
			}
		}


		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_item_request_form');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_item_request_form');  
		}
	}

    // USER CREATION FORM of SUPPLIER REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_supplier_request_form_tms() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->UsersTraccReq_model->get_supplier_from_tracc_req_mf_new_add();

		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/users_TRF/trf_supplier_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

    // USER CREATION {FUCNTION} of SUPPLIER REQUEST FORM (pdf ni mam hanna)
	public function user_creation_supplier_request_form_pdf() {
		$trf_comp_checkbox_value = isset($_POST['trf_comp_checkbox_value']) ? $_POST['trf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $trf_comp_checkbox_value);

		$checkbox_non_vat = isset($_POST['checkbox_non_vat']) ? 1 : 0;

		$checkbox_supplier_req_form = [
			'local_supplier_grp'                => isset($_POST['local_supplier_grp']) ? 1 : 0,
			'foreign_supplier_grp'              => isset($_POST['foreign_supplier_grp']) ? 1 : 0,
			'supplier_trade'                    => isset($_POST['supplier_trade']) ? 1 : 0,
			'supplier_non_trade'                => isset($_POST['supplier_non_trade']) ? 1 : 0,
			'trade_type_goods'                  => isset($_POST['trade_type_goods']) ? 1 : 0,
			'trade_type_services'               => isset($_POST['trade_type_services']) ? 1 : 0,
			'trade_type_GoodsServices'          => isset($_POST['trade_type_GoodsServices']) ? 1 : 0,
			'major_grp_local_trade_ven'         => isset($_POST['major_grp_local_trade_ven']) ? 1 : 0,
			'major_grp_local_nontrade_ven'      => isset($_POST['major_grp_local_nontrade_ven']) ? 1 : 0,
			'major_grp_foreign_trade_ven'       => isset($_POST['major_grp_foreign_trade_ven']) ? 1 : 0,
			'major_grp_foreign_nontrade_ven'    => isset($_POST['major_grp_foreign_nontrade_ven']) ? 1 : 0,
			'major_grp_local_broker_forwarder'  => isset($_POST['major_grp_local_broker_forwarder']) ? 1 : 0,
			'major_grp_rental'                  => isset($_POST['major_grp_rental']) ? 1 : 0,
			'major_grp_bank'                    => isset($_POST['major_grp_bank']) ? 1 : 0,
			'major_grp_one_time_supplier'       => isset($_POST['major_grp_one_time_supplier']) ? 1 : 0,
			'major_grp_government_offices'      => isset($_POST['major_grp_government_offices']) ? 1 : 0,
			'major_grp_insurance'               => isset($_POST['major_grp_insurance']) ? 1 : 0,
			'major_grp_employees'               => isset($_POST['major_grp_employees']) ? 1 : 0,
			'major_grp_subs_affiliates'         => isset($_POST['major_grp_subs_affiliates']) ? 1 : 0,
			'major_grp_utilities'               => isset($_POST['major_grp_utilities']) ? 1 : 0,
		];
	
		$process = $this->UsersTraccReq_model->add_supplier_request_form_pdf($imploded_values, $checkbox_non_vat, $checkbox_supplier_req_form);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_supplier_request_form_tms');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_supplier_request_form_tms');  
		}
	}

	// Kevin: Render customer request form details
	public function customer_request_form_rf_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_rf_details($id);
			$ticket_numbers = $this->UsersTraccReq_model->get_customer_from_tracc_req_details();
			$form_del_days = $this->Main_model->get_ticket_checkbox_customer_req($id);
			// print_r($ticket_numbers);
			// die();
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['ticket_numbers'] = $ticket_numbers[0];
				// $data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['del_days'] = $form_del_days;

				$selected_companies = isset($data['reqForm']['company']) ? explode(',', $data['reqForm']['company']) : [];
				$data['selected_companies'] = $selected_companies;
				// print_r($selected_companies);
				// die();

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/trf_customer_request_form_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// Kevin: Render shipping setup details
	public function customer_request_form_ss_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_ss_details($id);
			$ticket_numbers = $this->UsersTraccReq_model->get_customer_shipping_setup_from_tracc_req_mf_new_add();
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['ticket_numbers'] = $ticket_numbers[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/trf_customer_shipping_setup_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function update_shipping_setup($id) {
		if($this->session->userdata('login_data')) {
			$css_comp_checkbox_value = isset($_POST['css_comp_checkbox_value']) ? $_POST['css_comp_checkbox_value'] : [];
			$imploded_values = implode(',', $css_comp_checkbox_value);

			$checkbox_cus_ship_setup = [
				'checkbox_monday'           => isset($_POST['checkbox_monday']) ? 1 : 0,
				'checkbox_tuesday'          => isset($_POST['checkbox_tuesday']) ? 1 : 0,
				'checkbox_wednesday'        => isset($_POST['checkbox_wednesday']) ? 1 : 0,
				'checkbox_thursday'         => isset($_POST['checkbox_thursday']) ? 1 : 0,
				'checkbox_friday'           => isset($_POST['checkbox_friday']) ? 1 : 0,
				'checkbox_saturday'         => isset($_POST['checkbox_saturday']) ? 1 : 0,
				'checkbox_sunday'           => isset($_POST['checkbox_sunday']) ? 1 : 0,
			];

			$process = $this->UsersTraccReq_model->update_ss($id, $imploded_values, $checkbox_cus_ship_setup);

			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/details/concern/customer_req_ship_setup/' . $id);  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/details/concern/customer_req_ship_setup/' . $id);  
			} 
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// Kevin: Render item request details
	public function customer_request_form_ir_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_ir_details($id);
			$checkboxes = $this->Main_model->get_ticket_checkbox1_item_req_form($id);
			$checkboxes2 = $this->Main_model->get_ticket_checkbox2_item_req_form($customerReqForm[0]['ticket_id']);
			$checkboxes3 = $this->Main_model->get_ticket_checkbox3_item_req_form($customerReqForm[0]['ticket_id']);
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['checkboxes'] = $checkboxes;
				$data['checkboxes2'] = $checkboxes2;
				$data['checkboxes3'] = $checkboxes3;	

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/trf_item_request_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// Kevin: Render employee request details
	public function customer_request_form_er_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_er_details($id);
			$departments_result = $this->Main_model->getDepartment();
			$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['departments'] = $departments;

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/trf_employee_request_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function update_employee_request($id) {	
		if($this->session->userdata('login_data')) {
			$process = $this->UsersTraccReq_model->update_er($id);
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_er_details($id);
			$departments_result = $this->Main_model->getDepartment();
			$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['departments'] = $departments;
				
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect("sys/users/details/concern/customer_req_employee_req/" . $id);
				} else {
					$this->session->set_flashdata('error', $process[1]);
					redirect("sys/users/details/concern/customer_req_employee_req/" . $id);
				}

			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// Kevin: Render supplier request details
	public function customer_request_form_sr_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->UsersTraccReq_model->get_customer_req_form_sr_details($id);
			$checkboxes = $this->Main_model->get_ticket_checkbox_supplier_req_by_ticket_id($customerReqForm[0]['ticket_id']);
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['checkboxes'] = $checkboxes[0];

				$this->load->view('users/header', $data);
				$this->load->view('users/users_TRF/trf_supplier_request_form_details', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function update_supplier_request($id) {
		if ($this->session->userdata('login_data')) {
			$trf_comp_checkbox_value = isset($_POST['trf_comp_checkbox_value']) ? $_POST['trf_comp_checkbox_value'] : [];
			$imploded_values = implode(',', $trf_comp_checkbox_value);

			$checkbox_non_vat = isset($_POST['checkbox_non_vat']) ? 1 : 0;

			$checkbox_supplier_req_form = [
				'local_supplier_grp'                => isset($_POST['local_supplier_grp']) ? 1 : 0,
				'foreign_supplier_grp'              => isset($_POST['foreign_supplier_grp']) ? 1 : 0,
				'supplier_trade'                    => isset($_POST['supplier_trade']) ? 1 : 0,
				'supplier_non_trade'                => isset($_POST['supplier_non_trade']) ? 1 : 0,
				'trade_type_goods'                  => isset($_POST['trade_type_goods']) ? 1 : 0,
				'trade_type_services'               => isset($_POST['trade_type_services']) ? 1 : 0,
				'trade_type_GoodsServices'          => isset($_POST['trade_type_GoodsServices']) ? 1 : 0,
				'major_grp_local_trade_ven'         => isset($_POST['major_grp_local_trade_ven']) ? 1 : 0,
				'major_grp_local_nontrade_ven'      => isset($_POST['major_grp_local_nontrade_ven']) ? 1 : 0,
				'major_grp_foreign_trade_ven'       => isset($_POST['major_grp_foreign_trade_ven']) ? 1 : 0,
				'major_grp_foreign_nontrade_ven'    => isset($_POST['major_grp_foreign_nontrade_ven']) ? 1 : 0,
				'major_grp_local_broker_forwarder'  => isset($_POST['major_grp_local_broker_forwarder']) ? 1 : 0,
				'major_grp_rental'                  => isset($_POST['major_grp_rental']) ? 1 : 0,
				'major_grp_bank'                    => isset($_POST['major_grp_bank']) ? 1 : 0,
				'major_grp_one_time_supplier'       => isset($_POST['major_grp_one_time_supplier']) ? 1 : 0,
				'major_grp_government_offices'      => isset($_POST['major_grp_government_offices']) ? 1 : 0,
				'major_grp_insurance'               => isset($_POST['major_grp_insurance']) ? 1 : 0,
				'major_grp_employees'               => isset($_POST['major_grp_employees']) ? 1 : 0,
				'major_grp_subs_affiliates'         => isset($_POST['major_grp_subs_affiliates']) ? 1 : 0,
				'major_grp_utilities'               => isset($_POST['major_grp_utilities']) ? 1 : 0,
			];

			$process = $this->UsersTraccReq_model->update_sr($id, $imploded_values, $checkbox_non_vat, $checkbox_supplier_req_form);

			if($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/details/concern/customer_req_supplier_req/' . $id);
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/details/concern/customer_req_supplier_req/' . $id);
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
<<<<<<< HEAD
=======

	// Update Customer Request Form (Users)
	// public function user_edit_customer_request_form_pdf($id) {
	// 	$trf_number = $this->input->post('trf_number', true);
	// 	$selected_companies = $this->input->post('trf_comp_checkbox_value', true);
	// 	$date = $this->input->post('date', true);
	// 	$customer_code = $this->input->post('customer_code', true);
	// 	$tin_no = $this->input->post('tin_no', true);
	// 	$customer_name = $this->input->post('customer_name', true);
	// 	$terms = $this->input->post('terms', true);
	// 	$customer_address = $this->input->post('customer_address', true);
	// 	$contact_person = $this->input->post('contact_person', true);
	// 	$pricelist 	= $this->input->post('pricelist', true);
	// 	$office_tel_no = $this->input->post('office_tel_no', true);
	// 	$payment_group = $this->input->post('payment_grp', true);
	// 	$contact_no = $this->input->post('contact_no', true);
	// 	$territory = $this->input->post('territory', true);
	// 	$salesman = $this->input->post('salesman', true);
	// 	$business_style = $this->input->post('business_style', true);
	// 	$email = $this->input->post('email', true);
	// 	$shipping_code = $this->input->post('shipping_code', true);
	// 	$route_code = $this->input->post('route_code', true);
	// 	$customer_shipping_address = $this->input->post('customer_shipping_address', true);
	// 	$landmark = $this->input->post('landmark', true);
	// 	$window_time_start = $this->input->post('window_time_start', true);
	// 	$window_time_end = $this->input->post('window_time_end', true);
	// 	$special_instruction = $this->input->post('special_instruction', true);

	// 	$del_days = [
	// 		'outright'   						=> $this->input->post('checkbox_outright', true) ? 1 : 0,
	// 		'consignment'						=> $this->input->post('checkbox_consignment', true) ? 1 : 0,
	// 		'customer_is_also_a_supplier'		=> $this->input->post('checkbox_cus_a_supplier', true) ? 1 : 0,
	// 		'online'							=> $this->input->post('checkbox_online', true) ? 1 : 0,
	// 		'walk_in'							=> $this->input->post('checkbox_walkIn', true) ? 1 : 0,
	// 		'monday'							=> $this->input->post('checkbox_monday', true) ? 1 : 0,
	// 		'tuesday'							=> $this->input->post('checkbox_tuesday', true) ? 1 : 0,
	// 		'wednesday'							=> $this->input->post('checkbox_wednesday', true) ? 1 : 0,
	// 		'thursday'							=> $this->input->post('checkbox_thursday', true) ? 1 : 0,
	// 		'friday'							=> $this->input->post('checkbox_friday', true) ? 1 : 0,
	// 		'saturday'							=> $this->input->post('checkbox_saturday', true) ? 1 : 0,
	// 		'sunday'							=> $this->input->post('checkbox_sunday', true) ? 1 : 0,
	// 	];

	// 	$process = $this->UsersTraccReq_model->edit_customer_request_form_pdf($id, $selected_companies, $date, $customer_code, $tin_no, $customer_name, $terms, $customer_address, $contact_person, $pricelist, $office_tel_no, $payment_group, $contact_no, $territory, $salesman, $business_style, $email, $shipping_code, $route_code, $customer_shipping_address, $landmark, $window_time_start, $window_time_end, $special_instruction);
	// 	// print_r($selected_companies);
	// 	// die();
	// 	$process1 = $this->UsersTraccReq_model->edit_customer_request_form_pdf_del_days($id, $del_days);

	// 	if ($process[0] == 1 || $process1[0] == 1) {
	// 		$this->session->set_flashdata('success', $process[1]);
	// 		redirect(base_url()."sys/users/dashboard");
	// 	} else {
	// 		$this->session->set_flashdata('error', $process[0]);
	// 		redirect(base_url()."sys/users/dashboard");
	// 	}
	// }

	public function user_edit_customer_request_form_pdf($id) {
		// Fetch and sanitize input
		$trf_number = $this->input->post('trf_number', true);
		$customer_code = $this->input->post('customer_code', true);
		$selected_companies = $this->input->post('trf_comp_checkbox_value', true);
	
		// Validate essential fields
		if (empty($trf_number) || empty($customer_code)) {
			$this->session->set_flashdata('error', 'Transaction number and customer code are required.');
			redirect(base_url() . "sys/users/dashboard");
			return;
		}
	
		// Gather data for updating the main customer request form
		$main_data = [
			'trf_number' => $trf_number,
			'date' => $this->input->post('date', true),
			'customer_code' => $customer_code,
			'tin_no' => $this->input->post('tin_no', true),
			'customer_name' => $this->input->post('customer_name', true),
			'terms' => $this->input->post('terms', true),
			'customer_address' => $this->input->post('customer_address', true),
			'contact_person' => $this->input->post('contact_person', true),
			'pricelist' => $this->input->post('pricelist', true),
			'office_tel_no' => $this->input->post('office_tel_no', true),
			'payment_group' => $this->input->post('payment_grp', true),
			'contact_no' => $this->input->post('contact_no', true),
			'territory' => $this->input->post('territory', true),
			'salesman' => $this->input->post('salesman', true),
			'business_style' => $this->input->post('business_style', true),
			'email' => $this->input->post('email', true),
			'shipping_code' => $this->input->post('shipping_code', true),
			'route_code' => $this->input->post('route_code', true),
			'customer_shipping_address' => $this->input->post('customer_shipping_address', true),
			'landmark' => $this->input->post('landmark', true),
			'window_time_start' => $this->input->post('window_time_start', true),
			'window_time_end' => $this->input->post('window_time_end', true),
			'special_instruction' => $this->input->post('special_instruction', true),
		];
	
		// Gather data for updating the delivery days
		$del_days = [
			'outright'   						=> $this->input->post('checkbox_outright', true) ? 1 : 0,
			'consignment'						=> $this->input->post('checkbox_consignment', true) ? 1 : 0,
			'customer_is_also_a_supplier'		=> $this->input->post('checkbox_cus_a_supplier', true) ? 1 : 0,
			'online'							=> $this->input->post('checkbox_online', true) ? 1 : 0,
			'walk_in'							=> $this->input->post('checkbox_walkIn', true) ? 1 : 0,
			'monday'							=> $this->input->post('checkbox_monday', true) ? 1 : 0,
			'tuesday'							=> $this->input->post('checkbox_tuesday', true) ? 1 : 0,
			'wednesday'							=> $this->input->post('checkbox_wednesday', true) ? 1 : 0,
			'thursday'							=> $this->input->post('checkbox_thursday', true) ? 1 : 0,
			'friday'							=> $this->input->post('checkbox_friday', true) ? 1 : 0,
			'saturday'							=> $this->input->post('checkbox_saturday', true) ? 1 : 0,
			'sunday'							=> $this->input->post('checkbox_sunday', true) ? 1 : 0,
		];
	
		// Call model functions
		$process = $this->UsersTraccReq_model->edit_customer_request_form_pdf(
			$id, 
			$selected_companies, 
			$main_data['date'],
			$main_data['customer_code'],
			$main_data['tin_no'],
			$main_data['customer_name'],
			$main_data['terms'],
			$main_data['customer_address'],
			$main_data['contact_person'],
			$main_data['pricelist'],
			$main_data['office_tel_no'],
			$main_data['payment_group'],
			$main_data['contact_no'],
			$main_data['territory'],
			$main_data['salesman'],
			$main_data['business_style'],
			$main_data['email'],
			$main_data['shipping_code'],
			$main_data['route_code'],
			$main_data['customer_shipping_address'],
			$main_data['landmark'],
			$main_data['window_time_start'],
			$main_data['window_time_end'],
			$main_data['special_instruction'],
			$del_days
		);
	
		// Handle success or error
		if ($process[0] == 1) {
			$this->session->set_flashdata('success', 'Ticket successfully updated.');
		} else {
			$this->session->set_flashdata('error', 'Error updating the ticket. Please try again.');
		}
	
		// Redirect to dashboard
		redirect(base_url() . "sys/users/dashboard");
	}
	

	
>>>>>>> 47d7d97310597e2e54103eec85af197e44068544
}
?>