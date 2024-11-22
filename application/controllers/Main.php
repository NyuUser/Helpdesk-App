<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
		$this->load->model('Main_model');
	}
	// code ni sir gilbert
	/*public function login() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[12]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]|max_length[150]');
		
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('login');
		} else {
			$process = $this->Main_model->login();
	
			if ($process[0] == 1 && $process[1]['status'] == 1) {
				$role = $process[1]['role'];
				$this->session->set_userdata(array('login_data' => $process[1]));
				
				if ($role == "L2") {
					$this->session->set_flashdata('success', 'Login successful!');
					redirect(base_url().'sys/admin/dashboard');
				} else {
					$this->session->set_flashdata('success', 'Login successful!');
					redirect(base_url().'sys/users/dashboard');
				}
			} else {
				$this->session->set_flashdata('error', $process['message']);
				redirect("sys/authentication");
			}
		}
	}*/
	
	// LOGIN 
	public function login() {
		// Load helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		
		// Check if the request is a POST request
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			// Set validation rules
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
			if ($this->form_validation->run() == FALSE) {
				// Return validation errors in JSON format if validation fails
				$response = array(
					'status' => 'error',
					'message' => 'Validation failed',
					'errors' => $this->form_validation->error_array()
				);
				echo json_encode($response);
				exit; // Ensure no further code execution
			} else {
				// Process login
				$process = $this->Main_model->login();
	
				if ($process[0] == 1 && $process[1]['status'] == 1) {
					// Successful login
					$role = $process[1]['role'];
					$this->session->set_userdata(array('login_data' => $process[1]));
	
					// Set redirect URL based on role
					//$redirect_url = ($role == "L2") ? base_url().'sys/admin/dashboard' : base_url().'sys/users/dashboard';
					if ($role == "L2"){
						$redirect_url = base_url().'sys/admin/dashboard';
					} else if ($role == "L3"){
						$redirect_url = base_url().'sys/admin/dashboard';
					} else {
						$redirect_url = base_url().'sys/users/dashboard';
					}
					// Return success response with redirect URL
					$response = array(
						'status' => 'success',
						'message' => 'Login successful',
						'redirect_url' => $redirect_url
					);
					echo json_encode($response);
				} else {
					// Login failed, return error message
					$response = array(
						'status' => 'error',
						'message' => isset($process['message']) ? $process['message'] : 'Invalid Login Credentials'
					);
					echo json_encode($response);
				}
				exit; // Ensure no further code execution
			}
		} else {
			// If not a POST request, load the login view (initial page load)
			$this->load->view('login');
		}
	}

	
	/*public function login() {
		// Load the necessary helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
	
		// Set form validation rules
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
		if ($this->form_validation->run() == FALSE) {
			// Return validation errors in JSON format
			$response = array(
				'status' => 'error',
				'message' => 'Validation failed',
				'errors' => $this->form_validation->error_array()
			);
			echo json_encode($response);
		} else {
			// Process login
			$process = $this->Main_model->login();
	
			if ($process[0] == 1 && $process[1]['status'] == 1) {
				// Successful login
				$role = $process[1]['role'];
				$this->session->set_userdata(array('login_data' => $process[1]));
	
				// Set redirect URL based on role
				$redirect_url = ($role == "L2") ? base_url().'sys/admin/dashboard' : base_url().'sys/users/dashboard';
	
				// Return success response with redirect URL
				$response = array(
					'status' => 'success',
					'message' => 'Login successful',
					'redirect_url' => $redirect_url
				);
				echo json_encode($response);
			} else {
				// Login failed, return error message
				$response = array(
					'status' => 'error',
					'message' => isset($process['message']) ? $process['message'] : 'Invalid login credentials'
				);
				echo json_encode($response);
			}
		}
	}*/
	
	/*public function registration() {
		$this->load->helper('form');
		$this->load->library('session');
	
		// Fetch departments data
		$page_data['get_departments'] = $this->Main_model->get_departments();
		//print_r($page_data['get_departments']);die;

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$process = $this->Main_model->user_registration();
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/registration');
			} else {
				$this->session->set_flashdata('error', $process[1]);
			}
	
			redirect('registration');
		}
	
		$this->load->view('registration', $page_data);
	}*/

	//Registration 
	public function registration() {
		$this->load->helper('form');
		$this->load->library('session');
	
		// Fetch departments data
		$page_data['get_departments'] = $this->Main_model->get_departments();
	
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$process = $this->Main_model->user_registration();
	
			if ($process[0] == 1) {
				// Successful registration
				$response = array(
					'status' => 'success',
					'message' => $process[1]
				);
			} else {
				// Registration failed
				$response = array(
					'status' => 'error',
					'message' => $process[1]
				);
			}
	
			// Return JSON response
			echo json_encode($response);
			exit; // Prevent loading the view after response
		}
	
		// If not POST request, load the registration view
		$this->load->view('registration', $page_data);
	}
	
	
	// Admin Dashboard, Viewing of Admin Dashboard
	public function admin_dashboard($active_menu = 'dashboard') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				// Fetch total users count
				$data['total_users'] = $this->Main_model->get_total_users();
				// Fetch total msrf ticket count
				$data['total_msrf_tickets'] = $this->Main_model->get_total_msrf_ticket();
				// Fetch total departments count
				$data['total_departments'] = $this->Main_model->get_total_departments();
				// Fetch total tracc concern count
				$data['total_tracc_concern_tickets'] = $this->Main_model->get_total_tracc_concern_ticket();
				// Fetch total tracc request count
				// $data['total_tracc_request_tickets'] = $this->Main_model->get_total_tracc_request_ticket();

				$allowed_menus = ['dashboard', 'system_administration', 'other_menu'];
				if (!in_array($active_menu, $allowed_menus)) {
		        	$active_menu = 'dashboard';
		    	}

				$data['active_menu'] = $active_menu;

				$this->check_upload_size();
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/dashboard', $data);
				$this->load->view('admin/footer');

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

	//Viewing of users/employees for Admin Datatable
	public function admin_users() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'team'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/users', $data);
				$this->load->view('admin/footer');
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

	//Viewing of Department for Admin Datatable
	public function admin_team() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'team'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_team', $data);
				$this->load->view('admin/footer');
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

	//NOT WORKING, locking of users
	public function lock_users() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['data'] = array('user_details' => $user_details[1]);

				$process = $this->Main_model->updated_lock();
				
				if ($process[0] == 1) {
                    redirect(base_url()."sys/admin/users");
                } else {
                    redirect(base_url()."sys/admin/users");
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

	//NOT WORKING, unlocking of users
	public function unlock_users() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['data'] = array('user_details' => $user_details[1]);

				$process = $this->Main_model->updated_unlock();

				if ($process[0] == 1) {
                    redirect(base_url()."sys/admin/users");
                } else {
                    redirect(base_url()."sys/admin/users");
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

	//TRACC CONCERN List of Ticket for Admin
	public function admin_list_tracc_concern(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
	
				$data['active_menu'] = $active_menu;
				
				$data['checkboxes'] = [
					'for_mis_concern' => 0,
					'for_lst_concern' => 0,
					'system_error' => 0,
					'user_error' => 0
					];

				if ($this->input->post()) {
					$control_number = $this->input->post('control_number');
					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
					$received_by = $this->input->post('received_by');
					$noted_by = $this->input->post('noted_by');
					$priority = $this->input->post('priority');
					$approval_stat = $this->input->post('app_stat');
					$reject_ticket = $this->input->post('reason_rejected');
					$solution = $this->input->post('solution');
					$resolved_by = $this->input->post('resolved_by');
					$resolved_date = $this->input->post('res_date');
					$others = $this->input->post('others');
					$received_by_lst = $this->input->post('received_by_lst');
					$date_lst = $this->input->post('date_lst');

					$checkbox_data = [
						'control_number' => $control_number,
						'for_mis_concern' => $this->input->post('checkbox_mis') ? 1 : 0,
						'for_lst_concern' => $this->input->post('checkbox_lst') ? 1 : 0,
						'system_error' => $this->input->post('checkbox_system_error') ? 1 : 0,
						'user_error' => $this->input->post('checkbox_user_error') ? 1 : 0,
					];

					$process = $this->Main_model->status_approval_tracc_concern($control_number, $received_by, $noted_by, $priority, $approval_stat, $reject_ticket, $solution, $resolved_by, $resolved_date, $others, $received_by_lst, $date_lst);
					$process_checkbox = $this->Main_model->insert_checkbox_data($checkbox_data);
	
					if ($process[0] == 1 && $process_checkbox[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved');
					} else {
						$this->session->set_flashdata('error', 'Updated.');
					}
	
					redirect(base_url()."sys/admin/list/ticket/tracc_concern");
				}

				// Load views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_tracc_concern', $data);
				$this->load->view('admin/footer');
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

	//TRACC REQUEST List of Ticket for Admin
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

					$process = $this->Main_model->status_approval_trf($trf_number, $app_stat);
					print_r($process);
					
					if (isset($process[0]) && $process[0] == 1) {
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}

					redirect(base_url()."sys/admin/list/ticket/tracc_request");
				}
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_tracc_request', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	//MSRF List of Ticket for Admin
	public function admin_list_tickets() {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
	
				$data['active_menu'] = $active_menu;
	
				// Process form submission
				if ($this->input->post()) {
					// Capture form data
					$msrf_number = $this->input->post('msrf_number');
					$approval_stat = $this->input->post('approval_stat');
					$rejecttix = $this->input->post('rejecttix');
					
					
					//print_r($_POST); // Add this line to debug POST data
					// Pass data to the model for processing
					$process = $this->Main_model->status_approval_msrf($msrf_number, $approval_stat, $rejecttix);  // Update this line in the model
					//var_dump($process);
					
					/*if ($process[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved');
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}*/

					if (isset($process[0]) && $process[0] == 1) {
						//Tickets Approved
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}
	
					// Redirect after form processing
					redirect(base_url()."sys/admin/list/ticket/msrf");
				}
	
				// Load views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_msrf', $data);
				$this->load->view('admin/footer');
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

	// public function admin_list_tickets() {
	// 	$this->load->helper('form');
	// 	$this->load->library('form_validation');

	// 	if ($this->session->userdata('login_data')) {
	// 		$user_details = $this->Main_model->user_details();

	// 		if ($user_details[0] == "ok"){
	// 			$sid = $this->session->session_id;
	// 			$data['user_details'] = $user_details[1];

	// 			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
	// 			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

	// 			$data['active_menu'] = $active_menu;

	// 			if($this->input->post()) {
	// 				$msrf_number = $this->input->post('msrf_number');
	// 				$app_stat = $this->input->post('approval_stat');

	// 				$process = $this->Main_model->status_approval_msrf($msrf_number, $app_stat);
	// 				print_r($process);

	// 				if (isset($process[0]) && $process[0] == 1) {
	// 					$this->session->set_flashdata('success', "Tickets been updated");
	// 				} else {
	// 					$this->session->set_flashdata('error', "Update Failed");
	// 				}

	// 				//redirect(base_url()."sys/admin/list/ticket/msrf");
	// 			}
	// 			$this->load->view('admin/header', $data);
	// 			$this->load->view('admin/sidebar', $data);
	// 			$this->load->view('admin/tickets_msrf', $data);
	// 			$this->load->view('admin/footer');
	// 		}
	// 	} else {
	// 		$this->session->sess_destroy();
	// 		$this->session->set_flashdata('error', 'Session expired. Please login again.');
	// 		redirect("sys/authentication");
	// 	}
	// }	
	
	//Adding FORM of department for ADMIN
	public function admin_list_department() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				if ($department_data[0] == "ok") {
					// Assign department data to the view data array
					$data['departments'] = $department_data[1];
				} else {
					// Handle the case when no departments are found
					$data['departments'] = array();
					echo "No departments found."; // Output a message for debugging
				}

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/add_department', $data);
				$this->load->view('admin/footer');
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


	//Adding FORM Employee in ADMIN
	public function admin_list_employee() {
		// Check if the user is logged in by verifying session data
		if($this->session->userdata('login_data')) {
			// Fetch user details from the Main_model
			$user_details = $this->Main_model->user_details();
			// Fetch department data from the Main_model
			$department_data = $this->Main_model->getDepartment();
	
			// Check if user details were fetched successfully
			if ($user_details[0] == "ok") {
				// Get the session ID
				$sid = $this->session->session_id;
				// Assign user details to the data array for use in the view
				$data['user_details'] = $user_details[1];
				// Assign department data to the data array for use in the view
				$data['department_data'] = $department_data;
	
				// Define the allowed menus
				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				// Get the current menu from the URI segment and ensure it is allowed, defaulting to 'system_administration' if not
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
	
				// Set the active menu in the data array for use in the view
				$data['active_menu'] = $active_menu;
	
				// Check if department data was fetched successfully
				if ($department_data[0] == "ok") {
					// Assign the fetched departments to the data array for use in the view
					$data['departments'] = $department_data[1];
				} else {
					// Handle the case when no departments are found by assigning an empty array
					$data['departments'] = array();
					// Output a message for debugging purposes
					echo "No departments found.";
				}
	
				// Load the views and pass the data array to them
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/add_employee', $data);
				$this->load->view('admin/footer');
			} else {
				// If user details could not be fetched, set an error message and redirect to the authentication page
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			// If the user is not logged in, destroy the session, set an error message, and redirect to the authentication page
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
	
	// Adding employee ADMIN
	public function employee_add() {
		// Load the form helper and session library
		$this->load->helper('form');
		$this->load->library('session');
	
		// Set form validation rules for the 'emp_id' field
		$this->form_validation->set_rules('emp_id', 'Employee ID', 'trim|required');
	
		// Fetch user details from the Main_model
		$user_details = $this->Main_model->user_details();
	
		// Check if the form validation has passed
		if ($this->form_validation->run() == FALSE) {
			// If validation fails, return error message in JSON format
			$response = [
				'status' => 'error',
				'message' => validation_errors() // Provide the validation error messages
			];
		} else {
			// If validation passes, process the addition of the employee
			$process = $this->Main_model->add_employee();
	
			// Check the result of the process
			if ($process[0] == 1) {
				// If successful, return a success message in JSON format
				$response = [
					'status' => 'success',
					'message' => $process[1]
				];
			} else {
				// If unsuccessful, return an error message in JSON format
				$response = [
					'status' => 'error',
					'message' => $process[1] // Assuming $process[1] contains the error message
				];
			}
		}
	
		// Return the JSON response
		echo json_encode($response);
	}

	//Updating Employee for ADMIN
	public function employee_update() {
		// Load the form helper and form validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		// Retrieve the employee ID from the submitted form data, with XSS filtering
		$id = $this->input->post('id', true);
	
		// Check if the user is logged in by verifying the session data
		if ($this->session->userdata('login_data')) {
			// Retrieve the logged-in user's ID from session data
			$user_id = $this->session->userdata('login_data')['user_id'];
	
			// Fetch detailed information about the user from the model
			$user_details = $this->Main_model->user_details();
	
			// Fetch department data from the model
			$department_data = $this->Main_model->getDepartment();
	
			// Check if the user details were retrieved successfully
			if ($user_details[0] == "ok") {
				// Call the model method to update the employee's data
				$process = $this->Main_model->update_employee();
	
				// Return JSON response
				if ($process[0] == 1) {
					echo json_encode(array('status' => 'success', 'message' => 'Employee is been updated successfully.'));
				} else {
					echo json_encode(array('status' => 'error', 'message' =>  $process[1]));
				}
				return; // Prevent loading the view when using AJAX
			} else {
				echo json_encode(array('status' => 'error', 'message' => 'Error fetching user information.'));
				return;
			}
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Session expired. Please login again.'));
			return;
		}
	}

	//Deleting Employee for Admin
	public function employee_delete($id){
		if (is_numeric($id)){
			$status = $this->Main_model->delete_employee($id);
				if($status){
					echo json_encode(['status' => 'success', 'message' => 'Succesfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the Employee.']);
				}
		}else{
			echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID.']);
		}
	}

	public function list_update_employee($id) {
		// Check if the user is logged in by verifying session data
		if ($this->session->userdata('login_data')) {
	
			// Fetch detailed information about the logged-in user from the model
			$user_details = $this->Main_model->user_details();
	
			// Fetch department data from the model
			$department_data = $this->Main_model->getDepartment();
	
			// Fetch details of the specific user to be updated based on the provided ID
			$users_det = $this->Main_model->users_details_put($id);
	
			// Check if the user details were retrieved successfully
			if ($user_details[0] == "ok") {
	
				// Get the session ID (though it's not used further in this code)
				$sid = $this->session->session_id;
	
				// Store the user details, department data, and the details of the user to be updated in the $data array
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['users_det'] = $users_det[1];
	
				// Define allowed menus for navigation and determine the active menu based on the URI segment
				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
				$data['active_menu'] = $active_menu;
	
				// Check if department data was successfully retrieved
				if ($department_data[0] == "ok") {
					// If successful, store the department list in the $data array
					$data['departments'] = $department_data[1];
				} else {
					// If not, initialize an empty array and display an error message
					$data['departments'] = array();
					echo "No departments found.";
				}
	
				// Load the views for the admin page, passing the $data array to the views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/update_employee', $data);
				$this->load->view('admin/footer');
	
			} else {
				// If user details retrieval failed, set an error message and redirect to the authentication page
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			// If the user is not logged in (session data is missing), destroy the session, set an error message, and redirect to the authentication page
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
	
	// else if
	// public function admin_approval_list($subject, $id) {
	// 	if ($this->session->userdata('login_data')) {
	// 		$user_id = $this->session->userdata('login_data')['user_id'];
	// 		$user_role = $this->session->userdata('login_data')['role'];
	// 		$user_dept = $this->session->userdata('login_data')['sup_id'];
	// 		$dept_id = $this->session->userdata('login_data')['dept_id'];
	// 		$user_details = $this->Main_model->user_details();
	// 		$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
	// 		$getTraccCon = $this->Main_model->getTraccConcernByID($id);
	// 		$trf_tickets = $this->Main_model->getTicketsTRF($id);
	// 		$checkbox_newadd = $this->Main_model->getCheckboxDataNewAdd($id);
	// 		$checkbox_update = $this->Main_model->getCheckboxDataUpdate($id);
	// 		$checkbox_account = $this->Main_model->getCheckboxDataAccount($id);

	// 		$ict = $this->Main_model->GetICTSupervisor();

	// 		if ($user_details[0] == "ok") {
	// 			$sid = $this->session->session_id;
	// 			$data['user_details'] = $user_details[1];
	// 			$data['msrf'] = $msrf_tickets[1];
	// 			$data['tracc_con'] = $getTraccCon[1];
	// 			$data['trf'] = $trf_tickets[1];
	// 			$data['ict'] = $ict;
	// 			$data['checkbox_newadd'] = $checkbox_newadd;
	// 			$data['checkbox_update'] = $checkbox_update;
	// 			$data['checkbox_account'] = $checkbox_account;
	// 			$emp_id = $user_details[1]["emp_id"];
	// 			$getTeam = $this->Main_model->GetTeam($dept_id);
	// 			$data['pages'] = 'tickets';

	// 			$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
	// 			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';

	// 			$data['active_menu'] = $active_menu;

	// 			// Handle TRACC Concern data
	// 			if ($subject == "TRACC_CONCERN") {
	// 				if ($getTraccCon[0] == "ok") {
	// 					// Access control_number safely
	// 					$control_number = $getTraccCon[1]['control_number'];
	// 					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
	// 					$data['tracc_con'] = $getTraccCon[1]; // Store TRACC concern data
	// 				} else {
	// 					// Handle the case where no TRACC Concern data was found
	// 					$data['checkboxes'] = []; // Set to empty array if no data
	// 					$data['tracc_con'] = []; // Set to empty array if no data
	// 					$this->session->set_flashdata('error', $getTraccCon[1]); // Use the error message returned
	// 				}
	
	// 				// Load TRACC Concern views
	// 				$this->load->view('admin/header', $data);
	// 				$this->load->view('admin/tickets_approval_tracc_concern', $data);
	// 				$this->load->view('admin/sidebar', $data);
	// 				$this->load->view('admin/footer');
	
	// 			} else if ($subject == "MSRF") {
	// 				$this->load->view('admin/header', $data);
	// 				$this->load->view('admin/tickets_approval_msrf', $data);
	// 				$this->load->view('admin/sidebar', $data);
	// 				$this->load->view('admin/footer');

	// 			} else if ($subject == "TRACC_REQUEST"){
	// 				$checkbox_lmi = 0;
	// 				$checkbox_rgdi = 0;
	// 				$checkbox_lpi = 0;
	// 				$checkbox_sv = 0;

	// 				if (isset($trf_tickets[1]['company'])) {
	// 					// Check if 'company' is a string and explode it into an array
	// 					$company_values = (is_string($trf_tickets[1]['company'])) ? explode(',', $trf_tickets[1]['company']) : $trf_tickets[1]['company'];
			
	// 					// Ensure that $company_values is an array
	// 					if (is_array($company_values)) {
	// 						$checkbox_lmi = in_array('LMI', $company_values) ? 1 : 0;
	// 						$checkbox_rgdi = in_array('RGDI', $company_values) ? 1 : 0;
	// 						$checkbox_lpi = in_array('LPI', $company_values) ? 1 : 0;
	// 						$checkbox_sv = in_array('SV', $company_values) ? 1 : 0;
	// 					}
	// 				}
	// 				// Passing data to view
	// 				$data['checkbox_lmi'] = $checkbox_lmi;
	// 				$data['checkbox_rgdi'] = $checkbox_rgdi;
	// 				$data['checkbox_lpi'] = $checkbox_lpi;
	// 				$data['checkbox_sv'] = $checkbox_sv;

	// 				$data['checkbox_newadd'] = $checkbox_newadd;
	// 				$data['checkbox_update'] = $checkbox_update;
	// 				$data['checkbox_account'] = $checkbox_account;

	// 				$this->load->view('admin/header', $data);
	// 				$this->load->view('admin/tickets_approval_tracc_request', $data);
	// 				$this->load->view('admin/sidebar', $data);
	// 				$this->load->view('admin/footer');
	// 			}


	// 			if ($user_role == "L2") {
	// 				$data['getTeam'] = $getTeam[1];
	// 			}

	// 		} else {
	// 			$this->session->set_flashdata('error', 'Error fetching user information.');
	// 			redirect("sys/authentication");
	// 		}
	// 	} else {
	// 		$this->session->set_flashdata('error', 'Error fetching user information');
    //         redirect("sys/authentication");
	// 	}
	// }
	
	//ADMIN APPROVAL for all tickets
	public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_role = $this->session->userdata('login_data')['role'];
			$user_dept = $this->session->userdata('login_data')['sup_id'];
			$dept_id = $this->session->userdata('login_data')['dept_id'];
			$user_details = $this->Main_model->user_details();
			$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
			$trf_tickets = $this->Main_model->getTicketsTRF($id);
			$checkbox_newadd = $this->Main_model->getCheckboxDataNewAdd($id);
			$checkbox_update = $this->Main_model->getCheckboxDataUpdate($id);
			$checkbox_account = $this->Main_model->getCheckboxDataAccount($id);
	
			$ict = $this->Main_model->GetICTSupervisor();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['msrf'] = $msrf_tickets[1];
				$data['tracc_con'] = $getTraccCon[1];
				$data['trf'] = $trf_tickets[1];
				$data['ict'] = $ict;
				$data['checkbox_newadd'] = $checkbox_newadd;
				$data['checkbox_update'] = $checkbox_update;
				$data['checkbox_account'] = $checkbox_account;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);
				$data['pages'] = 'tickets';
	
				$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';
	
				$data['active_menu'] = $active_menu;
	
				// Handling TRF tickets company checkboxes
				$checkbox_lmi = $checkbox_rgdi = $checkbox_lpi = $checkbox_sv = 0;
				if (isset($trf_tickets[1]['company'])) {
					// Check if 'company' is a string and explode it into an array
					$company_values = (is_string($trf_tickets[1]['company'])) ? explode(',', $trf_tickets[1]['company']) : $trf_tickets[1]['company'];
					if (is_array($company_values)) {
						$checkbox_lmi = in_array('LMI', $company_values) ? 1 : 0;
						$checkbox_rgdi = in_array('RGDI', $company_values) ? 1 : 0;
						$checkbox_lpi = in_array('LPI', $company_values) ? 1 : 0;
						$checkbox_sv = in_array('SV', $company_values) ? 1 : 0;
					}
				}
	
				$data['checkbox_lmi'] = $checkbox_lmi;
				$data['checkbox_rgdi'] = $checkbox_rgdi;
				$data['checkbox_lpi'] = $checkbox_lpi;
				$data['checkbox_sv'] = $checkbox_sv;
	
				// Switch case to handle different subjects
				switch ($subject) {
					case "TRACC_CONCERN":
						if ($getTraccCon[0] == "ok") {
							$control_number = $getTraccCon[1]['control_number'];
							$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
							$data['tracc_con'] = $getTraccCon[1];
						} else {
							$data['checkboxes'] = [];
							$data['tracc_con'] = [];
							$this->session->set_flashdata('error', $getTraccCon[1]);
						}
						$this->load->view('admin/header', $data);
						$this->load->view('admin/tickets_approval_tracc_concern', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "MSRF":
						$this->load->view('admin/header', $data);
						$this->load->view('admin/tickets_approval_msrf', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "TRACC_REQUEST":
						// No need to repeat checkbox data
						$data['checkbox_newadd'] = $checkbox_newadd;
						$data['checkbox_update'] = $checkbox_update;
						$data['checkbox_account'] = $checkbox_account;
	
						$this->load->view('admin/header', $data);
						$this->load->view('admin/tickets_approval_tracc_request', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					default:
						$this->session->set_flashdata('error', 'Invalid subject provided.');
						redirect("sys/authentication");
				}
	
				if ($user_role == "L2") {
					$data['getTeam'] = $getTeam[1];
				}
	
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect("sys/authentication");
		}
	}
	

	/*public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_role = $this->session->userdata('login_data')['role'];
			$user_dept = $this->session->userdata('login_data')['sup_id'];
			$dept_id = $this->session->userdata('login_data')['dept_id'];
			$user_details = $this->Main_model->user_details();
			$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
			$ict = $this->Main_model->GetICTSupervisor();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['msrf'] = $msrf_tickets[1];
				$data['ict'] = $ict;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);
				$data['pages'] = 'tickets';
	
				// Handle the active menu logic
				$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';
				$data['active_menu'] = $active_menu;
	
				// Handle TRACC Concern data
				if ($subject == "TRACC_CONCERN") {
					if ($getTraccCon[0] == "ok") {
						// Access control_number safely
						$control_number = $getTraccCon[1]['control_number'];
						$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
						$data['tracc_con'] = $getTraccCon[1]; // Store TRACC concern data
					} else {
						// Handle the case where no TRACC Concern data was found
						$data['checkboxes'] = []; // Set to empty array if no data
						$data['tracc_con'] = []; // Set to empty array if no data
						$this->session->set_flashdata('error', $getTraccCon[1]); // Use the error message returned
					}
	
					// Load TRACC Concern views
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_tracc_concern', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
	
				} else if ($subject == "MSRF") {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_msrf', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
				}
	
				// Other logic for L2 user role if applicable
				if ($user_role == "L2") {
					$data['getTeam'] = $getTeam[1];
				}
	
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect("sys/authentication");
		}
	}*/


	public function dept_supervisor_approval() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();
			if ($user_details[0] == "ok") {

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$process = $this->Main_model->status_approval_msrf();
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', 'Tickets Approved');
					redirect(base_url()."sys/admin/list/ticket");
				} else {
					$this->session->set_flashdata('error', 'Update failed.');
					redirect(base_url()."sys/admin/list/ticket");
				}
				
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect(base_url()."admin/login");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}

	// Method to handle adding a department
	public function department_add() {
		// Load the form helper and form validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	
		// Set validation rule for the 'dept_desc' field (Department Name)
		$this->form_validation->set_rules('dept_desc', 'Department Name', 'trim|required');
	
		// Check if the request is AJAX
		if ($this->input->is_ajax_request()) {
			// Check if form validation has failed
			if ($this->form_validation->run() == FALSE) {
				// Return JSON response with validation errors
				echo json_encode(array('status' => 'error', 'message' => validation_errors()));
				return;
			}
	
			// If form validation is successful, proceed with adding the department
			$process = $this->Main_model->add_department();
	
			// Check if the addition was successful
			if ($process[0] == 1) {
				echo json_encode(array('status' => 'success', 'message' => 'Department added successfully!'));
			} else {
				echo json_encode(array('status' => 'error', 'message' => $process[0]));
			}
			return; // Prevent loading the view when using AJAX
		} else {
			// Handle non-AJAX requests (standard page load)
			$user_details = $this->Main_model->user_details();
			$data['user_details'] = $user_details[1];
			$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
			$data['active_menu'] = $active_menu;
	
			// Load the views with the prepared data
			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/add_department', $data);
			$this->load->view('admin/footer');
		}
	}
	
	//Updating department
	public function department_update($id) {
		// Ensure $id is an integer for security
		$id = (int) $id;
	
		// Fetch department details using the provided ID from the model
		$data['user'] = $this->Main_model->get_department_details($id);
		// Pass the ID to the view for use in the form action or other purposes
		$data['recid'] = $id;
	
		// Check if the form was submitted via POST request
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Retrieve form data from POST request
			$dept_desc = $this->input->post('dept_desc');
			$manager_id = $this->input->post('manager_id');
			$sup_id = $this->input->post('sup_id');
	
			// Prepare data array to be updated in the database
			$update_data = array(
				'dept_desc' => $dept_desc,
				'manager_id' => $manager_id,
				'sup_id' => $sup_id,
			);
	
			// Remove empty fields to avoid overwriting with NULL values
			foreach ($update_data as $key => $value) {
				if (empty($value)) {
					unset($update_data[$key]);
				}
			}
	
			// Call the model's method to update the department record with the new data
			$status = $this->Main_model->update_department($update_data, $id);
	
			// Return JSON response
			if ($status[0] == 1) {
				echo json_encode(array('status' => 'success', 'message' => $status[1]));
			} else {
				echo json_encode(array('status' => 'error', 'message' => $status[1]));
			}
			return; // Prevent loading the view when using AJAX
		}
	
		// Load the view for updating department details with the fetched data
		$this->load->view('admin/update_department', $data);
	}
	
	//Deleting department
	public function department_delete($id) {
		if (is_numeric($id)) {
			$status = $this->Main_model->delete_department($id);
				if ($status) {
					echo json_encode(['status' => 'success', 'message' => 'Successfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the department.']);
				}
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Invalid department ID.']);
			}
	}

	//Edit Form for department
	public function list_update_department($id) {
		// Check if the user is logged in by verifying session data
		if ($this->session->userdata('login_data')) {
	
			// Fetch detailed information about the logged-in user from the model
			$user_details = $this->Main_model->user_details();
	
			// Fetch department data from the model
			$department_data = $this->Main_model->getDepartment();
	
			// Fetch details of the specific department to be updated based on the provided ID
			$dept_details = $this->Main_model->get_department_details($id);
	
	
			// Check if the user details were retrieved successfully
			if ($user_details[0] == "ok") {
	
				// Get the session ID (though it's not used further in this code)
				$sid = $this->session->session_id;
	
				// Store the user details, department data, and the details of the department to be updated in the $data array
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['dept_details'] = $dept_details[1];
	
				// Define allowed menus for navigation and determine the active menu based on the URI segment
				$allowed_menus = ['dashboard', 'system_administration', 'departments', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
				$data['active_menu'] = $active_menu;
	
				// Check if department data was successfully retrieved
				if ($department_data[0] == "ok") {
					// If successful, store the department list in the $data array
					$data['departments'] = $department_data[1];
				} else {
					// If not, initialize an empty array and display an error message
					$data['departments'] = array();
					echo "No departments found.";
				}
	
				// Load the views for the admin page, passing the $data array to the views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/update_department', $data);
				$this->load->view('admin/footer');
	
			} else {
				// If user details retrieval failed, set an error message and redirect to the authentication page
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			// If the user is not logged in (session data is missing), destroy the session, set an error message, and redirect to the authentication page
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}


	public function details_tickets_list() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/tickets_msrf');
			$this->load->view('admin/footer');

		} else {
			$this->session->sess_destroy();
        	$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	// Users Dashboard
	public function users_dashboard() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];

				$this->load->view('users/header', $data);
				$this->load->view('users/dashboard', $data);
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

	 /*public function users_creation_tickets_msrf() {
	 	$id = $this->session->userdata('login_data')['user_id'];
	 	$this->load->helper('form');
         $this->load->library('session');
	 	$this->form_validation->set_rules('trn_number', 'Ticket ID', 'trim|required');
	 	$user_details = $this->Main_model->user_details();
	 	$department_data = $this->Main_model->getDepartment();
	 	$getdepartment = $this->Main_model->GetDepartmentID();
	 	$users_det = $this->Main_model->users_details_put($id);
		
	 	if ($this->form_validation->run() == FALSE) {
	 		$data['user_details'] = $user_details[1];
	 		$msrf = $this->GenerateMSRFNo();
			
	 		$data['getdept'] = $getdepartment[1];
	 		$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();

	 		$data['msrf'] = $msrf;
	 		$data['department_data'] = $department_data;
	 		$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();

	 		if ($department_data[0] == "ok") {
	 			$data['departments'] = $department_data[1];
	 		} else {
	 			$data['departments'] = array();
	 			echo "No departments found.";
	 		}

	 		$this->load->view('users/header', $data);
	 		$this->load->view('users/service_request_form_msrf_creation', $data);
	 		$this->load->view('users/footer');
	 	} else {
	 		$process = $this->Main_model->msrf_add_ticket();
	 		if ($process[0] == 1) {
	 			$this->session->set_flashdata('success', $process[1]);
	 			redirect(base_url().'sys/users/list/tickets/msrf');
	 		} else {
	 			$this->session->set_flashdata('error', $process[1]);
	 			redirect(base_url().'sys/users/list/tickets/msrf');
	 		}
	 	}
	 }*/

	/*public function user_creation_tickets_tracc_concern() {
		// Get the logged-in user's ID from session data
		$id = $this->session->userdata('login_data')['user_id'];
	
		// Load necessary helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
	
		// Set form validation for 'control_number' field (user-inputted, required)
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');
	
		// Retrieve user details and department information
		$user_details = $this->Main_model->user_details();              // Get logged-in user's basic details
		$getdepartment = $this->Main_model->GetDepartmentID();          // Get department ID for the user
		$users_det = $this->Main_model->users_details_put($id);         // Get detailed user info based on the user ID
	
		// Check if form validation failed
		if ($this->form_validation->run() == FALSE) {
			// Prepare data for the view, including user and department info
			$data['user_details'] = $user_details[1];                   // Store user details in the data array
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  // Get detailed user info
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  // Get department info
	
			// Get department information based on the user's department ID
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   // Fetch department details
			$data['get_department'] = $get_department;  // Store the department details in the data array
	
			// Load the form view with prefilled user and department data
			$this->load->view('users/header', $data);  // Load header
			$this->load->view('users/tracc_concern_form_creation', $data);  // Load the form view
			$this->load->view('users/footer');  // Load footer
	
		} else {
			// Form validation passed, process the form submission
			// All form fields except for requestor, date requested, and department are user-inputted
	
			// Process the form and insert into the database using model function
			$process = $this->Main_model->tracc_concern_add_ticket();  // Adjust model call to add TRACC concern ticket
	
			// Check if the process was successful
			if ($process[0] == 1) {
				// Set a success message in session and redirect to the appropriate page
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/tracc_concern');  // Redirect to ticket list after successful submission
			} else {
				// Set an error message in session and redirect back to the form
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/create/tickets/tracc_concern');  // Redirect back to form in case of error
			}
		}
	}*/

	//creation of ticket for tracc concern
	public function user_creation_tickets_tracc_concern() {
		// Get the logged-in user's ID from session data
		$id = $this->session->userdata('login_data')['user_id'];
	
		// Load necessary helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload'); 
	
		// Set form validation rules
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');
	
		// Retrieve user details and department information
		$user_details = $this->Main_model->user_details();              
		$getdepartment = $this->Main_model->GetDepartmentID();          
		$users_det = $this->Main_model->users_details_put($id);         
	
		// Check if form validation failed
		if ($this->form_validation->run() == FALSE) {
			// Prepare data for the view
			$data['user_details'] = $user_details[1];                   
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
			
			// Get department information based on the user's department ID
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   
			$data['get_department'] = $get_department;  
	
			// Load the form view
			$this->load->view('users/header', $data);  
			$this->load->view('users/tracc_concern_form_creation', $data);  
			$this->load->view('users/footer');  
		} else {
			// Check if file is uploaded
			$file_path = null; // Initialize file path
			if (!empty($_FILES['uploaded_photo']['name'])) {
				// File upload configuration
				$config['upload_path'] = FCPATH . 'uploads/tracc_concern/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg'; 
				$config['max_size'] = 5048; 
				$config['file_name'] = time() . '_' . $_FILES['uploaded_photo']['name']; 
	
				// Load the upload library with configuration
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_photo')) {
					// Log the error and redirect back to the form
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url().'sys/users/create/tickets/tracc_concern');  
				} else {
					// Get file data and store the file name
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
					//echo 'Uploaded file path: ' . $file_path; // Debugging output
				}
			}
	
			// Process the form and insert into the database using model function
			$process = $this->Main_model->tracc_concern_add_ticket($file_path);  
	
			// Debugging output for the returned process
			//var_dump($process); 
			//exit;
	
			// Check if the process was successful
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/tracc_concern');  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/create/tickets/tracc_concern');  
			}
		}
	}
	
	//creation of ticket for msrf
	public function users_creation_tickets_msrf() {
		// Get the currently logged-in user's ID from session data
		$id = $this->session->userdata('login_data')['user_id'];
	
		// Load the form helper and session library
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		// Set form validation rule for 'msrf_number' (must not be empty)
		$this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required');
	
		// Retrieve user details, department ID, and additional user data
		$user_details = $this->Main_model->user_details();           // Gets the logged-in user's details
		$getdepartment = $this->Main_model->GetDepartmentID();       // Retrieves the department ID of the logged-in user
		$users_det = $this->Main_model->users_details_put($id);      // Gets detailed user info for the user ID
	
		// Check if form validation passed or failed
		if ($this->form_validation->run() == FALSE) {
			// If validation fails, generate a new MSRF number and gather necessary data to re-display the form
	
			// Generate a new MSRF number
			$msrf = $this->GenerateMSRFNo();
			
			// Populate data to be sent to the view
			$data['msrf'] = $msrf;                                    // Store the generated MSRF number
			$data['user_details'] = $user_details[1];                 // Store user details (assumed from an array result)
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  // Detailed user information, or empty array if not available
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  // Department info
	
			// Get the department of the current user
			$users_department = $users_det[1]['dept_id'];             // Get the department ID from the user details
			$get_department = $this->Main_model->UsersDepartment($users_department);  // Retrieve department details using department ID
			$data['get_department'] = $get_department;                // Store the department information
			
			// Load the view with the gathered data (this displays the form)
			$this->load->view('users/header', $data);
			$this->load->view('users/service_request_form_msrf_creation', $data);
			$this->load->view('users/footer');
	
		} else {
			$file_path = null; // Initialize file path
			if (!empty($_FILES['uploaded_file']['name'])) {
				// File upload configuration
				$config['upload_path'] = FCPATH . 'uploads/msrf/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg'; 
				$config['max_size'] = 5048; 
				$config['file_name'] = time() . '_' . $_FILES['uploaded_file']['name']; 
	
				// Load the upload library with configuration
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_file')) {
					// Log the error and redirect back to the form
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url().'sys/users/create/tickets/msrf');  
				} else {
					// Get file data and store the file name
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
					echo 'Uploaded file path: ' . $file_path; // Debugging output
				}
			}

			// If form validation succeeds, process the form submission (add the MSRF ticket)
	
			// Call the model function to add the new MSRF ticket to the database
			$process = $this->Main_model->msrf_add_ticket($file_path);
	
			// Check if the process was successful
			if ($process[0] == 1) {
				// If success, set a success message and redirect to the ticket list
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/msrf');
			} else {
				// If an error occurred, set an error message and redirect to the ticket list
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/list/tickets/msrf');
			}
		}
	}
	
	//datatable na nakikita ni user MSRF
	public function service_form_msrf_list() {
		// Get session data for the logged-in user
		$id = $this->session->userdata('login_data')['user_id']; // Fetches the user ID from the session
		$dept_id = $this->session->userdata('login_data')['dept_id']; // Fetches the department ID from the session
	
		// Load form and session libraries/helpers for validation
		$this->load->helper('form'); // Loads form helper for handling form elements
		$this->load->library('session'); // Loads session library to access session data
	
		// Set validation rule for the form input 'msrf_number'
		$this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required'); 
		// 'msrf_number' is required; it will trigger an error if it's not present.
	
		// Fetch user and department details from the model
		$user_details = $this->Main_model->user_details(); // Gets the current user details
		$department_data = $this->Main_model->getDepartment(); // Gets the department data
		$users_det = $this->Main_model->users_details_put($id); // Gets user details based on the session user ID
		$getdepartment = $this->Main_model->GetDepartmentID(); // Fetches department data based on the session department ID
	
		// Check if the form validation has failed
		if ($this->form_validation->run() == FALSE) { 
			// If validation fails, it will proceed to generate the MSRF number
			$msrfNumber = $this->GenerateMSRFNo(); // Calls a function to generate a new MSRF number
	
			// Populate data array to pass to views
			$data['user_details'] = $user_details[1]; // Stores the user details in the data array for view
			$data['department_data'] = $department_data; // Stores department data
			$data['users_det'] = $users_det[1]; // User details (fetched using user ID)
			$data['dept_id'] = $dept_id; // Stores department ID from session
	
			// Check if department data is retrieved successfully
			if ($department_data[0] == "ok") { 
				$data['departments'] = $department_data[1]; // Stores departments if the data was fetched successfully
			} else {
				$data['departments'] = array(); // If no departments were found, set to an empty array
				echo "No departments found."; // Output an error message
			}
	
			$data['getdept'] = $getdepartment[1]; // Stores department information
			
			// Add a form type to differentiate between MSRF and TRACC
			$data['form_type'] = 'msrf';
			
			// Prepare the MSRF number and load views
			$data['msrfNumber'] = $msrfNumber; // Stores the generated MSRF number
			$this->load->view('users/header', $data); // Loads the header view, passing the data array
			$this->load->view('users/service_request_form_msrf_list', $data); // Loads the main view for the service request form
			$this->load->view('users/footer', $data); // Loads the footer view
	
		} else { // If form validation passes, proceed to process the form data
	
			// Add ticket (submit the MSRF form)
			$process = $this->Main_model->msrf_add_ticket(); // Calls model function to add a new MSRF ticket
	
			// Check if ticket was added successfully
			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]); // Sets a success message in session data
				redirect(base_url().'sys/users/dashboard'); // Redirect to the user's dashboard
			} else {
				$this->session->set_flashdata('error', $process[1]); // Sets an error message in session data
				redirect(base_url().'sys/users/dashboard'); // Redirect to the dashboard on error as well
			}
		}
	}

	//datatable na makikita ni user tracc concern
	public function service_form_tracc_concern_list() {
		$id = $this->session->userdata('login_data')['user_id']; // Fetches the user ID from the session
		$dept_id = $this->session->userdata('login_data')['dept_id']; // Fetches the department ID from the session
		
		// Load form and session libraries/helpers for validation
		$this->load->helper('form'); // Loads form helper for handling form elements
		$this->load->library('session'); // Loads session library to access session data
	
		// Set validation rule for the form input 'Control Number'
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required'); 
		// 'Control Number' is required; it will trigger an error if it's not present.
	
		// Fetch user and department details from the model
		$user_details = $this->Main_model->user_details(); // Gets the current user details
		$department_data = $this->Main_model->getDepartment(); // Gets the department data
		$users_det = $this->Main_model->users_details_put($id); // Gets user details based on the session user ID
		$getdepartment = $this->Main_model->GetDepartmentID(); // Fetches department data based on the session department ID
	
		// Check if the form validation has failed
		if ($this->form_validation->run() == FALSE) { 
			
			// Populate data array to pass to views
			$data['user_details'] = $user_details[1]; // Stores the user details in the data array for view
			$data['department_data'] = $department_data; // Stores department data
			$data['users_det'] = $users_det[1]; // User details (fetched using user ID)
			$data['dept_id'] = $dept_id; // Stores department ID from session
			$control_number = $this->session->userdata('control_number');
	
			// Check if department data is retrieved successfully
			if ($department_data[0] == "ok") { 
				$data['departments'] = $department_data[1]; // Stores departments if the data was fetched successfully
			} else {
				$data['departments'] = array(); // If no departments were found, set to an empty array
				echo "No departments found."; // Output an error message
			}
	
			$data['getdept'] = $getdepartment[1]; // Stores department information
			
			// Add a form type to differentiate between MSRF and TRACC
			//$data['form_type'] = 'tracc_concern';

			$data['control_number'] = $control_number; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/tracc_concern_form_list', $data);
			$this->load->view('users/footer', $data);
	
		} else {
	
			$process = $this->Main_model->tracc_concern_add_ticket();

			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]); // Sets a success message in session data
				redirect(base_url().'sys/users/dashboard'); // Redirect to the user's dashboard
			} else {
				$this->session->set_flashdata('error', $process[1]); // Sets an error message in session data
				redirect(base_url().'sys/users/dashboard'); // Redirect to the dashboard on error as well
			}
		}

	}

	/*public function service_form_tracc_concern_list() {
		$id = $this->session->userdata('login_data')['user_id']; // Logged-in user ID
		$dept_id = $this->session->userdata('login_data')['dept_id']; // Logged-in user dept ID
		
		// Load form and session libraries/helpers for validation
		$this->load->helper('form');
		$this->load->library('session');
	
		// Set validation rule for 'control_number'
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');
	
		// Fetch user and department details
		$user_details = $this->Main_model->user_details();
		$department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);
		$getdepartment = $this->Main_model->GetDepartmentID();
	
		// Check if form validation failed
		if ($this->form_validation->run() == FALSE) {
			
			// Populate data array
			$data['user_details'] = $user_details[1]; 
			$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];
			$data['dept_id'] = $dept_id;
	
			// Fetch the control numbers created by this specific user
			$control_number = $this->session->userdata('control_number');
			$user_tickets = $this->Main_model->get_tickets_by_user($id); // Add this logic in the model
	
			// Only show tickets if they belong to the logged-in user
			if ($user_tickets) {
				$data['tickets'] = $user_tickets;
			} else {
				$data['tickets'] = array(); // No tickets found
				echo "No tickets found for this user.";
			}
	
			// Load views
			$this->load->view('users/header', $data);
			$this->load->view('users/tracc_concern_form_list', $data);
			$this->load->view('users/footer', $data);
	
		} else {
			$process = $this->Main_model->tracc_concern_add_ticket();
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}
		}
	}*/

	//Generate MSRF Number
	public function GenerateMSRFNo() {
		$lastMSRF = $this->Main_model->getLastMSRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'MSRF-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
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

	public function users_creation_tickets_tracc() {
		// for tracc function
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
        $this->load->library('session');
		$this->form_validation->set_rules('trn_number', 'Ticket ID', 'trim|required');
		$user_details = $this->Main_model->user_details();
		$department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$traccNumber = $this->GenerateTRACCNo();

			$data['traccNumber'] = $traccNumber;
			$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];

			if ($department_data[0] == "ok") {
				$data['departments'] = $department_data[1];
			} else {
				$data['departments'] = array();
				echo "No departments found.";
			}

			$this->load->view('users/service_form_tracc', $data);
		} else {
			$trn_no = $this->input->post('trn_number', true);
			$name = $this->input->post('name', true);
			$department = $this->input->post('department_description', true);
			$dept_id = $this->input->post('dept_id', true);
			$date_req = $this->input->post('date_req', true);
			$date_need = $this->input->post('date_need', true);
			$web_app = $this->input->post('r3', true);
			$webString = implode(", ", $web_app);
			$links_master = $this->input->post('r1', true);
			$details = $this->input->post('concern', true);
			$sup_id = $this->input->post('sup_id', true);
			$others = $this->input->post('others', true);
			$purpose = $this->input->post('purpose', true);
			
			$data = array(
				'ticket_id' => $trn_no,
				'subject' => 'TRACC',
				'requestor_name' => $name,
				'department' => $department,
				'dept_id' => $dept_id,
				'date_requested' => $date_req,
				'date_needed' => $date_need,
				'web_concern' => $webString,
				'tracc_access' => $links_master,
				'others' => $others,
				'purpose' => $purpose,
				'details_concern' => $details,
				'status' => 'Open',
				'head_approval_status' => 'Pending',
				'priority' => 'High',
				'request_id' => $id,
				'sup_id' => $sup_id,
				'it_dept_id' => 1,
				'it_sup_id' => '',
				'it_approval_status' => 'In Progress',
				'created_at' => date("Y-m-d H:i:s")
			);

			var_dump($data);
		}
	}

	/*public function tracc_concern_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['tracccon'] = $getTraccCon[1];
				
				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_concern_form_details', $data);
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
	}*/

	//MSRF details
	public function service_form_msrf_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getMsrf = $this->Main_model->getTicketsMSRF($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['msrf'] = $getMsrf[1];

				$this->load->view('users/header', $data);
				$this->load->view('users/service_request_form_msrf_details', $data);
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

	//Tracc concern details
	public function tracc_concern_form_details($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['tracc_con'] = $getTraccCon[1];

				if (isset($getTraccCon[1])) {
					$control_number = $getTraccCon[1]['control_number'];
					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);  
					$data['tracc_con'] = $getTraccCon[1];
				} else {
					$data['checkboxes'] = [];
					$data['tracc_con'] = [];
					$this->session->set_flashdata('error', 'TRACC concern data not found.');
				}
				// Load the views and pass the data
				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_concern_form_details', $data);
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

	//Tracc Request details
	public function tracc_request_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTRF = $this->Main_model->getTicketsTRF($id);
			$getCheckboxDataNewAdd = $this->Main_model->getCheckboxDataNewAdd($id);
			$getCheckeboxDataUpdate = $this->Main_model->getCheckboxDataUpdate($id);
			$getCheckboxDataAccount = $this->Main_model->getCheckboxDataAccount($id);

			// echo '<pre>'; 
			// print_r($getCheckboxDataNewAdd); 
			// echo '</pre>'; 
			// exit;

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['trf'] = $getTRF[1];
				$data['checkbox_data_newadd'] = $getCheckboxDataNewAdd;
				$data['checkbox_data_update'] = $getCheckeboxDataUpdate;
				$data['checkbox_data_account'] = $getCheckboxDataAccount;

				$selected_companies = isset($data['trf']['company']) ? explode(',', $data['trf']['company']) : [];
				$data['selected_companies'] = $selected_companies; // Add to data

				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_request_form_details', $data);
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

	/*public function update_status_msrf_assign() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$ticket_id = $this->input->post('msrf_number', true);
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern);
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/create/tickets/msrf");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/create/tickets/msrf");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}*/

	public function update_status_msrf_assign() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$ticket_id = $this->input->post('msrf_number', true);
		$date_needed = $this->input->post('date_need', true);
		$asset_code = $this->input->post('asset_code', true);
		$request_category = $this->input->post('category', true);
		$specify = $this->input->post('msrf_specify', true);
		$details_concern = $this->input->post('concern', true);

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern);
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/list/tickets/msrf");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/list/tickets/msrf");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}

	//Acknowledging the form as resolved
	public function acknowledge_as_resolved() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$control_number = $this->input->post('control_number', true);
		if ($this->session->userdata('login_data')){
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->AcknolwedgeAsResolved($control_number);
				if ($process[0] == 1){
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/list/tickets/tracc_concern");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/list/tickets/tracc_concern");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect(base_url()."admin/login");
		}
	}

	/*public function acknowledge_as_resolved() {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		// Get the posted data
		$control_number = $this->input->post('control_number', true);
		$module_affected = $this->input->post('module_affected', true);
		$company = $this->input->post('company', true);
		$concern = $this->input->post('concern', true);
		$app_stat = $this->input->post('app_stat', true); // Current approval status
	
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				
				// Check if the app_stat is 'Pending'
				if ($app_stat == 'Pending' ) {
					// Update editable fields
					$update_process = $this->Main_model->update_tracc_concern($control_number, $module_affected, $company, $concern);
					
					if (!$update_process) {
						// Handle update error
						$this->session->set_flashdata('error', 'Error updating the ticket.');
						redirect(base_url() . "sys/users/list/tickets/tracc_concern");
					}
				}

				$ack_as_res_by = $this->input->post('ack_as_res_by', true); // Missing variable added
				$ack_as_res_date = $this->input->post('ack_as_res_date', true); // Missing variable added
	
				// If ack_as_res_by and ack_as_res_date are filled, update the status to 'Resolved'
				if (!empty($ack_as_res_by) && !empty($ack_as_res_date)) {
					$resolve_process = $this->Main_model->AcknolwedgeAsResolved($control_number);
	
					if ($resolve_process[0] == 1) {
						$this->session->set_flashdata('success', $resolve_process[1]);
					} else {
						$this->session->set_flashdata('error', $resolve_process[0]);
					}
				}
	
				// Redirect after processing
				redirect(base_url() . "sys/users/list/tickets/tracc_concern");
	
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}*/

	public function SendEmail() {
		// function email
	}

	//logout
	public function logout() {
		if ($this->session->userdata('login_data')) {
			$this->session->unset_userdata('login_data');
			redirect(base_url()."");
		} else {
			$this->session->set_flashdata('error', 'Please login first before you can access this page.');
			redirect(base_url()."");
		}
	}

	//download file 
	public function download_file($file_name) {
		// Path to the file
		$file_path = FCPATH . 'uploads/tracc_con/' . $file_name;
	
		// Check if file exists
		if (file_exists($file_path)) {
			// Load the download helper
			$this->load->helper('download');
	
			// Get the file's original name
			$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
	
			// Set the correct content type based on file extension
			switch ($file_extension) {
				case 'pdf':
					$mime_type = 'application/pdf';
					break;
				case 'jpg':
				case 'jpeg':
					$mime_type = 'image/jpeg';
					break;
				case 'png':
					$mime_type = 'image/png';
					break;
				case 'doc':
					$mime_type = 'application/msword';
					break;
				case 'docx':
					$mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
					break;
				default:
					$mime_type = 'application/octet-stream'; // Fallback for unknown types
					break;
			}
	
			// Force download
			force_download($file_path, NULL, $mime_type);
		} else {
			// Show error if file does not exist
			show_404();
		}
	}

	//checking the size of storage for upload files
	public function check_upload_size(){
		$dirs = ['uploads/tracc_concern', 'uploads/msrf', 'uploads/tracc_request']; // Directories to check
		$total_size = 0;

		foreach ($dirs as $dir) {
			$full_path = FCPATH . $dir;
			// Check if the directory exists
			if (is_dir($full_path)) {
				// Iterate through files in the directory
				$files = scandir($full_path);
				foreach ($files as $file) {
					if ($file !== '.' && $file !== '..') {
						$total_size += filesize($full_path . '/' . $file);
					}
				}
			}
		}

		// 1GB
		if ($total_size > 1 * 1024 * 1024 * 1024) {
			// Set a session variable for alert
			$this->session->set_flashdata('upload_alert', 'The total upload size exceeds the limit!');
		}
	}

	//datatable na makikita ng user sa get_tracc_request_form
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

			$data['getdept'] = $getdepartment[1];

			$data['trfNumber'] = $trfNumber; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/tracc_request_form_list', $data); 
			$this->load->view('users/footer', $data); 
	
		} else {
			$process = $this->Main_model->trf_add_ticket();

			if ($process[0] == 1){
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}

		}
	}

	//user creation of ticket
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
			$this->load->view('users/tracc_request_form_creation', $data);
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
				'checkbox_item' => $this->input->post('checkbox_item') ? 1 : 0,
				'checkbox_customer' => $this->input->post('checkbox_customer') ? 1 : 0,
				'checkbox_supplier' => $this->input->post('checkbox_supplier') ? 1 : 0,
				'checkbox_whs' => $this->input->post('checkbox_whs') ? 1 : 0,
				'checkbox_bin' => $this->input->post('checkbox_bin') ? 1 : 0,
				'checkbox_cus_ship_setup' => $this->input->post('checkbox_cus_ship_setup') ? 1 : 0,
				'checkbox_item_req_form' => $this->input->post('checkbox_item_req_form') ? 1 : 0,
				'checkbox_others_newadd' => $this->input->post('checkbox_others_newadd') ? 1 : 0, 
				'others_text_newadd' => $this->input->post('others_text_newadd')
			];

			$checkbox_data_update = [
				'checkbox_system_date_lock' => $this->input->post('checkbox_system_date_lock') ? 1 : 0,
				'checkbox_user_file_access' => $this->input->post('checkbox_user_file_access') ? 1 : 0,
				'checkbox_item_dets' => $this->input->post('checkbox_item_dets') ? 1 : 0,
				'checkbox_customer_dets' => $this->input->post('checkbox_customer_dets') ? 1 : 0,
				'checkbox_supplier_dets' => $this->input->post('checkbox_supplier_dets') ? 1 : 0,
				'checkbox_others_update' => $this->input->post('checkbox_others_update') ? 1 : 0,
				'others_text_update' => $this->input->post('others_text_update')
			]; 

			$checkbox_data_account = [
				'checkbox_tracc_orien' => $this->input->post('checkbox_tracc_orien') ? 1 : 0,
				'checkbox_create_lmi' => $this->input->post('checkbox_create_lmi') ? 1 : 0,
				'checkbox_create_lpi' => $this->input->post('checkbox_create_lpi') ? 1 : 0,
				'checkbox_create_rgdi' => $this->input->post('checkbox_create_rgdi') ? 1 : 0,
				'checkbox_create_sv' => $this->input->post('checkbox_create_sv') ? 1 : 0,
				'checkbox_gps_account' => $this->input->post('checkbox_gps_account') ? 1 : 0,
				'checkbox_others_account' => $this->input->post('checkbox_others_account') ? 1 : 0,
				'others_text_account' => $this->input->post('others_text_account')
			];


			// echo '<pre>';
			// print_r($checkbox_data_update);
			// echo '</pre>';
			// exit;
	
			$comp_checkbox_values = isset($_POST['comp_checkbox_value']) ? $_POST['comp_checkbox_value'] : [];
			$imploded_values = implode(',', $comp_checkbox_values);
	
			$process = $this->Main_model->trf_add_ticket($file_path, $imploded_values, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account);
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url() . 'sys/users/list/tickets/tracc_request');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url() . 'sys/users/create/tickets/tracc_request');
			}
		}
	}

	public function user_creation_tickets_customer_request_forms_tms() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_customer_from_tracc_req_mf_new_add();
	
		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_customer_form_request_creation', $data);
			$this->load->view('users/footer');
		} else {
			$comp_checkbox_values = isset($_POST['comp_checkbox_value']) ? $_POST['comp_checkbox_value'] : [];
			$imploded_values = implode(',', $comp_checkbox_values);
				
			$process = $this->Main_model->trf_add_ticket($imploded_values);

			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url() . 'sys/users/list/tickets/tracc_request');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url() . 'sys/users/create/tickets/tracc_request');
			}
		}
	}

	public function user_creation_customer_request_form_pdf() {
		$crf_comp_checkbox_values = isset($_POST['crf_comp_checkbox_value']) ? $_POST['crf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $crf_comp_checkbox_values);

		$checkbox_cus_req_form_del = [
			'checkbox_outright' => isset($_POST['checkbox_outright']) ? 1 : 0,
			'checkbox_consignment' => isset($_POST['checkbox_consignment']) ? 1 : 0,
			'checkbox_cus_a_supplier' => isset($_POST['checkbox_cus_a_supplier']) ? 1 : 0,
			'checkbox_online' => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_walkIn' => isset($_POST['checkbox_walkIn']) ? 1 : 0,
			'checkbox_monday' => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday' => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday' => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday' => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday' => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday' => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday' => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];
	
		$process = $this->Main_model->add_customer_request_form_pdf($imploded_values, $checkbox_cus_req_form_del);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms');  
	public function approve_ticket(){
		$id = $this->input->post('recid');
		$data_module = $this->input->post('data_module');
		if($id){
			$status = $this->Main_model->update_department_status($data_module, $id);
			if($status[0] === 1){
					echo json_encode(['status' => 'success', 'message' => 'Succesfully Updated!']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to update Department approval status.']);
				}
		}else{
			echo json_encode(['status' => 'error', 'message' => 'Failed to update Department approval status.']);
		}
	}

	
}