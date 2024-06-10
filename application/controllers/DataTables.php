<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends CI_Controller { 
	public function __construct() {
		parent::__construct();
	}

    public function employee() {
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }

        $valid_columns = array(
            0 => 'disable_date',
            1 => 'stamp'
        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if (!empty($search)) {
            $search_query = "AND (emp_id LIKE '%".$search."%' OR fname LIKE '%".$search."%' OR mname LIKE '%".$search."%' OR lname LIKE '%".$search."%' OR email LIKE '%".$search."%' OR username LIKE '%".$search."%' OR email LIKE '%".$search."%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();

        $data = array();
        $query = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $rows) { 
                $bid[] = $rows->recid;
                $emp_id[] = $rows->emp_id;
                $full_name[] = $rows->fname . ' ' . $rows->mname . ' ' . $rows->lname;
                $email[] = $rows->email;
                $position[] = $rows->position;
                $username[] = $rows->username;

                if ($rows->status == 1) {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock lock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Lock Account</button>";
                } else {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock unlock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Unlock Account</button>";
                }

                $btn_edit[] = "<a href='".base_url()."sys/admin/update/employee/".$rows->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Update Details</a>";
            }

            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array($emp_id[$i],$full_name[$i],$email[$i],$position[$i],$username[$i],$btn_action[$i].$btn_edit[$i]);
            }
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $length_count,
            "recordsFiltered"   => $length_count,
            "data"              => $data
        );
        echo json_encode($output);
        exit();
    }

	public function get_msrf_ticket() {
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        $string_emp = "'$emp_id'";
		$draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }

        $valid_columns = array(
            0 => 'disable_date',
	        1 => 'stamp'
        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if (!empty($search)) {
            $search_query = "and (ticket_id like '%".$search."%' OR requestor_name like '%".$search."%' OR subject like '%".$search."%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("SELECT * FROM service_request_msrf WHERE assigned_it_staff = ".$string_emp." ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("SELECT * FROM service_request_msrf WHERE assigned_it_staff = ".$string_emp." ".$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");
        if ($strQry->num_rows() > 0) {
        	foreach ($strQry->result() as $rows) { 
                $bid[] = $rows->recid;
                $ticket[] = $rows->ticket_id;
                $name[] = $rows->requestor_name;
                $subject[] = $rows->subject;
                $status[] = $rows->status;
                $prio[] = $rows->priority;
                $app_stat[] = $rows->approval_status;

                $label_class = '';
                if ($rows->status == 'Open') {
                    $label_class = 'label-primary';
                } else if ($rows->status == 'In Progress') {
                    $label_class = 'label-warning';
                } else if ($rows->status == 'Resolved') {
                    $label_class = 'label-success';
                } else if ($rows->status == 'Closed') { 
                    $label_class = 'label-danger';
                }

                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';

                $prio_class = '';
                if ($rows->priority == 'Low') {
                    $prio_class = 'label-primary';
                } else if ($rows->priority == 'Medium') {
                    $prio_class = 'label-warning';
                } else if ($rows->priority == 'High') {
                    $prio_class = 'label-danger';
                }

                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';

                $app_stat_class = '';
                if ($rows->approval_status == 'Approved') {
                    $app_stat_class = 'label-success';
                } else if ($rows->approval_status == 'Pending') {
                    $app_stat_class = 'label-warning';
                } else if ($rows->approval_status == 'Rejected') {
                    $app_stat_class = 'label-danger';
                }

                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
            }

            for ($i = 0; $i < count($bid); $i++) {
            	$data[] = array($ticket[$i],$name[$i],$subject[$i],$prio_label[$i], $status_label[$i],$app_stat_label[$i]);
            }
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $length_count,
            "recordsFiltered"   => $length_count,
            "data"              => $data
        );
        echo json_encode($output);
        exit();
	}


    public function all_tickets_msrf() {
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }

        $valid_columns = array(
            0 => 'disable_date',
	        1 => 'stamp'
        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if (!empty($search)) {
            $search_query = "and (ticket_id like '%".$search."%' OR requestor_name like '%".$search."%' OR subject like '%".$search."%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("SELECT * FROM service_request_msrf ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("SELECT * FROM service_request_msrf WHERE sup_id = ".$user_id." OR it_sup_id = '23-0001' OR assigned_it_staff = ".$emp_id." " .$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");
        if ($strQry->num_rows() > 0) {
        	foreach ($strQry->result() as $rows) { 
                $bid[] = $rows->recid;
                $ticket[] = $rows->ticket_id;
                $name[] = $rows->requestor_name;
                $subject[] = $rows->subject;
                $status[] = $rows->status;
                $prio[] = $rows->priority;
                $app_stat[] = $rows->approval_status;
                $it_status[] = $rows->it_approval_status;
                $assigned_it_staff[] = $rows->assigned_it_staff;

                $label_class = '';
                if ($rows->status == 'Open') {
                    $label_class = 'label-primary';
                } else if ($rows->status == 'In Progress') {
                    $label_class = 'label-warning';
                } else if ($rows->status == 'Resolved') {
                    $label_class = 'label-success';
                } else if ($rows->status == 'Closed') {
                    $label_class = 'label-danger';
                }

                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';

                $prio_class = '';
                if ($rows->priority == 'Low') {
                    $prio_class = 'label-primary';
                } else if ($rows->priority == 'Medium') {
                    $prio_class = 'label-warning';
                } else if ($rows->priority == 'High') {
                    $prio_class = 'label-danger';
                }

                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';

                $app_stat_class = '';
                if ($rows->approval_status == 'Approved') {
                    $app_stat_class = 'label-success';
                } else if ($rows->approval_status == 'Pending') {
                    $app_stat_class = 'label-warning';
                } else if ($rows->approval_status == 'Rejected') {
                    $app_stat_class = 'label-danger';
                }

                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';

                $it_stat_class = '';
                if ($rows->it_approval_status == 'Approved') {
                    $it_stat_class = 'label-success';
                } else if ($rows->it_approval_status == 'Pending') {
                    $it_stat_class = 'label-warning';
                } else if ($rows->it_approval_status == 'Rejected') {
                    $it_stat_class = 'label-danger';
                }

                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $tickets[] = "<a href='".base_url()."sys/admin/approved/".$rows->subject."/".$rows->ticket_id."'>". $rows->ticket_id ."</a>";
            }

            for ($i = 0; $i < count($bid); $i++) {
            	$data[] = array($tickets[$i],$name[$i],$subject[$i],$prio_label[$i], $status_label[$i],$app_stat_label[$i],$it_stat_label[$i]);
            }
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $length_count,
            "recordsFiltered"   => $length_count,
            "data"              => $data
        );
        echo json_encode($output);
        exit();
    }
}