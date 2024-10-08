<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
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
	
	// code na may sweetalert
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
	
	/*public function login() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	
		// Simplified validation rules
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			echo '<pre>';
			print_r($this->form_validation->error_array()); // Display validation errors
			echo '</pre>';
			exit;
		} else {
			echo 'Validation passed!';
		}
	}*/

	/*public function login (){
		$this->load->view('login');
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
	
	
	// Admin Dashboard
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

				$allowed_menus = ['dashboard', 'system_administration', 'other_menu'];
				if (!in_array($active_menu, $allowed_menus)) {
		        	$active_menu = 'dashboard';
		    	}

				$data['active_menu'] = $active_menu;
									
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

	//kay maam hannah na dashboard
	//wala pang process
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
				

				//iibahin yung logic for tracc_concern
				//di pa tapos
				if ($this->input->post()) {
					// Capture form data
					$control_number = $this->input->post('control_number');
					$received_by = $this->input->post('received_by');
					$noted_by = $this->input->post('noted_by');
					$approval_stat = $this->input->post('app_stat');
					$solution = $this->input->post('solution');
					$resolved_by = $this->input->post('resolved_by');
					$resolved_date = $this->input->post('res_date');
					
					print_r($_POST); // Add this line to debug POST data
					// Pass data to the model for processing
					$process = $this->Main_model->status_approval_tracc_concern($control_number, $received_by, $noted_by, $approval_stat, $solution, $resolved_by, $resolved_date);  // Update this line in the model
	
					if ($process[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved');
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}
	
					// Redirect after form processing
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
					
					print_r($_POST); // Add this line to debug POST data
					// Pass data to the model for processing
					$process = $this->Main_model->status_approval_msrf($msrf_number, $approval_stat, $rejecttix);  // Update this line in the model
	
					if ($process[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved');
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
	
	public function admin_approval_list($subject, $id) {
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
				$data['tracc_con'] = $getTraccCon[1]; 
				$data['ict'] = $ict;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);
				$data['pages'] = 'tickets';

				$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';

				$data['active_menu'] = $active_menu;

				if ($user_role == "L2") {
					$data['getTeam'] = $getTeam[1];
				}

				if ($subject == "MSRF") {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_msrf', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');

				} else if ($subject == "TRACC_CONCERN") {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_tracc_concern', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
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
					echo json_encode(array('status' => 'error', 'message' => 'Failed to update employee.'));
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

	public function user_creation_tickets_tracc_concern() {
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
	}
	

	public function users_creation_tickets_msrf() {
		// Get the currently logged-in user's ID from session data
		$id = $this->session->userdata('login_data')['user_id'];
	
		// Load the form helper and session library
		$this->load->helper('form');
		$this->load->library('session');
	
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
			// If form validation succeeds, process the form submission (add the MSRF ticket)
	
			// Call the model function to add the new MSRF ticket to the database
			$process = $this->Main_model->msrf_add_ticket();
	
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
	
	
	//datatable na makikita ni user tracc concern
	public function service_form_tracc_concern_list() {
		$id = $this->session->userdata('login_data')['user_id']; // Fetches the user ID from the session
		$dept_id = $this->session->userdata('login_data')['dept_id']; // Fetches the department ID from the session
		
		// Load form and session libraries/helpers for validation
		$this->load->helper('form'); // Loads form helper for handling form elements
		$this->load->library('session'); // Loads session library to access session data
	
		// Set validation rule for the form input 'msrf_number'
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required'); 
		// 'msrf_number' is required; it will trigger an error if it's not present.
	
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

	public function GenerateMSRFNo() {
		$lastMSRF = $this->Main_model->getLastMSRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'MSRF-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
	}
	

	public function GenerateTRACCNo() {
		$lastMSRF = $this->Main_model->getLastTRACCNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'TRN-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
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

				// Check if the TRACC concern data was successfully fetched
				/*if ($getTraccCon[0] == "ok" && is_array($getTraccCon[1])) {
					$data['tracc_con'] = $getTraccCon[1];
				} else {
					// If no TRACC data or an error occurred, assign an empty array to avoid errors
					$data['tracc_con'] = array();
					$this->session->set_flashdata('error', 'TRACC concern data not found.');
				}*/
	
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

	public function update_status_msrf_assign() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$ticket_id = $this->input->post('msrf_number', true);
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->UpdateMSRFAssign($ticket_id);
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
	}

	//di pa tapos
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


	public function SendEmail() {
		// function email
	}

	public function logout() {
		if ($this->session->userdata('login_data')) {
			$this->session->unset_userdata('login_data');
			redirect(base_url()."");
		} else {
			$this->session->set_flashdata('error', 'Please login first before you can access this page.');
			redirect(base_url()."");
		}
	}

	
}