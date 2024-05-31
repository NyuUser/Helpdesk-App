<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

	public function login() {
	    $username = trim($this->input->post('username', true));
	    $input_pw = $this->input->post('password');
	    $new_pw = substr(sha1($input_pw), 0, 200);

	    $res = $this->db->query("SELECT * FROM users WHERE username = '" . $username . "'");
	    if ($res->num_rows() > 0) {
	        $t = $res->row_array();
	        $user_id = $t['recid'];
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
	            return array(1, array('user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
	        } else {
	            $query = $this->db->where('username', $username)->get('users');

	            if ($query->num_rows() > 0) {
	                $row = $query->row();
	                if (isset($row->failed_attempts) && $row->failed_attempts == 3) {
	                    $this->db->set('status', 1);
	                    $this->db->where('username', $username);
	                    $this->db->update('users');

	                    return array(1, array('user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
	                    // return array(1, "Your Account is Locked.");
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

	public function user_details() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT * FROM users WHERE recid = ". $user_id ."")) {
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

	public function users_details_put($id) {
		if ($query = $this->db->query("SELECT * FROM users WHERE recid = ". $id ."")) {
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
					->get('msrf');
		if ($query->num_rows() > 0) {
			return array("error", "Data is Existing");
		} else {
			if ($category === "computer") {
				$spec = '';
				$categ = "High";
			} else if ($category === "printer") {
				$spec = '';
				$categ = "High";
			} else if ($category === "network") {
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
				'it_sup_id' => 1,
				'created_at' => date("Y-m-d H:i:s")
			);
			
			$this->db->trans_start();
			$query = $this->db->insert('msrf', $data);
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

	public function add_employee() {
		$emp_id = $this->input->post('emp_id', true);
		$fname = $this->input->post('fname', true);
		$mname = $this->input->post('mname', true);
		$lname = $this->input->post('lname', true);
		$email = $this->input->post('email', true);
		$department = $this->input->post('department', true);
		$position = $this->input->post('position', true);
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$cpassword = $this->input->post('cpassword', true);
		$new_password = password_hash($password, PASSWORD_DEFAULT);

		$word_preg = '';

		if (preg_match('/\bManager\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} else if (preg_match('/\bSupervisor\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} else {
			$word_preg = '';
		}

		if ($word_preg == "Manager") {
			$pos_id = "L3";
			$sup_id = '';
		} else if ($word_preg == "Supervisor") {
			$pos_id = "L2";
			$sup_id = '';
		} else {
			$pos_id = "L1";
			$sup_id = $department;
		}

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
			'role' => $pos_id,
			'status' => 1,
			'failed_attempts' => 1,
			'created_at' => date("Y-m-d H:i:s")
		);

		$query = $this->db->insert('users', $data);
		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
            return array(1, "Successfully Details Keywords's: ");
		} else {
			$this->db->trans_rollback();
            return array(0, "Error updating Keywords's status. Please try again.");
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
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$new_password = password_hash($password, PASSWORD_DEFAULT);

		$word_preg = '';

		if (preg_match('/\bManager\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} else if (preg_match('/\bSupervisor\b/i', $position, $matches)) {
			$word_preg = $matches[0];
		} else {
			$word_preg = '';
		}

		if ($word_preg == "Manager") {
			$pos_id = "L3";
			$sup_id = '';
		} else if ($word_preg == "Supervisor") {
			$pos_id = "L2";
			$sup_id = '';
		} else {
			$pos_id = "L1";
			$sup_id = $department;
		}

		$this->db->set('emp_id', $emp_id);
		$this->db->set('fname', $fname);
		$this->db->set('mname', $mname);
		$this->db->set('lname', $lname);
		$this->db->set('email', $email);
		$this->db->set('position', $position);
		$this->db->set('username', $username);
		$this->db->set('password', $new_password);
		$this->db->set('s_password', $password);
		$this->db->set('api_password', $new_password);
		$this->db->set('s_api_password', $password);
		$this->db->set('dept_id', $department);
		$this->db->set('sup_id', $sup_id);
		$this->db->set('role', $pos_id);
		$this->db->set('status', 1);
		$this->db->set('failed_attempts', 1);
		$this->db->set('updated_at', date("Y-m-d H:i:s"));
		$this->db->where('recid', $id);
		$this->db->update('users');

		if ($this->db->affected_rows() > 0) { 
            $this->db->trans_commit();
            return array(1, "Successfully Users: ". $id);
        } else { 
            $this->db->trans_rollback();
            return array(0, "Error updating Users. Please try again.");
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

	public function getTicketsMSRF($id) {
		$strQry = "'.$id.'";
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
}
?>