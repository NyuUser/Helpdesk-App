<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
	}

	public function login() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[2]|max_length[150]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[12]');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('login');
		} else {
			$process = $this->Main_model->login();
			if ($process[0] == 1 && $process[1]['status'] == 1) {
				$role = $process[1]['role'];
				
				if ($role == "L2") {
					$this->session->set_flashdata('success', $process[1]);
					$this->session->set_userdata(array('login_data' => $process[1]));
					redirect(base_url().'sys/admin/dashboard');
				} else {
					$this->session->set_flashdata('success', $process[1]);
					$this->session->set_userdata(array('login_data' => $process[1]));
					redirect(base_url().'sys/users/dashboard');
				}
			} else {
				$this->session->set_flashdata('error', $process['message']);
	            redirect("sys/authentication");
			}
		}
	}

	// Admin Dashboard
	public function admin_dashboard($active_menu = 'dashboard') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

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
				$this->load->view('admin/setup_team', $data);
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

	public function admin_list_tickets() {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$data['active_menu'] = $active_menu;

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

	public function admin_list_employee() {
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
				$this->load->view('admin/add_employee', $data);
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

	public function employee_add() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->form_validation->set_rules('emp_id', 'Employee ID', 'trim|required');
		$user_details = $this->Main_model->user_details();

		if ($this->form_validation->run() == FALSE) {
			$sid = $this->session->session_id;
			$data['user_details'] = $user_details[1];

			$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

			$data['active_menu'] = $active_menu;

			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/add_employee', $data);
			$this->load->view('admin/footer');
		} else {
			$process = $this->Main_model->add_employee();
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url()."sys/admin/users");
			} else {
				$this->session->set_flashdata('error', $process[0]);
				redirect(base_url()."sys/admin/users");
			}
		}
	}

	public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_role = $this->session->userdata('login_data')['role'];
			$user_dept = $this->session->userdata('login_data')['sup_id'];
			$dept_id = $this->session->userdata('login_data')['dept_id'];
			$user_details = $this->Main_model->user_details();
			$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
			$ict = $this->Main_model->GetICTSupervisor();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['msrf'] = $msrf_tickets[1];
				$data['ict'] = $ict;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);

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

				} else if ($subject == "TRACC") {
					$this->load->view('admin/header', $data);
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

	public function employee_update() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$id = $this->input->post('id', true);
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
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
				    $data['departments'] = $department_data[1];
				} else {
				    $data['departments'] = array();
				    echo "No departments found.";
				}

				$process = $this->Main_model->update_employee();
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
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}

	public function list_update_employee($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();
			$users_det = $this->Main_model->users_details_put($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['users_det'] = $users_det[1];

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				if ($department_data[0] == "ok") {
				    $data['departments'] = $department_data[1];
				} else {
				    $data['departments'] = array();
				    echo "No departments found.";
				}

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/update_employee', $data);
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

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$this->load->view('users/dashboard', $data);
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

	public function users_creation_tickets_msfr() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
        $this->load->library('session');
        $this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required');
        $user_details = $this->Main_model->user_details();
        $department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);

        if ($this->form_validation->run() == FALSE) {
        	$msrfNumber = $this->GenerateMSRFNo();
        	$data['user_details'] = $user_details[1];
        	$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];

			if ($department_data[0] == "ok") {
				$data['departments'] = $department_data[1];
			} else {
				$data['departments'] = array();
				echo "No departments found.";
			}
        	$data['msrfNumber'] = $msrfNumber;
        	$this->load->view('users/create_tickets', $data);
        } else {
			$process = $this->Main_model->msrf_add_ticket();
			if ($process[0] == 1) {
				$success_script = '<script>$(document).ready(function(){ $("#modal-success-users").modal("show"); });</script>';
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url()."sys/users/dashboard?success=".urlencode($success_script));
			} else {
				$error_script = '<script>$(document).ready(function(){ $("#modal-error-users").modal("show"); });</script>';
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url()."sys/users/dashboard?error=".urlencode($error_script));
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

	public function GenerateTRACCNo() {
		$lastMSRF = $this->Main_model->getLastTRACCNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'TRN-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
	}

	public function SendEmail() {
		// function email
	}

	public function logout() {
		if ($this->session->userdata('login_data')) {
			$this->session->unset_userdata('login_data');
			redirect(base_url()."sys/authentication");
		} else {
			$this->session->set_flashdata('error', 'Please login first before you can access this page.');
			redirect(base_url()."sys/authentication");
		}
	}
}