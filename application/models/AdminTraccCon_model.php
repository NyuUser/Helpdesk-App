<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccCon_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

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
	
		$qry = $this->db->query('SELECT * FROM service_request_tracc_concern WHERE control_number = ?', array($control_number));
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
			
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');  
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress'); 
			} else if ($approval_stat == 'Pending') {
				$this->db->set('approval_status', 'Pending');
				$this->db->set('status', 'Pending'); 
			}
	
			if ($it_approval_stat == 'Resolved') {
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Done'); 
			} else if ($it_approval_stat == 'Closed') {
				$this->db->set('it_approval_status', 'Closed');
				$this->db->set('status', 'Closed');
			} else if ($it_approval_stat == 'Rejected') {
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
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

			if (!empty($received_by)) {
				$this->db->set('received_by', $received_by);
			}
			if (!empty($noted_by)) {
				$this->db->set('noted_by', $noted_by);
			}
			if (!empty($reject_ticket_traccCon)) {
				$this->db->set('reason_reject_tickets', $reject_ticket_traccCon);
			}
			if (!empty($solution)) {
				$this->db->set('tcr_solution', $solution);
			}
			if (!empty($resolved_by)) {
				$this->db->set('resolved_by', $resolved_by);
			}
			if (!empty($resolved_date)) {
				$this->db->set('resolved_date', $resolved_date);
			}
			if (!empty($others)) {
				$this->db->set('others', $others);
			}
			if (!empty($received_by_lst)) {
				$this->db->set('received_by_lst', $received_by_lst);
			}
			if (!empty($date_lst)) {
				$this->db->set('date_lst', $date_lst);
			}

			$this->db->where('control_number', $control_number);
			$this->db->update('service_request_tracc_concern');

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

}
?>