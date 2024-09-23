<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
	
	/*public function user_registration() {
		$password = $this->input->post('password');
		$conpassword = $this->input->post('conpassword');

		if ($password != $conpassword){
			$this->session->set_flashdata('error', $process['message']);
			redirect('sys/registration');
		}else{
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);

			$data = array(
				"emp_id" => $this->input->post('employee_id'),
				"fname" => $this->input->post('firstname'),
				"mname" => $this->input->post('middlename'),
				"lname" => $this->input->post('lastname'),
				"email" => $this->input->post('email'),
				"dept_id" => $this->input->post('dept_name'),
				"position" => $this->input->post('position'),
				"username" => $this->input->post('username'),
				"password" => $hashed_password,
				"role" => 'L1',
				'status' => 1,
				'failed_attempts' => 1,
				'created_at' => date("Y-m-d H:i:s")

			);

			$this->db->insert('users', $data);
			$this->session->set_flashdata('success', 'Registration is successful!');
			redirect('sys/registration');
		}
	}*/

	public function user_registration() {
		$password = $this->input->post('password');
		$conpassword = $this->input->post('conpassword');
	
		// Check if passwords match
		if ($password != $conpassword) {
			$response = array(
				'status' => 'error',
				'message' => 'Passwords do not match!'
			);
			echo json_encode($response);
			exit; // Prevent further execution
		} else {
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	
			$data = array(
				"emp_id" => $this->input->post('employee_id'),
				"fname" => $this->input->post('firstname'),
				"mname" => $this->input->post('middlename'),
				"lname" => $this->input->post('lastname'),
				"email" => $this->input->post('email'),
				"dept_id" => $this->input->post('dept_name'),
				"position" => $this->input->post('position'),
				"username" => $this->input->post('username'),
				"password" => $hashed_password,
				"role" => 'L1',
				'status' => 1,
				'failed_attempts' => 1,
				'created_at' => date("Y-m-d H:i:s")
			);
	
			// Insert user data into the database
			$this->db->insert('users', $data);
	
			// Check if insert was successful
			if ($this->db->affected_rows() > 0) {
				$response = array(
					'status' => 'success',
					'message' => 'Registration successful!'
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => 'Registration failed. Please try again.'
				);
			}
	
			echo json_encode($response);
			exit; // Prevent further execution
		}
	}
	

	/*public function login() {
		// Retrieve and sanitize the username from POST data
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// SQL query to fetch the user record based on the provided username
		//$sql = "SELECT * FROM users WHERE username = ?";
		//$res = $this->db->query($sql, array($username));
		$this->db->where('username', $username);
		$query = $this->db->get('users');


		
		// Debugging: Print the query result
		echo '<pre>';
		print_r($query->result_array()); // Display the query result
		echo '</pre>';
		exit; // Stop execution to check data

		// Check if the query returned any results
		if ($query->num_rows() > 0) {
			// Fetch the user data as an associative array
			$user = $query->row();
			if(password_verify($password, $user->password))
				return array(1, array(
						'emp_id' => ['emp_id'],
						'user_id' => ['recid'],
						'role' => ['role'],
						'status' => 1,
						'dept_id' => ['dept_id'],
						'sup_id' => ['sup_id']
				));
		}
	}*/
	
	public function login() {
	    $username = trim($this->input->post('username', true));
	    $input_pw = $this->input->post('password');
	    $new_pw = substr(sha1($input_pw), 0, 200);

	    $sql = "SELECT * FROM users WHERE username = ?";
		$res = $this->db->query($sql, array($username));
	    if ($res->num_rows() > 0) {
	        $t = $res->row_array();
	        $user_id = $t['recid'];
			$emp_id = $t['emp_id'];
	        $session_id = $t['api_password'];
	        $stored_pw = $t['password'];
	        $role = $t['role'];
	        $dept_id = $t['dept_id'];
			$sup_id = $t['sup_id'];
	        $sid = $this->session->session_id;

	        if (password_verify($input_pw, $stored_pw)) {
	            $this->db->set('failed_attempts', 1);
	            $this->db->where('username', $username);
	            $this->db->update('users');
	            return array(1, array('emp_id' => $emp_id, 'user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
	        } else {
	            $query = $this->db->where('username', $username)->get('users');

	            if ($query->num_rows() > 0) {
	                $row = $query->row();
	                if (isset($row->failed_attempts) && $row->failed_attempts == 3) {
	                    $this->db->set('status', 1);
	                    $this->db->where('username', $username);
	                    $this->db->update('users');

	                    return array(1, array('user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
	                    return array(1, "Your Account is Locked.");
	                } else {
	                	$attempts = $this->db->where('username', $username)->get('users')->row()->failed_attempts;
	                    $this->db->where('username', $username);
				        $this->db->set('failed_attempts', ($attempts + 1), FALSE);
				        $this->db->update('users');
				        return array(0, "Your Username/Password is Invalid Please Try Again.");
				        // return array('status' => 0, 'message' => "Your Username/Password is Invalid Please Try Again.");
	                }
	            } else {
	                return array('status' => 0, 'message' => "User not found");
	            }
	        }
	    } else {
	        log_message("error", "[Login] - Callback passed but can't retrieve data.");
	        return array('status' => 0, 'message' => "Failed to retrieve your data. Please try again or reset your account.");
	    }
	}
	
	//fetch details of the currently logged-in user from 'user' table
	public function user_details() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$this->db->where('recid', $user_id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data was fetched.");
		}
	}

	//retrieves the details of a specific user from the 'user' table based on the user ID 
	public function users_details_put($id) {
		$this->db->where('recid', $id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data was fetched.");
		}
	}

	public function delete_employee($id) {
		$this->db->where('recid', $id);
		$this->db->delete('users');

		if($this->db->affected_rows() >= 0){
			return array(1, "Employee deleted successfully.");
		}else{
			return array(0, "No changes were made to the Employee.");
		}
	}

	public function msrf_add_ticket() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$msrf_number = $this->input->post('msrf_number', true);
		$fullname = $this->input->post('name', true);
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);
		$date_req = $this->input->post('date_req', true);
		$date_need = $this->input->post('date_need', true);
		$asset_code = $this->input->post('asset_code', true);
		$category = $this->input->post('category', true);
		$specify = $this->input->post('specify', true);
		$concern = $this->input->post('concern', true);
		$sup_id = $this->input->post('sup_id', true);
		
		$query = $this->db->select('ticket_id')
					->where('ticket_id', $msrf_number)
					->get('service_request_msrf');
		if ($query->num_rows() > 0) {
			return array("error", "Data is Existing");
		} else {
			if ($category === "computer" || $category === "printer" || $category === "network") {
				$spec = '';
				$categ = "High";
			} else if ($category === "projector") {
				$spec = '';
				$categ = "Medium";
			} else if ($category === "others") {
				$spec = $specify;
				$categ = "Low";
			}

			$data = array(
				'ticket_id' => $msrf_number,
				'subject' => 'MSRF',
				'requestor_name' => $fullname,
				'department' => $department_description,
				'dept_id' => $department_id,
				'date_requested' => $date_req,
				'date_needed' => $date_need,
				'asset_code' => $asset_code,   
				'category' => $category,
				'specify' => $spec,
				'details_concern' => $concern,
				'status' => 'Open',
				'approval_status' => 'Pending',
				'priority' => $categ,
				'requester_id' => $user_id,
				'sup_id' => $sup_id,
				'it_dept_id' => 1,
				'it_sup_id' => '23-0001',
				'it_approval_status' => 'Pending',
				'created_at' => date("Y-m-d H:i:s")
			);
			
			$this->db->trans_start();
			$query = $this->db->insert('service_request_msrf', $data);
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Created Ticket: ".$msrf_number."");
			} else {
				$this->db->trans_rollback();
				return array(0, "There seems to be a problem when inserting new user. Please try again.");
			}
		}
	}

	public function getLastMSRFNumber() {
		$this->db->select('ticket_id');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_msrf');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->ticket_id;
		} else {
			return 'MSRF-000';
		}
	}

	public function getLastTRACCNumber() {
		$this->db->select('ticket_id');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_tracc');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->ticket_id;
		} else {
			return 'TRN-000';
		}
	}

	public function getDepartmentUsers() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT * FROM department WHERE recid = ". $user_id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function UsersDepartment($id) {
		if ($query = $this->db->query("SELECT * FROM departments WHERE recid = ". $id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function department() {
        $this->db->select('recid, dept_desc');
        $this->db->from('department');
        $query = $this->db->get();
        return $query->result_array();
	}

	public function updated_lock() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$id = $this->input->post('id', true);

		$this->db->set('status', 0);
		$this->db->where('recid', $id);
		$this->db->update('users');

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Details Keywords's: ". $id);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error updating Keywords's status. Please try again.");
		}
	}

	public function updated_unlock() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$id = $this->input->post('id', true);

		$this->db->set('status', 1);
		$this->db->where('recid', $id);
		$this->db->update('users');

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Details Keywords's: ". $id);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error updating Keywords's status. Please try again.");
		}
	}

	public function getSupervisorByDepartment($dept_id) {
		$this->db->select('sup_id');
		$this->db->from('departments');
		$this->db->where('recid', $dept_id);
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->row()->sup_id; 
		} else {
			return null;
		}
	}
	
	public function get_departments(){
		$qry = $this->db->get('departments');
		return $qry->result_array();
	}

	public function getDepartment() {
		$query = $this->db->query("SELECT * FROM departments");
	
		if ($query) {
			if ($query->num_rows() > 0) { 
				$departments = $query->result_array();
				return array("ok", $departments);
			} else { 
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function add_department(){		
		$this->db->trans_begin();
	
		$dept_desc = $this->input->post('dept_desc', true);
		$manager_id = $this->input->post('manager_id', true);
		$sup_id = $this->input->post('sup_id', true);
	
		$data = array(
			'dept_desc' => $dept_desc,
			'manager_id' => $manager_id,
			'sup_id' => $sup_id
		);
	
		$query = $this->db->insert('departments', $data);
	
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback(); 
			return array(0, "Error adding department. Please try again."); 
		} else {
			$this->db->trans_commit();  
			return array(1, "Successfully added department."); 
		}
	}

	public function get_department_details($id) {
		$id = (int) $id;
	
		// Fetch the department details
		$this->db->where('recid', $id);
		$query = $this->db->get('departments');
	
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data found.");
		}
	}
	
	public function update_department($data, $id) {
		// Ensure $id is an integer for security
		$id = (int) $id;
	
		// Check if there are any fields to update
		if (empty($data)) {
			return array(0, "No data provided for update.");
		}
	
		// Update the department record
		$this->db->where('recid', $id);
		$this->db->update('departments', $data);
	
		// Check if the update was successful
		if ($this->db->affected_rows() > 0) {
			return array(1, "Department updated successfully.");
		} else {
			// If no rows were affected, check if the ID exists
			$this->db->where('recid', $id);
			$query = $this->db->get('departments');
			
			if ($query->num_rows() > 0) {
				// ID exists but no changes were made
				return array(0, "No changes were made.");
			} else {
				// ID does not exist
				return array(0, "Department not found.");
			}
		}
	}
	
	
	public function delete_department($id) {
        $this->db->where('recid', $id);
        $this->db->delete('departments');
    
        if ($this->db->affected_rows() >= 0) {
            return array(1, "Department deleted successfully.");
        } else {
            return array(0, "No changes were made to the department.");
        }
    }
	
	public function add_employee() {
		// Retrieve form data with XSS filtering
		$emp_id = $this->input->post('emp_id', true);
		$fname = $this->input->post('fname', true);
		$mname = $this->input->post('mname', true);
		$lname = $this->input->post('lname', true);
		$email = $this->input->post('email', true);
		$department = $this->input->post('department', true);
		$position = $this->input->post('position', true);
		$role = $this->input->post('role', true);
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$cpassword = $this->input->post('cpassword', true);

		if (!empty($password) && $password !== $cpassword) {
			return array(0, "Password and Confirm Password do not match.");
		}
		
		// Hash the password for secure storage
		$new_password = password_hash($password, PASSWORD_DEFAULT);

		$sup_id = $this->Main_model->getSupervisorByDepartment($department);
		/*
		// Initialize a variable for role detection
		$word_preg = '';

		// Check if the position contains the word "Manager"
		if (preg_match('/\bManager\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} 
		// Check if the position contains the word "Supervisor"
		else if (preg_match('/\bSupervisor\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} 
		// If neither, set it to an empty string
		else {
			$word_preg = '';
		}

		// Determine the role ID and supervisor ID based on the position
		if ($word_preg == "Manager") {
			$pos_id = "L3";   // Manager level
			$sup_id = '';      // No supervisor for Managers
		} else if ($word_preg == "Supervisor") {
			$pos_id = "L2";   // Supervisor level
			$sup_id = '';      // No supervisor for Supervisors
		} else {
			$pos_id = "L1";   // Regular employee level
			$sup_id = $department; // Set supervisor ID to department
		}*/

		// Prepare data for insertion into the database
		$data = array(
			'emp_id' => $emp_id,
			'fname' => $fname, 	
			'mname' => $mname,
			'lname' => $lname,
			'email' => $email,
			'position' => $position,
			'username' => $username,
			'password' => $new_password,
			's_password' => $password,
			'api_password' => $new_password,
			's_api_password' => $password,
			'dept_id' => $department,
			'sup_id' => $sup_id,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'created_at' => date("Y-m-d H:i:s")
		);

		// Insert the data into the 'users' table
		$query = $this->db->insert('users', $data);

		// Check if the insert operation was successful
		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();  // Commit the transaction
			return array(1, "Successfully added employee."); // Return success message
		} else {
			$this->db->trans_rollback();  // Rollback the transaction
			return array(0, "Error adding employee. Please try again."); // Return error message
		}
	}

	public function update_employee() {
		$id = $this->input->post('id', true);
		$emp_id = $this->input->post('emp_id', true);
		$fname = $this->input->post('fname', true);
		$mname = $this->input->post('mname', true);
		$lname = $this->input->post('lname', true);
		$email = $this->input->post('email', true);
		$department = $this->input->post('department', true);
		$position = $this->input->post('position', true);
		$role = $this->input->post('role', true);
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$cpassword = $this->input->post('cpassword', true);
		
		if (!empty($password) && $password !== $cpassword) {
			return array(0, "Password and Confirm Password do not match.");
		}

		// Start a transaction
		$this->db->trans_start();
	
		// Prepare update data
		$data = [
			'emp_id' => $emp_id,
			'fname' => $fname,
			'mname' => $mname,
			'lname' => $lname,
			'email' => $email,
			'position' => $position,
			'username' => $username,
			'dept_id' => $department,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'updated_at' => date("Y-m-d H:i:s")
		];
	
		// If a new password is provided, hash and update it
		if (!empty($password)) {
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);
			$data['s_password'] = $password; // If you need to store the plain text password for some reason
			$data['api_password'] = password_hash($password, PASSWORD_DEFAULT);
			$data['s_api_password'] = $password;
		}
	
		// Set supervisor ID if applicable (Make sure to define `$sup_id` logic)
		// $data['sup_id'] = $sup_id;
	
		// Perform the update
		$this->db->where('recid', $id);
		$this->db->update('users', $data);
	
		// Complete the transaction
		$this->db->trans_complete();

		// Check the status of the transaction
		if ($this->db->trans_status() === FALSE) {
			return array(0, "Error updating Users. Please try again.");
		} elseif ($this->db->affected_rows() > 0) {
			return array(1, "Successfully updated User ID: ". $id);
		} else {
			// No rows were updated because no data changed
			return array(1, "No changes were made for User ID: " . $id);
		}
	}
	

	public function getSupervisorId($recid) {
		$query = $this->db->select('sup_id')
						->where('recid', $recid)
						->get('users');
		if ($query->num_rows() == 1) {
			$result = $query->row_array();
			return $result['sup_id'];
		} else {
			return false;
		}
	}

	public function getTraccConcernByID($id) {
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE control_number = " . $strQry ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t); 
			} else {
				return array("error", "No data was fetched.");
			}
		}else{
			return array("error", "Internal error. Please try again.");
		}
	}
	
	public function getTicketsMSRF($id) {
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_msrf WHERE ticket_id = ". $strQry ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function status_approval_tracc_concern() {
		
	}

	public function status_approval_msrf() {
		$ticket_id = $this->input->post('msrf_number', true);
		$it_approval_stat = $this->input->post('it_approval_stat', true);
		$assign_staff = $this->input->post('assign_to', true);
		$approval_stat = $this->input->post('approval_stat', true);
		$reject_reason = $this->input->post('rejecttix', true);  // Get rejection reason
		
		// Start transaction
		$this->db->trans_start();
		
		// Retrieve ticket details
		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', array($ticket_id));

		if ($qry->num_rows() > 0) {
			$row = $qry->row();

			// If the ticket is approved
			if ($row->approval_status == "Approved") {
				// Set default status for approved tickets
				$this->db->set('status', 'In Progress');

				// Update status based on IT approval status
				if ($it_approval_stat == 'Resolved') {
					$this->db->set('status', 'Closed'); // Change status to 'Closed' if resolved
				} else if ($it_approval_stat == 'Rejected') {
					$this->db->set('remarks_ict', $reject_reason); // Add rejection reason if rejected
				}

				$this->db->set('it_approval_status', $it_approval_stat);
				$this->db->set('assigned_it_staff', $assign_staff);
			} else {
				// Update approval status if the ticket is not approved
				$this->db->set('approval_status', $approval_stat);
			}
			
			// Update the ticket
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf'); 

			// Complete transaction
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				return array(0, "Error updating ticket, please try again.");
			} else {
				return array(1, "Successfully updated ticket: " . $ticket_id);
			}
		} else {
			return array(0, "Service request not found for ticket: " . $ticket_id);
		}
	}

	 /*public function status_approval_msrf() {
	 	$ticket_id = $this->input->post('msrf_number', true);
	 	$approval_stat = $this->input->post('approval_stat', true);

	 	$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = '. $ticket_id .'');
		
	 	if ($approval_status == "Approved") {
	 		$this->db->set('it_approval_status', $approval_stat);
			$this->db->where('ticket_id', $ticket_id);
	 		$this->db->update('service_request_msrf');

	 		if ($this->db->affected_rows() > 0) {
	 			$this->db->trans_commit();
	 			return array(1, "Successfully Update Tickets's: ". $ticket_id);
	 		} else {
	 			$this->db->trans_rollback();
	 			return array(0, "Error updating Tickets's, Please try again.");
	 		}
	 	} else {
	 		$this->db->set('approval_status', $approval_stat);
	 		$this->db->where('ticket_id', $ticket_id);
	 		$this->db->update('service_request_msrf');

	 		if ($this->db->affected_rows() > 0) {
	 			$this->db->trans_commit();
	 			return array(1, "Successfully Update Tickets's: ". $ticket_id);
	 		} else {
				$this->db->trans_rollback();
				return array(0, "Error updating Tickets's, Please try again.");
	 		}
	 	}
	 }*/

	public function GetICTSupervisor() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT a.emp_id FROM users a JOIN departments b ON a.emp_id = b.sup_id WHERE a.recid = ". $user_id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function GetTeam($dept_id) {
		if ($query = $this->db->query("SELECT * FROM users WHERE dept_id = ". $dept_id ." AND role = 'L1'")) {
			if ($query->num_rows() > 0) {
				return array("ok", $query->result_array());
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function GetDepartmentID() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT * FROM users WHERE recid = ".$user_id."")) {
			if ($query->num_rows() > 0) {
				return array("ok", $query->result_array());
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function AcknolwedgeAsResolved($control_number){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$ack_resolved = $this->input->post('ack_as_res_by', true);
		$ack_resolved_date = $this->input->post('ack_as_res_date', true);

		$data = array(
			'ack_as_resolved' => $ack_resolved,
			'ack_as_resolved_date' => $ack_resolved_date,
			'status' => 'Resolved'
		);

		$this->db->where('control_number', $control_number);
		$this->db->update('service_request_tracc_concern', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Ticket acknolwedge as resolved.');
		} else {
			$this->session->set_flashdata('error', 'Error acknowledging ticket as resolved.');
		}

		redirect(base_url(). "sys/users/list/tickets/tracc_concern");

	}

	public function UpdateMSRFAssign($ticket_id) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$status = $this->input->post('it_status', true);
		$status_users = $this->input->post('status_users', true);
		$status_requestor = $this->input->post('status_requestor', true);
		
		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE status = "In Progress" OR status = "On going" OR status = "Resolved"');

		if ($qry->num_rows() > 0) {
			$row = $qry->row();

			// Check if status is 'On going'
			if ($row->status == 'In Progress') {
				$this->db->set('status', $status);
			} else if ($row->status == 'Resolved') {
				$this->db->set('status', $status_requestor);
			} else {
				$this->db->set('status', $status_users);
			}
		
			// Update only status in the database
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf');
		
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: ". $ticket_id);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error updating Keywords's status. Please try again.");
			}

		} else {
			return array(0, "Service request not found for ticket: " . $status);
		}
	}

	//kinukuha yung name ng it assigned keysa id ang dinidisplay
	/*public function getTicketWithAssignedIT($ticket_id){
		$this->db->select('msrf.ticket_id, CONCAT(users.fname, " ", users.mname, " ", users.lname) as assigned_it_name');
		$this->db->from('service_request_msrf as msrf');
		$this->db->join('users', 'msrf.assigned_it_staff = users.recid', 'left');  // Join with the users table
		$this->db->where('msrf.ticket_id', $ticket_id);
		$query = $this->db->get();
	
		return $query->row_array();  // Return the result as an array
	}*/

	public function get_total_users(){
		return $this->db->count_all('users');
	}

	public function get_total_msrf_ticket(){
		$this->db->from('service_request_msrf');
		$this->db->where_in('status', ['In Progress', 'Open']);
		return $this->db->count_all_results();
	}

	public function get_total_departments(){
		return $this->db->count_all('departments');
	}

	public function get_total_tracc_concern_ticket(){
		$this->db->from('service_request_tracc_concern');
		$this->db->where_in('status', ['In Progress', 'Open']);
		return $this->db->count_all_results();
	}

	public function tracc_concern_add_ticket(){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$control_number = $this->input->post('control_number');
		$module_affected = $this->input->post('module_affected');
		$company = $this->input->post('company');
		$concern = $this->input->post('concern');
		$reported_by = $this->input->post('name');
		$date_rep = $this->input->post('date_rep');

		$data = array(
			'control_number' => $control_number,
			'subject' => 'TRACC_CONCERN',
			'module_affected' => $module_affected,
			'company' => $company,
			'tcr_details' => $concern,
			'reported_by' => $reported_by,
			'reported_date' => $date_rep,
			'status' => 'Open',
			'approval_status' => 'Pending',
			'it_approval_status' => 'Pending',
			'reported_by_id' => $user_id,
			'created_at' => date("Y-m-d H:i:s")
		);

		$this->db->trans_start();
		$query = $this->db->insert('service_request_tracc_concern', $data);
		if ($this->db->affected_rows() > 0){
			$this->db->trans_commit();
			return array(1, "Successfully Created Ticket: ".$control_number."");
		}else{
			$this->db->trans_rollback();
			return array(0, "There seems to be a problem when inserting new ticket. Please try again.");
		}
	}
	
}
?>