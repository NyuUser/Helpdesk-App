<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
	
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
			
			// Get department ID
			$dept_id = $this->input->post('dept_name');

			// Fetch department description from the model
			$department_details = $this->Main_model->get_department_details($dept_id);
			
			// Check if department details were fetched successfully
			if ($department_details[0] == "ok") {
				$department_description = $department_details[1]['dept_desc']; // Assuming 'dept_desc' is the column name
			} else {
				$department_description = ''; // Handle case if department not found
			}

			$employee_id = $this->input->post('employee_id');

			$this->db->where('emp_id', $employee_id);
			$existing_user = $this->db->get('users')->row();

			if ($existing_user) {
				$response = array (
					'status' => 'error',
					'message' => 'Employee ID is already taken!'
				);
				echo json_encode($response);
				exit;
			}
	
			$data = array(
				"emp_id" => $this->input->post('employee_id'),
				"fname" => $this->input->post('firstname'),
				"mname" => $this->input->post('middlename'),
				"lname" => $this->input->post('lastname'),
				"email" => $this->input->post('email'),
				"dept_id" => $dept_id,
            	"department_description" => $department_description,
				"position" => $this->input->post('position'),
				"username" => $this->input->post('username'),
				"password" => $hashed_password,
				"api_password" => $hashed_password,
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

	    $sql = "SELECT * FROM users WHERE BINARY username = ?";
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
				$query = $this->db->where('username', $username)->get('users')->row();

				if($query->status == 0) {
					return array(0, 'message' => 'You Account is Locked.');
				} else {
					$this->db->set('failed_attempts', 1);
					$this->db->where('username', $username);
					$this->db->update('users');
					return array(1, array('emp_id' => $emp_id, 'user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
				}
	        } else {
	            $query = $this->db->where('username', $username)->get('users');

	            if ($query->num_rows() > 0) {
	                $row = $query->row();
	                if ($row->failed_attempts == 3) {
	                    $this->db->set('status', 0);
	                    $this->db->where('username', $username);
	                    $this->db->update('users');

	                    // return array(0, array('user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
	                    return array(0, 'message' => "Your Account is Locked.");
						// return array('status' => 0, 'message' => "Your Account is Locked.");
	                } else {
	                	$attempts = $this->db->where('username', $username)->get('users')->row()->failed_attempts;
	                    $this->db->where('username', $username);
				        $this->db->set('failed_attempts', ($attempts + 1), FALSE);
				        $this->db->update('users');
				        // return array(0, "Your Username or Password is Invalid Please Try Again.");
				        return array('status' => 0, 'message' => "Your Username/Password is Invalid Please Try Again.");
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
	/*public function user_details() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$this->db->where('recid', $user_id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data was fetched.");
		}
	}*/
	

	//fetch details of the currently logged-in user from 'user' table
	public function user_details() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$this->db->where('recid', $user_id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			$user_data = $query->row_array();
			
			$this->session->set_userdata('user_role', $user_data['role']);
			return array("ok", $user_data);
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

	public function msrf_add_ticket($file_path = null) {
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
			
			 // Check if there is a file path and add it to the data array
			if ($file_path !== null) {
				$data['file'] = $file_path;
			}
			
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

	public function getLastTRFNumber() {
		$this->db->select('ticket_id');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_tracc_request');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->ticket_id;
		} else {
			return 'TRN-0000';
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

		$this->db->set(['status' => 1, 'failed_attempts => 1']);
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

	// Fetch ALL the department from DB
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
	
	public function update_department_status($data_module, $id, $data_remarks, $data_status) {
	    $id = (int) $id;  // Ensure $id is an integer
	    
	    if($data_status === "Rejected"){
	    	$this->db->set('approval_status', 'Rejected');
	    	$this->db->set('status', 'Rejected');
	    }else if($data_status === "Approved"){
	    	$this->db->set('approval_status', 'Approved');
	    	$this->db->set('status', 'Approved');
	    }else{
	    	$this->db->set('approval_status', 'Returned');
	    	$this->db->set('status', 'Returned');
	    }
	    $this->db->where('recid', $id); 
	    if(strtolower($data_module) === "tracc-concern"){
	    	$update_success = $this->db->update('service_request_tracc_concern');
	    }else if(strtolower($data_module) === "tracc-request"){
	    	$update_success = $this->db->update('service_request_tracc_request');
	    }else{
	    	$update_success = $this->db->update('service_request_msrf');
	    }

	    // Check if the update was successful based on affected rows
	    if ($update_success && $this->db->affected_rows() > 0) {
	        return array(1, "Department approval status updated successfully.");
	    } else {
	        return array(0, "Department approval status not found or update failed.");
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
	
	/*public function add_employee() {
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
		/*$data = array(
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
			'department_description' => $department,
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
	}*/

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
	
		// Get supervisor ID based on department
		$sup_id = $this->Main_model->getSupervisorByDepartment($department);
	
		// Get department details using the department ID
		list($status, $department_details) = $this->get_department_details($department);
	
		// Check if the department was found
		if ($status === "error") {
			return array(0, $department_details); // Return error message if no data found
		}
	
		// Extract department description
		$department_description = $department_details['dept_desc']; // Adjust based on your actual column name
	
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
			//'s_password' => $password,
			'api_password' => $new_password,
			//'s_api_password' => $password,
			'dept_id' => $department, // Store department ID
			'department_description' => $department_description, // Store department description
			'sup_id' => $sup_id,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'created_at' => date("Y-m-d H:i:s")
		);
	
		// Insert the data into the 'users' table
		$this->db->trans_start(); // Start transaction
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
			return array(0, "Passwords do not match.");
		}

		// Fetch department details
		$department_details = $this->get_department_details($department);  
		if ($department_details[0] == "ok") {
			$department_description = $department_details[1]['dept_desc']; 
		} else {
			return array(0, "Department not found.");
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
			'department_description' => $department_description,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'updated_at' => date("Y-m-d H:i:s")
		];
	
		// If a new password is provided, hash and update it
		if (!empty($password)) {
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);
			//$data['s_password'] = $password; // If you need to store the plain text password for some reason
			$data['api_password'] = password_hash($password, PASSWORD_DEFAULT);
			//$data['s_api_password'] = $password;
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

	public function getTicketsTRF($id){
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_tracc_request WHERE ticket_id = ". $strQry ."")){
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

	/*public function status_approval_tracc_concern() {
		$control_number = $this->input->post('control_number', true);
		$received_by = $this->input->post('received_by', true);
		$noted_by = $this->input->post('noted_by', true);
		$approval_stat = $this->input->post('app_stat', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$reject_ticket_traccCon = $this->input->post('reason_rejected', true);
		$solution = $this->input->post('tcr_solution', true);
		$resolved_by = $this->input->post('resolved_by', true);
		$resolved_date = $this->input->post('res_date', true);
		$others = $this->input->post('others', true);
		$received_by_lst = $this->input->post('received_by_lst', true);
		$date_lst = $this->input->post('date_lst', true);

		$this->db->trans_start();

		$qry = $this->db->query('SELECT * FROM service_request_tracc_concern WHERE control_number = ?', array($control_number));

		if ($qry->num_rows() > 0) {
			$row = $qry->row();

			if ($row->approval_status == "Approved") {
				$this->db->set('status', 'Open');

				if ($it_approval_stat == 'Resolved') {
					$this->db->set('it_approval_status', 'Resolved');
				} else if ($it_approval_stat == 'Closed') {
					$this->db->set('it_approval_status', 'Closed');
					$this->db->set('status', 'Closed');
				} else if($it_approval_stat == 'Rejected') {
					$this->db->set('it_approval_status', 'Rejected');
					$this->db->set('status', 'Rejected');
				} else if ($it_approval_stat == 'Approved'){
					$this->db->set('it_approval_status', 'Approved');
					$this->db->set('status', 'In Progress');
				}

				$this->db->set('it_approval_status', $it_approval_stat);
			} else {
				$this->db->set('approval_status', $approval_stat);
			}	
				$this->db->set('received_by', $received_by);
				$this->db->set('noted_by', $noted_by);
				$this->db->set('reason_reject_tickets', $reject_ticket_traccCon);
				$this->db->set('tcr_solution', $solution);
				$this->db->set('resolved_by', $resolved_by);
				$this->db->set('resolved_date', $resolved_date);
				$this->db->set('others', $others);
				$this->db->set('received_by_lst', $received_by_lst);
				$this->db->set('date_lst', $date_lst);
				$this->db->where('control_number', $control_number);
				$this->db->update('service_request_tracc_concern');

				$this->db->trans_complete();

				if ($this->db->trans_status === FALSE){
					return array(0, "Error updating ticket, please try again.");
				} else {
					return array(1, "Succesfully updated ticket: " . $control_number);
				}
		} else {
			return array(0, "Service request not found for ticket: " . $control_number);
		}
		
	}*/

	public function status_approval_tracc_concern() {
		$control_number = $this->input->post('control_number', true);
		$received_by = $this->input->post('received_by', true);
		$noted_by = $this->input->post('noted_by', true);
		$priority = $this->input->post('priority', true);
		$approval_stat = $this->input->post('app_stat', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$reject_ticket_traccCon = $this->input->post('reason_rejected', true);
		$solution = $this->input->post('tcr_solution', true);
		$resolved_by = $this->input->post('resolved_by', true);
		$resolved_date = $this->input->post('res_date', true);
		$others = $this->input->post('others', true);
		$received_by_lst = $this->input->post('received_by_lst', true);
		$date_lst = $this->input->post('date_lst', true);
	
		$this->db->trans_start();
	
		// Retrieve the ticket based on control_number
		$qry = $this->db->query('SELECT * FROM service_request_tracc_concern WHERE control_number = ?', array($control_number));
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
			
			// Update the 'approval_status' and 'it_approval_status' based on the form inputs
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');  // Update the overall status when the main approval is Rejected
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress');  // In Progress after main approval
			} else if ($approval_stat == 'Pending') {
				$this->db->set('approval_status', 'Pending');
				$this->db->set('status', 'Pending');  // Set to pending
			}
	
			// Handle the IT-specific approval status independently
			if ($it_approval_stat == 'Resolved') {
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Done');  // Set status to Resolved for IT
			} else if ($it_approval_stat == 'Closed') {
				$this->db->set('it_approval_status', 'Closed');
				$this->db->set('status', 'Closed');
			} else if ($it_approval_stat == 'Rejected') {
				$this->db->set('it_approval_status', 'Rejected');
			} else if ($it_approval_stat == 'Approved') {
				$this->db->set('it_approval_status', 'Approved');
				$this->db->set('status', 'In Progress');
			}

			if ($priority == 'Low') {
				$this->db->set('priority', 'Low');
			} else if ($priority == 'Medium') {
				$this->db->set('priority', 'Medium');
			} else if ($priority == 'High') {
				$this->db->set('priority', 'High');
			}
	
			// Set other fields from the form
			$this->db->set('received_by', $received_by);
			$this->db->set('noted_by', $noted_by);
			$this->db->set('reason_reject_tickets', $reject_ticket_traccCon);
			$this->db->set('tcr_solution', $solution);
			$this->db->set('resolved_by', $resolved_by);
			$this->db->set('resolved_date', $resolved_date);
			$this->db->set('others', $others);
			$this->db->set('received_by_lst', $received_by_lst);
			$this->db->set('date_lst', $date_lst);
	
			// Update the ticket in the database
			$this->db->where('control_number', $control_number);
			$this->db->update('service_request_tracc_concern');
	
			// Complete the transaction
			$this->db->trans_complete();
	
			if ($this->db->trans_status() === FALSE) {
				return array(0, "Error updating ticket, please try again.");
			} else {
				return array(1, "Successfully updated ticket: " . $control_number);
			}
	
		} else {
			return array(0, "Tracc Concern can not found for ticket: " . $control_number);
		}
	}
	

	public function insert_checkbox_data($checkbox_data) {
		$this->db->where('control_number', $checkbox_data['control_number']);
		$existing_data = $this->db->get('filled_by_mis');

		if ($existing_data->num_rows() > 0){
			$this->db->where('control_number', $checkbox_data['control_number']);
			return $this->db->update('filled_by_mis', $checkbox_data);
		} else {
			return $this->db->insert('filled_by_mis', $checkbox_data);
		}
	}

	public function get_checkbox_values($control_number){
		$this->db->where('control_number', $control_number);
		$query = $this->db->get('filled_by_mis');

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return [
				'for_mis_concern' => 0,
				'for_lst_concern' => 0,
				'system_error' => 0,
				'user_error' => 0
			];
		}
	}

	// public function status_approval_msrf() {
	// 	$ticket_id = $this->input->post('msrf_number', true);
	// 	$it_approval_stat = $this->input->post('it_approval_stat', true);
	// 	$assign_staff = $this->input->post('assign_to', true);
	// 	$approval_stat = $this->input->post('approval_stat', true);
	// 	$reject_reason = $this->input->post('rejecttix', true);  // Get rejection reason
		
	// 	// Start transaction
	// 	$this->db->trans_start();
		
	// 	// Retrieve ticket details
	// 	$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', array($ticket_id));

	// 	if ($qry->num_rows() > 0) {
	// 		$row = $qry->row();

	// 		// If the ticket is approved
	// 		if ($row->approval_status == "Approved") {
	// 			// Set default status for approved tickets
	// 			$this->db->set('status', 'In Progress');

	// 			// Update status based on IT approval status
	// 			if ($it_approval_stat == 'Resolved') {
	// 				$this->db->set('status', 'Closed'); // Change status to 'Closed' if resolved
	// 			} else if ($it_approval_stat == 'Rejected') {
	// 				$this->db->set('remarks_ict', $reject_reason); // Add rejection reason if rejected
	// 			}

	// 			$this->db->set('it_approval_status', $it_approval_stat);
	// 			$this->db->set('assigned_it_staff', $assign_staff);
	// 		} else {
	// 			// Update approval status if the ticket is not approved
	// 			$this->db->set('approval_status', $approval_stat);
	// 		}
			
	// 		// Update the ticket
	// 		$this->db->where('ticket_id', $ticket_id);
	// 		$this->db->update('service_request_msrf'); 

	// 		// Complete transaction
	// 		$this->db->trans_complete();

	// 		if ($this->db->trans_status() === FALSE) {
	// 			return array(0, "Error updating ticket, please try again.");
	// 		} else {
	// 			return array(1, "Successfully updated ticket: " . $ticket_id);
	// 		}
	// 	} else {
	// 		return array(0, "Service request not found for ticket: " . $ticket_id);
	// 	}
	// }

	public function status_approval_trf() {
		$ticket_id = $this->input->post('trf_number', true);
		$accomplished_by = $this->input->post('accomplished_by', true);
		$accomplished_by_date = $this->input->post('accomplished_by_date', true);
		$acknowledge_by = $this->input->post('acknowledge_by', true);
		$acknowledge_by_date =$this->input->post('ack_by_date', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$approval_stat = $this->input->post('app_stat', true);
		$reject_reason = $this->input->post('reason_rejected', true);
		$priority = $this->input->post('priority', true);

		$this->db->trans_start();

		$qry = $this->db->query('SELECT * FROM service_request_tracc_request WHERE ticket_id = ?', array ($ticket_id));

		if ($qry->num_rows() > 0){
			$row = $qry->row();

			if ($approval_stat == 'Rejected'){
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress');
			}

			if ($it_approval_stat == 'Resolved'){
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Closed');
			} else if ($it_approval_stat == 'Rejected'){
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
				$this->db->set('reason_reject_ticket', $reject_reason);
			} else if ($it_approval_stat == 'Approved'){
				$this->db->set('it_approval_status', 'Approved');
				$this->db->set('status', 'In Progress');
			}

			if ($priority == 'Low') {
				$this->db->set('priority', 'Low');
			} else if ($priority == 'Medium') {
				$this->db->set('priority', 'Medium');
			} else if ($priority == 'High') {
				$this->db->set('priority', 'High');
			}

			$this->db->set('accomplished_by', $accomplished_by);
			$this->db->set('accomplished_by_date', $accomplished_by_date);
			$this->db->set('acknowledge_by', $acknowledge_by);
			$this->db->set('acknowledge_by_date', $acknowledge_by_date);
			$this->db->set('reason_reject_ticket', $reject_reason);

			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_tracc_request');

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
	
			// Handle approval status (admin)
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected'); 
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'On Going'); 
			}
	
			
			if ($it_approval_stat == 'Resolved') {
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Closed'); 
			} else if ($it_approval_stat == 'Rejected') {
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('remarks_ict', $reject_reason); 
				$this->db->set('status', 'Rejected'); 
			} else if ($it_approval_stat == 'Approved') {
				$this->db->set('it_approval_status', 'Approved');
				$this->db->set('status', 'In Progress'); 
			}
	
			// Assign IT staff if provided
			if (!empty($assign_staff)) {
				$this->db->set('assigned_it_staff', $assign_staff);
			}
	
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf');
	
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

	/*public function GetTeam($dept_id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('dept_id', $dept_id);
		$this->db->where('role', 'L1');
	
		$query = $this->db->get(); // Execute the query
	
		// Check the result
		if ($query->num_rows() > 0) {
			return array("status" => "ok", "data" => $query->result_array());
		} else {
			return array("status" => "error", "message" => "No data was fetched.");
		}
	}*/
	

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

	/*public function UpdateMSRFAssign($ticket_id) {
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
	}*/

	public function UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$status = $this->input->post('it_status', true);
		$status_users = $this->input->post('status_users', true);
		$status_requestor = $this->input->post('status_requestor', true);
		
		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', [$ticket_id]); // Make sure to check by ticket_id
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
	
			// Determine which status to set based on the current status
			/*if ($row->status == 'In Progress') {
				$this->db->set('status', $status);
			} else if ($row->status == 'Resolved') {
				$this->db->set('status', $status_requestor);
			} else {
				$this->db->set('status', $status_users);
			}*/
			
			// Update the additional fields
			$this->db->set('date_needed', $date_needed);
			$this->db->set('asset_code', $asset_code);
			$this->db->set('category', $request_category);
			$this->db->set('specify', $specify);
			$this->db->set('details_concern', $details_concern);
	
			// Update only status and additional fields in the database
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf');
		
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $ticket_id);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error updating Keywords's status. Please try again.");
			}
		} else {
			return array(0, "Service request not found for ticket: " . $ticket_id);
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

	public function tracc_concern_add_ticket($file_path = null){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$control_number = $this->input->post('control_number');
		$module_affected = $this->input->post('module_affected');
		$company = $this->input->post('company');
		$concern = $this->input->post('details_concern');
		$reported_by = $this->input->post('name');
		$date_rep = $this->input->post('date_rep');

		$this->db->where('control_number', $control_number);
		$existing_control_number = $this->db->get('service_request_tracc_concern')->row();
		
		if($existing_control_number) {
			return array(0, "Control number already exists. Please use a different control number.");
		}

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

		 // Check if there is a file path and add it to the data array
		if ($file_path !== null) {
			$data['file'] = $file_path;
		}

		//var_dump($data);
		//exit;

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

	// public function update_tracc_concern($control_number, $module_affected, $company, $concern) {
		
	// 	$this->db->trans_start();

	// 	$this->db->set('module_affected', $module_affected);
	// 	$this->db->set('company', $company);
	// 	$this->db->set('tcr_details', $concern);
	
	// 	$this->db->where('control_number', $control_number);
	// 	$this->db->update('service_request_tracc_concern');

	// 	$this->db->trans_complete();

	// 	if ($this->db->affected_rows() > 0) {
	// 		$this->db->trans_commit();
	// 		return array(1, "Successfully Updating Tickets: " . $control_number);
	// 	} else {
	// 		$this->db->trans_rollback();
	// 		return array(0, "Error updating Keywords's status. Please try again.");
	// 	}
	// }

	public function trf_add_ticket($file_path = null, $comp_checkbox_values = null, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$trf_number = $this->input->post('trf_number', true);
		$fullname = $this->input->post('name', true);
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);
		$date_requested = $this->input->post('date_req', true);
		$date_needed = $this->input->post('date_needed', true);
		$complete_details = $this->input->post('complete_details', true);
	
		$query = $this->db->select('ticket_id')
					->where('ticket_id', $trf_number)
					->get('service_request_tracc_request');
		if($query->num_rows() > 0) {
			return array(0, "Data is Existing");
		} else {
			$data = array(
				'ticket_id' => $trf_number,
				'subject' => 'TRACC_REQUEST',
				'requested_by' => $fullname,
				'department' => $department_description,
				'department_id' => $department_id,
				'date_requested' => $date_requested,
				'date_needed' => $date_needed,
				'requested_by_id' => $user_id,
				'complete_details' => $complete_details,
				'status' => 'Open',
				'approval_status' => 'Pending',
				'it_approval_status' => 'Pending',
				'created_at' => date("Y-m-d H:i:s")
			);
	
			if ($file_path !== null) {
				$data['file'] = $file_path;
			}
	
			// Add checkbox values if available
			if ($comp_checkbox_values !== null) {
				$data['company'] = $comp_checkbox_values;
			}
	
			$this->db->trans_start();
			$query = $this->db->insert('service_request_tracc_request', $data);
	
			if ($this->db->affected_rows() > 0) {
				$checkbox_entry_newadd = [
					'ticket_id' => $trf_number,
					'item' => isset($checkbox_data_newadd['checkbox_item']) ? $checkbox_data_newadd['checkbox_item'] : 0,
					'customer' => isset($checkbox_data_newadd['checkbox_customer']) ? $checkbox_data_newadd['checkbox_customer'] : 0,
					'supplier' =>  isset($checkbox_data_newadd['checkbox_supplier']) ? $checkbox_data_newadd['checkbox_supplier'] : 0,
					'warehouse' => isset($checkbox_data_newadd['checkbox_whs']) ? $checkbox_data_newadd['checkbox_whs'] : 0,
					'bin_number' => isset($checkbox_data_newadd['checkbox_bin']) ? $checkbox_data_newadd['checkbox_bin'] : 0,
					'customer_shipping_setup' => isset($checkbox_data_newadd['checkbox_cus_ship_setup']) ? $checkbox_data_newadd['checkbox_cus_ship_setup'] : 0,
					'employee_request_form' => isset($checkbox_data_newadd['checkbox_employee_req_form']) ? $checkbox_data_newadd['checkbox_employee_req_form'] : 0,
					'others' => isset($checkbox_data_newadd['checkbox_others_newadd']) ? $checkbox_data_newadd['checkbox_others_newadd'] : 0,
					'others_description_add' => isset($checkbox_data_newadd['others_text_newadd']) ? $checkbox_data_newadd['others_text_newadd'] : ""
				];
				$this->db->insert('tracc_req_mf_new_add', $checkbox_entry_newadd);

				$checkbox_entry_update = [
					'ticket_id' => $trf_number,
					'system_date_lock' => isset($checkbox_data_update['checkbox_system_date_lock']) ? $checkbox_data_update['checkbox_system_date_lock'] : 0,
					'user_file_access' => isset($checkbox_data_update['checkbox_user_file_access']) ? $checkbox_data_update['checkbox_user_file_access'] : 0,
					'item_details' => isset($checkbox_data_update['checkbox_item_dets']) ? $checkbox_data_update['checkbox_item_dets'] : 0,
					'customer_details' => isset($checkbox_data_update['checkbox_customer_dets']) ? $checkbox_data_update['checkbox_customer_dets'] : 0,
					'supplier_details' => isset($checkbox_data_update['checkbox_supplier_dets']) ? $checkbox_data_update['checkbox_supplier_dets'] : 0,
					'employee_details' => isset($checkbox_data_update['checkbox_employee_dets']) ? $checkbox_data_update['checkbox_employee_dets'] : 0,
					'others' => isset($checkbox_data_update['checkbox_others_update']) ? $checkbox_data_update['checkbox_others_update'] : 0,
					'others_description_update' => isset($checkbox_data_update['others_text_update']) ? $checkbox_data_update['others_text_update'] : ""
				];
				$this->db->insert('tracc_req_mf_update', $checkbox_entry_update);

				$checkbox_entry_account = [
					'ticket_id' => $trf_number,
					'tracc_orientation' => isset($checkbox_data_account['checkbox_tracc_orien']) ? $checkbox_data_account['checkbox_tracc_orien'] : 0,
					'lmi' => isset($checkbox_data_account['checkbox_create_lmi']) ? $checkbox_data_account['checkbox_create_lmi'] : 0,
					'rgdi' => isset($checkbox_data_account['checkbox_create_rgdi']) ? $checkbox_data_account['checkbox_create_rgdi'] : 0,
					'lpi' => isset($checkbox_data_account['checkbox_create_lpi']) ? $checkbox_data_account['checkbox_create_lpi'] : 0,
					'sv' => isset($checkbox_data_account['checkbox_create_sv']) ? $checkbox_data_account['checkbox_create_sv'] : 0,
					'gps_account' => isset($checkbox_data_account['checkbox_gps_account']) ? $checkbox_data_account['checkbox_gps_account'] : 0,
					'others' => isset($checkbox_data_account['checkbox_others_account']) ? $checkbox_data_account['checkbox_others_account'] : 0,
					'others_description_acc' => isset($checkbox_data_account['others_text_account']) ? $checkbox_data_account['others_text_account'] : ""
				];
				$this->db->insert('tracc_req_mf_account', $checkbox_entry_account);

				$this->db->trans_commit();
				return array(1, "Successfully Created Ticket: ".$trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "There seems to be a problem when inserting new user. Please try again.");
			}
		}
	}

	public function getCheckboxDataNewAdd($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_new_add'); 
	
		if ($query->num_rows() > 0) {
			return $query->row_array(); 
		}
	
		return [];
	}

	public function getCheckboxDataUpdate($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_update');

		if ($query->num_rows() > 0){
			return $query->row_array();
		}

		return[];
	}

	public function getCheckboxDataAccount($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_account');

		if ($query->num_rows() > 0){
			return $query->row_array();
		}

		return[];
	}

	public function get_all_trf_tickets(){
		$this->db->select('ticket_id'); 
		$query = $this->db->get('service_request_tracc_request'); 
		return $query->result_array();
	}
	
	public function add_customer_request_form_pdf($crf_comp_checkbox_values = null, $checkbox_cus_req_form_del) {
		$trf_number = $this->input->post('trf_number', true);
		
		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'date' => $this->input->post('date', true),
			'customer_code' => $this->input->post('customer_code', true),
			'customer_name' => $this->input->post('customer_name', true),
			'tin_no' => $this->input->post('tin_no', true),
			'terms' => $this->input->post('terms', true),
			'customer_address' => $this->input->post('customer_address', true),
			'contact_person' => $this->input->post('contact_person', true),
			'office_tel_no' => $this->input->post('office_tel_no', true),
			'pricelist' => $this->input->post('pricelist', true),
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
			'created_at' => date("Y-m-d H:i:s"),
		);

		// Add checkbox values if available
		if ($crf_comp_checkbox_values !== null) {
			$data['company'] = $crf_comp_checkbox_values;
		}

		// Start transaction
		$this->db->trans_start();
		$this->db->insert('tracc_req_customer_req_form', $data);
		
		if ($this->db->affected_rows() > 0) {
			$checkbox_cus_req_form_del_days = [
				'ticket_id' => $trf_number,
				'outright' => isset($checkbox_cus_req_form_del['checkbox_outright']) ? $checkbox_cus_req_form_del['checkbox_outright'] : 0,
				'consignment' => isset($checkbox_cus_req_form_del['checkbox_consignment']) ? $checkbox_cus_req_form_del['checkbox_consignment'] : 0,
				'customer_is_also_a_supplier' =>  isset($checkbox_cus_req_form_del['checkbox_cus_a_supplier']) ? $checkbox_cus_req_form_del['checkbox_cus_a_supplier'] : 0,
				'online' => isset($checkbox_cus_req_form_del['checkbox_online']) ? $checkbox_cus_req_form_del['checkbox_online'] : 0,
				'walk_in' => isset($checkbox_cus_req_form_del['checkbox_walkIn']) ? $checkbox_cus_req_form_del['checkbox_walkIn'] : 0,
				'monday' => isset($checkbox_cus_req_form_del['checkbox_monday']) ? $checkbox_cus_req_form_del['checkbox_monday'] : 0,
				'tuesday' => isset($checkbox_cus_req_form_del['checkbox_tuesday']) ? $checkbox_cus_req_form_del['checkbox_tuesday'] : 0,
				'wednesday' => isset($checkbox_cus_req_form_del['checkbox_wednesday']) ? $checkbox_cus_req_form_del['checkbox_wednesday'] : 0,
				'thursday' => isset($checkbox_cus_req_form_del['checkbox_thursday']) ? $checkbox_cus_req_form_del['checkbox_thursday'] : 0,
				'friday' => isset($checkbox_cus_req_form_del['checkbox_friday']) ? $checkbox_cus_req_form_del['checkbox_friday'] : 0,
				'saturday' => isset($checkbox_cus_req_form_del['checkbox_saturday']) ? $checkbox_cus_req_form_del['checkbox_saturday'] : 0,
				'sunday' => isset($checkbox_cus_req_form_del['checkbox_sunday']) ? $checkbox_cus_req_form_del['checkbox_sunday'] : 0,
				'created_at' => date("Y-m-d H:i:s"),
			];
			$this->db->insert('tracc_req_customer_req_form_del_days', $checkbox_cus_req_form_del_days);

			// Check the second insert
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Created Customer Request Form for: " . $data['ticket_id']);
			} else {
				// Rollback if delivery days insert fails
				$this->db->trans_rollback();
				return array(0, "Error: Could not insert delivery days data.");
			}
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}

	}

	public function get_customer_from_tracc_req_mf_new_add() {
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('customer', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}


	public function add_customer_shipping_setup_pdf($css_comp_checkbox_values = null, $checkbox_cus_ship_setup) {
		$trf_number = $this->input->post('trf_number', true);
	
		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'shipping_code' => $this->input->post('shipping_code', true),
			'route_code' => $this->input->post('route_code', true),
			'customer_address' => $this->input->post('customer_address', true),
			'landmark' => $this->input->post('landmark', true),
			'window_time_start' => $this->input->post('window_time_start', true),
			'window_time_end' => $this->input->post('window_time_end', true),
			'special_instruction' => $this->input->post('special_instruction', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		if ($css_comp_checkbox_values !== null) {
			$data['company'] = $css_comp_checkbox_values;
		}
	
		$data['monday'] = isset($checkbox_cus_ship_setup['checkbox_monday']) ? $checkbox_cus_ship_setup['checkbox_monday'] : 0;
		$data['tuesday'] = isset($checkbox_cus_ship_setup['checkbox_tuesday']) ? $checkbox_cus_ship_setup['checkbox_tuesday'] : 0;
		$data['wednesday'] = isset($checkbox_cus_ship_setup['checkbox_wednesday']) ? $checkbox_cus_ship_setup['checkbox_wednesday'] : 0;
		$data['thursday'] = isset($checkbox_cus_ship_setup['checkbox_thursday']) ? $checkbox_cus_ship_setup['checkbox_thursday'] : 0;
		$data['friday'] = isset($checkbox_cus_ship_setup['checkbox_friday']) ? $checkbox_cus_ship_setup['checkbox_friday'] : 0;
		$data['saturday'] = isset($checkbox_cus_ship_setup['checkbox_saturday']) ? $checkbox_cus_ship_setup['checkbox_saturday'] : 0;
		$data['sunday'] = isset($checkbox_cus_ship_setup['checkbox_sunday']) ? $checkbox_cus_ship_setup['checkbox_sunday'] : 0;
	
		$this->db->trans_begin();
	
		$this->db->insert('tracc_req_customer_ship_setup', $data);
	
		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Created Customer Shipping Setup for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

	public function get_customer_shipping_setup_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('customer_shipping_setup', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function add_employee_request_form_pdf() {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'name' => $this->input->post('employee_name', true),
			'department' => $this->input->post('department', true),
			'position' => $this->input->post('position', true),
			'address' => $this->input->post('address', true),
			'tel_no_mob_no' => $this->input->post('tel_mobile_no', true),
			'tin_no' => $this->input->post('tin_no', true),
			'contact_no' => $this->input->post('contact_person', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		$this->db->trans_begin();

		$this->db->insert('tracc_req_employee_req_form', $data);

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Created Customer Shipping Setup for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

	public function get_employee_request_form_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('employee_request_form', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}
	

	public function save_data($table = NULL, $data = NULL)
	{
		$this->db->insert($table, $data);
		$insertId = $this->db->insert_id();
			if($insertId):
				return $insertId;
			else:
			    return "failed";
			endif;
	}
}
?>