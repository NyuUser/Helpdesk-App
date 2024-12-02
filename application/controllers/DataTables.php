<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends CI_Controller { 
    public function __construct() {
        parent::__construct();
    }

    //Datatable ng Admin
    public function employee() {
        // Fetch request parameters sent by DataTables
        $draw = intval($this->input->post("draw"));  // The draw counter for DataTables (helps in ensuring AJAX requests are correctly processed)
        $start = intval($this->input->post("start"));  // The starting point for records (used for pagination)
        $length = intval($this->input->post("length"));  // The number of records to return (used for pagination)
        $order = $this->input->post("order");  // The column and direction for sorting
        $search = $this->input->post("search");  // The search value entered in the DataTables search box
        $search = $this->db->escape_str($search['value']);  // Escaping the search value to prevent SQL injection
    
        // Initialize variables for column index and direction
        $col = 0;  // Default column index for sorting
        $dir = "";  // Default sorting direction
    
        // Process the order array to get the column index and sort direction
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  // Column index (as sent by DataTables)
                $dir = $o['dir'];  // Sorting direction ('asc' or 'desc')
            }
        }
    
        // Validate the sort direction
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  // Default to ascending if an invalid direction is provided
        }
    
        // Define an array of valid columns that can be used for sorting
        $valid_columns = array(
            0 => 'disable_date',
            1 => 'stamp'
        );
    
        // Validate the selected column index, if invalid, set $order to null
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  // Set the column name based on the column index
        }
    
        // Enhance the search query
        // If a search term is provided, construct the search query
        if (!empty($search)) {
            $search_query = "WHERE (emp_id LIKE '%".$search."%' OR fname LIKE '%".$search."%' OR mname LIKE '%".$search."%' OR lname LIKE '%".$search."%' OR email LIKE '%".$search."%' OR username LIKE '%".$search."%' OR role LIKE '%".$search."%' OR department_description LIKE '%".$search."%')";
        } else {
            // If no search term, add a dummy condition to simplify SQL syntax
            $search_query = "WHERE 1=1";  // Ensures the query remains valid when no search term is provided
        }
    
        // Get the total count of records matching the search criteria
        $count_array = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();  // Get the total number of rows for pagination
    
        // Initialize an empty array to hold the data that will be returned
        $data = array();
    
        // Fetch the data with the applied search, sorting, and pagination
        $query = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");
    
        // Check if any records are returned from the query
        if ($query->num_rows() > 0) {
            // Loop through each row of the result set
            foreach ($query->result() as $rows) { 
                // Collecting data for each column to be displayed
                $bid[] = $rows->recid;
                $emp_id[] = $rows->emp_id;
                $full_name[] = $rows->fname . ' ' . $rows->mname . ' ' . $rows->lname;  // Concatenate first, middle, and last names
                $email[] = $rows->email;
                $position[] = $rows->position;
                $username[] = $rows->username;
                $role[] = $rows->role;
    
                // Create action buttons (lock/unlock and update details) based on the status
                if ($rows->status == 1) {
                    // If account is active, show the "Lock Account" button
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock lock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Lock Account</button>";
                } else {
                    // If account is inactive, show the "Unlock Account" button
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock unlock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Unlock Account</button>";
                }
    
                // Add the "Update Details" button
                $btn_edit[] = "<a href='".base_url()."sys/admin/update/employee/".$rows->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Update Details</a>";
                $btn_delete[] = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#UsersDeleteModal' data-id='".$rows->recid."'>
                 <i class='fa fa-trash'></i> Delete</button>";

            }
    
            // Combine all columns' data into a single row for each employee
            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array($emp_id[$i], $full_name[$i], $email[$i], $position[$i], $username[$i], $role[$i], $btn_action[$i] . $btn_edit[$i] . $btn_delete[$i]);
            }
        }
    
        // Prepare the output for DataTables
        $output = array(
            "draw"              => $draw,  // Echo back the draw counter received from DataTables
            "recordsTotal"      => $length_count,  // Total records in the result set (with search)
            "recordsFiltered"   => $length_count,  // Total filtered records (with search)
            "data"              => $data  // Data array that contains all the rows to be displayed
        );
        
        // Return the output as a JSON response
        echo json_encode($output);
        exit();  // Ensure no further processing happens
    }

    //Datatable ng Admin
    public function all_departments() {
        // Fetching session data for `user_id and emp_id
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        
        // Retrieving DataTables parameters from the POST request
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);  // Escaping the search input to prevent SQL injection
        
        // Default sorting direction
        $dir = "asc";
        $col = 0;
    
        // Determine the column and direction for ordering
        if (!empty($order)) {
            $col = $order[0]['column'];
            $dir = $order[0]['dir'];
        }
    
        // Validate the direction, default to 'asc' if invalid
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }
    
        // Define valid columns that exist in the 'departments' table for ordering
        $valid_columns = array(
            0 => 'recid',         // Ensure these columns exist in your 'departments' table
            1 => 'dept_desc',
            2 => 'manager_id',
            3 => 'sup_id'
        );
    
        // Determine the column to order by, default to 'recid'
        $order = isset($valid_columns[$col]) ? $valid_columns[$col] : 'recid';
    
        // Build the search query if a search term is provided
        $search_query = "";
        if (!empty($search)) {
            $search_query = "WHERE (recid LIKE '%".$search."%' OR dept_desc LIKE '%".$search."%' OR manager_id LIKE '%".$search."%' OR sup_id LIKE '%".$search."%')";
        }
    
        // Get the total number of records in the 'departments' table
        $count_query = $this->db->query("SELECT COUNT(*) AS count FROM departments");
        $total_records = $count_query->row()->count;
    
        // Get the total number of records that match the search criteria
        $filtered_query = $this->db->query("SELECT COUNT(*) AS count FROM departments " . $search_query);
        $filtered_records = $filtered_query->row()->count;
    
        // Fetch the records based on search, order, and pagination parameters
        $data_query = $this->db->query("SELECT * FROM departments " . $search_query . " ORDER BY " . $order . " " . $dir . " LIMIT " . $start . ", " . $length);
    
        $data = array();
    
        // Populate the data array with the results and add action buttons
        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $row) {
                // Create Edit and Delete buttons
                $btn_edit = "<a href='".base_url()."sys/admin/update/department/".$row->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Edit</a>";
                $btn_delete = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#deleteModal' data-id='".$row->recid."'>
               <i class='fa fa-trash'></i> Delete</button>";


                //$btn_delete = "<button class='btn btn-danger btn-sm btn-delete' data-deptid='".$row->recid."'><i class='fa fa-trash'></i> Delete</button>";
                
                // Combine the department data with the action buttons
                $data[] = array(
                    $row->recid,
                    $row->dept_desc,
                    $row->manager_id,
                    $row->sup_id,
                    $btn_edit . " " . $btn_delete
                );
            }
        }
    
        // Prepare the output for DataTables
        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $total_records,
            "recordsFiltered"   => $filtered_records,
            "data"              => $data
        );
        
        // Return the output as JSON
        echo json_encode($output);
        exit();
    }

    //Datatable ng User
    public function get_msrf_ticket() {
        // Retrieve user data from session
        $user_id = $this->session->userdata('login_data')['user_id'];  // Get the user ID from the session
        $emp_id = $this->session->userdata('login_data')['emp_id'];  // Get the employee ID from the session
        //$dept_desc = $this->session->userdata('login_data')['dept_desc'];  // Assuming dept_desc is in session
        //echo $emp_id;
        $string_emp = $this->db->escape($emp_id);  // Properly escape the employee ID to prevent SQL injection
        // Retrieve DataTables request parameters
        $draw = intval($this->input->post("draw"));  // The draw counter for DataTables (used to ensure AJAX requests are correctly processed)
        $start = intval($this->input->post("start"));  // The starting point for records (used for pagination)
        $length = intval($this->input->post("length"));  //0 The number of records to return (used for pagination)
        $order = $this->input->post("order");  // The column and direction for sorting
        $search = $this->input->post("search");  // The search value entered in the DataTables search box
        $search = $this->db->escape_str($search['value']);  // Escape the search value to prevent SQL injection
    
        // Initialize variables for column index and direction
        $col = 0;  // Default column index for sorting
        $dir = "";  // Default sorting direction
    
        // Process the order array to get the column index and sort direction
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  // Column index (as sent by DataTables)
                $dir = $o['dir'];  // Sorting direction ('asc' or 'desc')
            }
        }
    
        // Validate the sort direction
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  // Default to ascending if an invalid direction is provided
        }
    
        // Define an array of valid columns that can be used for sorting
        $valid_columns = array(
            0 => 'disable_date',  // Example column for sorting
            1 => 'stamp'  // Example column for sorting
        );
    
        // Validate the selected column index, if invalid, set $order to null
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  // Set the column name based on the column index
        }

        $search_query = "AND assigned_it_staff = ".$string_emp;
        // Enhance the search query
        if (!empty($search)) {
            // If a search term is provided, construct the search query
            $search_query .= "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%')";
        } 
        //else {
            // If no search term, leave the search query empty
        //    $search_query = "";
       // }
    
        // Count total records excluding 'Closed' status

       
        $sess_login_data = $this->session->userdata('login_data');
        $role = $sess_login_data['role'];

        if($role === "L1"){
            $query = "status IN ('Open', 'In Progress', 'On going', 'Resolved') ". $search_query;
            $select = "recid, status, approval_status, it_approval_status, priority, ticket_id, subject, requestor_name, department, dept_id, date_requested";
            $result_data = $this->Tickets_model->get_data_list("service_request_msrf", $query, 999,0, $select, null, null, null, null);

            $length_count = count($result_data);

        }else{
            // Count total records excluding 'Closed' status
            $count_array = $this->db->query("
                SELECT * FROM service_request_msrf 
                WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = " . $string_emp . ") 
                OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND requester_id = " . $user_id . ") " 
                . $search_query
            );
            $length_count = $count_array->num_rows();  // Get the total number of rows for pagination
        
            // Fetch records excluding 'Closed' status
            $data = array();
            $strQry = $this->db->query("
                SELECT * FROM service_request_msrf 
                WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = " . $string_emp . ") 
                OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND requester_id = " . $user_id . ") " 
                . $search_query . 
                " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
            );
            $result_data = $strQry->num_rows();
        }

    
        /*$strQry = $this->db->query("
            SELECT * FROM service_request_msrf 
            WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved') 
            AND requester_id = " . $user_id . " " 
            . $search_query . 
            " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        ); */

        // Check if any records are returned from the query
        $tickets = [];
        $data = [];
        if ($result_data > 0) {
            // Loop through each row of the result set
            foreach ($result_data as $rows) {

                // Determine the appropriate label class for the status
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                    case 'On going':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed':  // This case should never be hit because 'Closed' status tickets are excluded
                        $label_class = 'label-danger';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'On Going':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-warning';
                        break;
                }
                // Store the status label
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Determine the appropriate label class for the priority
                $prio_class = '';
                switch ($rows->priority) {
                    case 'Low':
                        $prio_class = 'label-primary';
                        break;
                    case 'Medium':
                        $prio_class = 'label-warning';
                        break;
                    case 'High':
                        $prio_class = 'label-danger';
                        break;
                }
                // Store the priority label
                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';
    
                // Determine the appropriate label class for the department approval status
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-warning';
                        break;
                }
                // Store the department approval status label
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // Determine the appropriate label class for the ICT approval status
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                }
                // Store the ICT approval status label
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
                // Store the ticket ID as a clickable link to the details page
                $tickets[] = "<a href='" . base_url() . "sys/users/details/concern/msrf/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                // Store the requestor name
                $name[] = $rows->requestor_name;
                // Store the subject of the request
                $subject[] = $rows->subject;
                
            }
    
            // Combine all columns' data into a single row for each ticket
            if($tickets){
                for ($i = 1; $i < $length_count; $i++) {
                    $data[] = array(
                        $tickets[$i],       // Ticket ID
                        $name[$i],          // Requestor name
                        $subject[$i],       // Subject
                        $prio_label[$i],    // Priority label
                        $status_label[$i],  // Status label
                        $app_stat_label[$i],// Department approval status label
                        $it_stat_label[$i],  // ICT approval status label
                    );
                }
            }
        }
    
        // Prepare the output for DataTables
        $output = array(
            "draw" => $draw,  // Echo back the draw counter received from DataTables
            "recordsTotal" => $length_count,  // Total records in the result set (with search)
            "recordsFiltered" => $length_count,  // Total filtered records (with search)
            "data" => $data  // Data array that contains all the rows to be displayed
        );
       
        // Return the output as a JSON response
        // print_r($data);
        // die();
        echo json_encode($output);
        die();
        exit();  // Ensure no further processing happens
    }
    
    /*
    public function get_msrf_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];
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

        $count_array = $this->db->query("SELECT * FROM service_request_msrf WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = ".$string_emp.") OR requester_id = ".$user_id." ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("SELECT * FROM service_request_msrf WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = " . $string_emp . ") OR requester_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length . "");
        //$strQry = $this->db->query("SELECT * FROM service_request_msrf WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = ".$string_emp.") OR requester_id = ".$user_id." ".$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");
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

                $label_class = '';
                if ($rows->status == 'Open') {
                    $label_class = 'label-primary';
                } else if ($rows->status == 'In Progress') {
                    $label_class = 'label-warning';
                } else if ($rows->status == 'On going') {
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

                $tickets[] = "<a href='".base_url()."sys/users/details/concern/msrf/".$rows->ticket_id."'>". $rows->ticket_id ."</a>";
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
    }*/

    //Datatable ng Admin
    public function all_tickets_msrf() {
        // SESSION DATA
        // Retrieve user_id and emp_id from the session to filter tickets relevant to the logged-in user.
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];   
    
        // DATATABLE PARAMETERS
        // Retrieve parameters sent by DataTables for server-side processing (pagination, ordering, search).
        $draw = intval($this->input->post("draw")); // Request sequence number for synchronization.
        $start = intval($this->input->post("start")); // Pagination starting point.
        $length = intval($this->input->post("length")); // Number of records to retrieve per page.
        $order = $this->input->post("order"); // User-specified column ordering.
        $search = $this->input->post("search"); // Search string input by the user.
        $search = $this->db->escape_str($search['value']); // Escape search string to prevent SQL injection.
        
        // DATE AND STATUS FILTERS
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $statusFilter = $this->input->post('statusFilter');

        // COLUMN ORDERING
        // Handles sorting based on user input, mapping the column index to valid database columns.
        $col = 0; // Default column index.
        $dir = "asc"; // Default sorting direction.
    
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column']; // Column index selected for ordering.
                $dir = $o['dir']; // Sorting direction (asc/desc).
            }
        }
        
        // Ensure the sorting direction is valid (either 'asc' or 'desc').
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }
    
        // Map column index to valid database column names.
        $valid_columns = array(
            0 => 'disable_date',
            1 => 'stamp'
        );
    
        // Default ordering if column index is invalid.
        $order = isset($valid_columns[$col]) ? $valid_columns[$col] : null;
    
        // SEARCH QUERY
        // Constructs a search query to filter results based on user input.
        $search_query = "";
        if (!empty($search)) {
            $search_query = "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        }
        // QUERY WITH DATE FILTERS
        /*$query = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved')";

        if (!empty($startDate) && !empty($endDate)) {
            $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
        }
        if (!empty($statusFilter)) {
            $query .= " AND status = '$statusFilter'";
        }*/
        //$datafilters = $this->db->query($query)->result();


        // TOTAL RECORD COUNT
        // Retrieve the total number of records that match the search and status filters.
        // Modify the query to exclude 'Closed' tickets if necessary.
        $count_query = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') " . $search_query . " ORDER BY recid";
        $count_array = $this->db->query($count_query);
        $length_count = $count_array->num_rows();
    
        // MAIN QUERY
        // Fetch the filtered records based on user permissions (assigned supervisor or IT staff).
        // Modify the query to exclude 'Closed' tickets if necessary.
        $strQry = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND (sup_id = " . $user_id . " OR it_sup_id = '23-0001' OR assigned_it_staff = '" . $emp_id . "') " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length;
        $data_query = $this->db->query($strQry);
    
        // DATA PROCESSING
        // Prepare the data to be returned to DataTables.
        $data = array();
    
        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $rows) {
                // Initialize arrays to store individual ticket attributes.
                $bid[] = $rows->recid;
                $ticket[] = $rows->ticket_id;
                $name[] = $rows->requestor_name;
                $subject[] = $rows->subject;
                $status[] = $rows->status;
    
                // If IT approval status is 'Resolved', set the ticket status to 'Closed'.
                if ($rows->it_approval_status == 'Resolved') {
                    $status = 'Closed';
                }
    
                $prio[] = $rows->priority;
                $app_stat[] = $rows->approval_status;
                $it_status[] = $rows->it_approval_status;
                $assigned_it_staff[] = $rows->assigned_it_staff;
    
                // Format status label with corresponding CSS classes.
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-info';
                        break;
                    case 'Closed':
                        $label_class = 'label-info';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'On Going':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-warning';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Format priority label with corresponding CSS classes.
                $prio_class = '';
                switch ($rows->priority) {
                    case 'Low':
                        $prio_class = 'label-primary';
                        break;
                    case 'Medium':
                        $prio_class = 'label-warning';
                        break;
                    case 'High':
                        $prio_class = 'label-danger';
                        break;
                }
                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';
    
                // Format approval status label with corresponding CSS classes.
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // Format IT approval status label with corresponding CSS classes.
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class= 'label-info';
                        break;
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
                // Generate a clickable link for the ticket ID.
                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";

                if($rows->approval_status === "Pending"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-requestor="'.$rows->requestor_name.'" data-department="'.$rows->department.'" data-concern="'.$rows->details_concern.'" data-date-needed="'.$rows->date_needed.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }
    
            // Populate data array with processed ticket information for DataTables.
            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array(
                    $tickets[$i],
                    $name[$i],
                    $subject[$i],
                    $prio_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i],
                    $action[$i], //Action
                );
            }
        }
    
        // PREPARE THE RESPONSE FOR DATATABLES
        // Encode the output as JSON and send it to the client.
        $output = array(
            "draw"            => $draw,             // Synchronize request-response cycle.
            "recordsTotal"    => $length_count,     // Total number of records.
            "recordsFiltered" => $length_count,     // Number of filtered records.
            "data"            => $data              // Processed ticket data.
        );
        echo json_encode($output);
        exit();
    }

    //Datatable ng User Tracc Concern
    public function get_tracc_concern_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id']; 
        $string_emp = $this->db->escape($emp_id);  
    
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
    
        // Enhanced search logic to search multiple fields
        $search_query = "AND reported_by_id = " . $this->db->escape($user_id);
        if (!empty($search)) {
            $search_query .= "AND (control_number LIKE '%" . $search . "%' OR reported_by LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        } 
        // else {
        //     $search_query = "AND reported_by_id = " . $this->db->escape($user_id);
        // }        
    
        $sess_login_data = $this->session->userdata('login_data');
        $role = $sess_login_data['role'];

        if($role === "L1"){
            $query = "status IN ('Open', 'In Progress', 'On going', 'Resolved') ".$search_query;
            $select = "recid, control_number, subject, module_affected, company, reported_by, reported_by_id, received_by, resolved_by, ack_as_resolved, others, priority, status, approval_status, it_approval_status";
            $result_data = $this->Tickets_model->get_data_list("service_request_tracc_concern", $query, 999,0, $select, null, null, null, null);

            $length_count = count($result_data);
        }else{
            // Fetch the count of records with the search filter
            $count_array = $this->db->query("
                SELECT * FROM service_request_tracc_concern 
                WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by_id = " . $user_id . ") 
                OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')) 
                " . $search_query
            );
            $length_count = $count_array->num_rows();
        
            // Fetch records with pagination and search filter applied
            $data = array();
            
            $strQry = $this->db->query("
                SELECT * FROM service_request_tracc_concern
                WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')
                AND reported_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);
            $result_data = $strQry->num_rows();
        }

        $control_number = [];
        $data = [];
        if ($result_data > 0) {
            foreach ($result_data as $rows) {
                // Status label
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                    case 'On going':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-info';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'Done':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-warning';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Company label
                $comp_class = '';
                switch ($rows->company) {
                    case 'LMI':
                        $comp_class = 'label-primary';
                        break;
                    case 'LPI':
                        $comp_class = 'label-warning';
                        break;
                    case 'RGDI':
                        $comp_class = 'label-danger';
                        break;
                    case 'SV':
                        $comp_class = 'label-primary';
                        break;
                }
                $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
    
                // Approval status label
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // IT approval status label
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;    
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
                // Ticket data
                $control_number[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                $name[] = $rows->reported_by;
                $subject[] = $rows->subject;
            }
    
            // Create rows for the output
            if($control_number){
                for ($i = 0; $i < count($control_number); $i++) {
                    $data[] = array(
                        $control_number[$i],// Control Number   
                        $name[$i],          // Reported by
                        $subject[$i],       // Subject
                        $priority_label[$i],
                        $comp_label[$i],    // Company label
                        $status_label[$i],  // Status label
                        $app_stat_label[$i],// Approval status label
                        $it_stat_label[$i] // IT approval status label
                    );
                }
            }
        }
    
        // Return the output for DataTables
        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count, 
            "data" => $data 
        );
    
        echo json_encode($output);
        exit();
    }
    

    /*public function all_tickets_tracc_concern() {
        // Retrieve user data from session
        $user_id = $this->session->userdata('login_data')['user_id'];  // Get the user ID from the session
        $emp_id = $this->session->userdata('login_data')['emp_id'];  // Get the employee ID from the session
        $string_emp = $this->db->escape($emp_id);  // Properly escape the employee ID to prevent SQL injection
    
        // Retrieve DataTables request parameters
        $draw = intval($this->input->post("draw"));  // The draw counter for DataTables (used to ensure AJAX requests are correctly processed)
        $start = intval($this->input->post("start"));  // The starting point for records (used for pagination)
        $length = intval($this->input->post("length"));  // The number of records to return (used for pagination)
        $order = $this->input->post("order");  // The column and direction for sorting
        $search = $this->input->post("search");  // The search value entered in the DataTables search box
        $search = $this->db->escape_str($search['value']);  // Escape the search value to prevent SQL injection
    
        // Initialize variables for column index and direction
        $col = 0;  // Default column index for sorting
        $dir = "";  // Default sorting direction
    
        // Process the order array to get the column index and sort direction
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  // Column index (as sent by DataTables)
                $dir = $o['dir'];  // Sorting direction ('asc' or 'desc')
            }
        }
    
        // Validate the sort direction
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  // Default to ascending if an invalid direction is provided
        }
    
        // Define an array of valid columns that can be used for sorting
        $valid_columns = array(
            0 => 'disable_date',  // Example column for sorting
            1 => 'stamp'  // Example column for sorting
        );
    
        // Validate the selected column index, if invalid, set $order to null
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  // Set the column name based on the column index
        }
    
        // Enhance the search query
        if (!empty($search)) {
            // If a search term is provided, construct the search query
            $search_query = "AND (control_number LIKE '%" . $search . "%' OR module_affected LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        } else {
            // If no search term, leave the search query empty
            $search_query = "";
        }
        
        //PAPALITAN NG QUERY
        // Count total records excluding 'Closed' status
        //$count_array = $this->db->query("SELECT * FROM service_request_tracc_concern  WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = " . $string_emp . ")  OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = ". $user_id . ") " . $search_query);
        $count_array = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND resolved_by = " . $string_emp . ") OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query);
        $length_count = $count_array->num_rows();  // Get the total number of rows for pagination
        
        // Fetch records excluding 'Closed' status
        $data = array();
        //$strQry = $this->db->query("SELECT * FROM service_request_msrf WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND assigned_it_staff = " . $string_emp . ") OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);
        //$strQry = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND resolved_by = " . $string_emp . ") OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);
        $strQry = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved')) OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);

        //PAPALITAN NG DATAS
        // Check if any records are returned from the query
        if ($strQry->num_rows() > 0) {
            // Loop through each row of the result set  
            foreach ($strQry->result() as $rows) {
                // Determine the appropriate label class for the status
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                    case 'On going':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-danger';
                        break;
                }

                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
                
                $comp_class = '';
                switch ($rows->company) {
                    case 'LMI':
                        $comp_class = 'label-primary';
                        break;
                    case 'LPI':
                        $comp_class = 'label-warning';
                        break;
                    case 'RGDI':
                        $comp_class = 'label-danger';
                        break;
                    case 'SV':
                        $comp_class = 'label-primary';
                        break;
                }
                // Store the priority label
                $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
                
                // Determine the appropriate label class for the department approval status
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                }
                // Store the department approval status label
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
                
                // Determine the appropriate label class for the ICT approval status
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                }
                // Store the ICT approval status label
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
                // Store the ticket ID as a clickable link to the details page
                $tickets[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                // Store the requestor name
                $name[] = $rows->reported_by;
                // Store the subject of the request
                $subject[] = $rows->subject;
            }
            
            // Combine all columns' data into a single row for each ticket
            for ($i = 0; $i < count($tickets); $i++) {
                $data[] = array(
                    $tickets[$i],       // Ticket ID
                    $name[$i],          // Requestor name
                    $subject[$i],       // Subject
                    $comp_label[$i],    // Priority label
                    $status_label[$i],  // Status label
                    $app_stat_label[$i],// Department approval status label
                    $it_stat_label[$i]  // ICT approval status label
                );
            }
        }
    
        // Prepare the output for DataTables
        $output = array(
            "draw" => $draw,  // Echo back the draw counter received from DataTables
            "recordsTotal" => $length_count,  // Total records in the result set (with search)
            "recordsFiltered" => $length_count,  // Total filtered records (with search)
            "data" => $data  // Data array that contains all the rows to be displayed
        );
        
        // Return the output as a JSON response
        echo json_encode($output);
        exit();  // Ensure no further processing happens
    }*/
    

    //Datatable ng Admin Tracc Concern
    public function all_tickets_tracc_concern() {
        // Retrieve user data from session
        $user_id = $this->session->userdata('login_data')['user_id'];  // Get the user ID from the session
        $emp_id = $this->session->userdata('login_data')['emp_id'];  // Get the employee ID from the session
        $string_emp = $this->db->escape($emp_id);  // Properly escape the employee ID to prevent SQL injection
    
        // Retrieve DataTables request parameters
        $draw = intval($this->input->post("draw"));  // The draw counter for DataTables (used to ensure AJAX requests are correctly processed)
        $start = intval($this->input->post("start"));  // The starting point for records (used for pagination)
        $length = intval($this->input->post("length"));  // The number of records to return (used for pagination)
        $order = $this->input->post("order");  // The column and direction for sorting
        $search = $this->input->post("search");  // The search value entered in the DataTables search box
        $search = $this->db->escape_str($search['value']);  // Escape the search value to prevent SQL injection
    
        // Initialize variables for column index and direction
        $col = 0;  // Default column index for sorting
        $dir = "";  // Default sorting direction
    
        // Process the order array to get the column index and sort direction
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  // Column index (as sent by DataTables)
                $dir = $o['dir'];  // Sorting direction ('asc' or 'desc')
            }
        }
    
        // Validate the sort direction
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  // Default to ascending if an invalid direction is provided
        }
    
        // Define an array of valid columns that can be used for sorting
        $valid_columns = array(
            0 => 'disable_date',  // Example column for sorting
            1 => 'stamp'  // Example column for sorting
        );
    
        // Validate the selected column index, if invalid, set $order to null
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  // Set the column name based on the column index
        }

        /*$search_query = "";
        if (!empty($search)) {
            $search_query = "AND (control_number LIKE '%" . $search . "%' OR module_affected LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        }*/
         // Enhanced search logic to search multiple fields
        if (!empty($search)) {
            $search_query = "AND (control_number LIKE '%" . $search . "%' OR module_affected LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }
        
        // Fetch the count of records with the search filter // Count total records excluding 'Closed' status
        //$count_array = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND resolved_by = " . $string_emp . ") OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query);
        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();

        // Fetch records excluding 'Closed' status
        // $data = array();
        // $strQry = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved')) OR (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);
        // $strQry = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved') OR reported_by = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length); 
        // Fetch records with pagination and search filter applied
        
        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')) 
            " . $search_query . " 
            ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        );

        // Check if any records are returned from the query
        if ($strQry->num_rows() > 0) {
            // Loop through each row of the result set  
            foreach ($strQry->result() as $rows) {
                // Determine the appropriate label class for the status

                // If IT approval status is 'Resolved', set the ticket status to 'Closed'.
                if ($rows->it_approval_status == 'Resolved') {
                    $status = 'Closed';
                }

                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-info';
                        break;
                    case 'Done':
                        $label_class = 'label-success';
                        break;
                }
    
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Company label
                $comp_class = '';
                switch ($rows->company) {
                    case 'LMI':
                        $comp_class = 'label-primary';
                        break;
                    case 'LPI':
                        $comp_class = 'label-warning';
                        break;
                    case 'RGDI':
                        $comp_class = 'label-danger';
                        break;
                    case 'SV':
                        $comp_class = 'label-info';
                        break;
                }
                $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
                
                // Department approval status label
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // ICT approval status label
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                   
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
                // Store the ticket ID as a clickable link to the details page
                //$tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                // Store the requestor name
                $name[] = $rows->reported_by;
                // Store the subject of the request
                $subject[] = $rows->subject;

                if($rows->approval_status === "Pending"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-reported-by="'.$rows->reported_by.'" data-concern="'.$rows->tcr_details.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }
    
            // Combine all columns' data into a single row for each ticket
            for ($i = 0; $i < count($tickets); $i++) {
                $data[] = array(
                    $tickets[$i],       // Ticket ID
                    $name[$i],          // Requestor name
                    $subject[$i],       // Subject
                    $priority_label[$i],
                    $comp_label[$i],    // Company label
                    $status_label[$i],  // Status label
                    $app_stat_label[$i],// Department approval status label
                    $it_stat_label[$i],  // ICT approval status label
                    $action[$i] //Action
                );
            }   
        }
    
        // Prepare the output for DataTables
        $output = array(
            "draw" => $draw,  // Echo back the draw counter received from DataTables
            "recordsTotal" => $length_count,  // Total records in the result set (with search)
            "recordsFiltered" => $length_count,  // Total filtered records (with search)
            "data" => $data  // Data array that contains all the rows to be displayed
        );
    
        // Return the output as a JSON response
        echo json_encode($output);
        exit();  // Ensure no further processing happens
    }

      //Datatable ng Admin Tracc Request
    public function all_tickets_tracc_request() {
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        $string_emp = $this->db->escape($emp_id);

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);

        $col = 0;
        $dir = "";

        if (!empty($order)){
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc"){
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
            $search_query = "AND (recid LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_request
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_request 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')) 
            " . $search_query . " 
            ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        );

        if ($strQry->num_rows() > 0){
            foreach ($strQry->result() as $rows){
                if ($rows->it_approval_status == 'Resolved'){
                    $status = 'Closed';
                }

                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed':
                        $label_class = 'label-info';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';

                $app_stat_class = '';
                switch ($rows->approval_status){
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';

                $it_stat_class = '';
                switch ($rows->it_approval_status){
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';

                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id ."</a>";
                $name[] = $rows->requested_by;
                $subject[] = $rows->subject;

                if($rows->approval_status === "Pending"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-requestor="'.$rows->requested_by.'" data-department="'.$rows->department.'" data-concern="'.$rows->complete_details.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }

            for ($i = 0; $i < count($tickets); $i++){
                $data[] = array(
                    $tickets[$i],
                    $name[$i],
                    $subject[$i],
                    $priority_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i],
                    $action[$i]
                );
            }
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $length_count,
            "recordsFiltered" => $length_count,
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
    
    //Datatable ng User
    public function get_trf_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id']; 
        $string_emp = $this->db->escape($emp_id);  
    
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
    
        // Enhanced search logic to search multiple fields
        $search_query = "AND requested_by_id = " . $this->db->escape($user_id);
        if (!empty($search)) {
            $search_query .= "AND (ticket_id LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        }
        //else {
        //    $search_query = "AND requested_by_id = " . $this->db->escape($user_id);
        //}        
    
        $sess_login_data = $this->session->userdata('login_data');
        $role = $sess_login_data['role'];

        if($role === "L1"){
            $query = "status IN ('Open', 'In Progress', 'On going', 'Resolved') ".$search_query;
            $select = "recid, ticket_id, subject, company, priority, status, approval_status, it_approval_status, requested_by";
            $result_data = $this->Tickets_model->get_data_list("service_request_tracc_request", $query, 999,0, $select, null, null, null, null);

            $length_count = count($result_data);
        }else{
            // Fetch the count of records with the search filter
            $count_array = $this->db->query("
                SELECT * FROM service_request_tracc_request 
                WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND requested_by_id = " . $user_id . ") 
                OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')) 
                " . $search_query
            );
            $length_count = $count_array->num_rows();
            
            $data = array();
            
            $strQry = $this->db->query("
                SELECT * FROM service_request_tracc_request
                WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')
                AND requested_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length); 
            $result_data = $strQry->num_rows();              
        }


        $trf_ticket = [];
        $data = [];
        if ($result_data > 0) {
            foreach ($result_data as $rows) {
                // Status label
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                    case 'On going':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-info';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Approval status label
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // IT approval status label
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;    
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
                // Ticket data
                $trf_ticket[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_request/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                $name[] = $rows->requested_by;
                $subject[] = $rows->subject;
            }
    
            // Create rows for the output
            if($trf_ticket){
                for ($i = 0; $i < count($trf_ticket); $i++) {
                    $data[] = array(
                        $trf_ticket[$i],// Control Number   
                        $name[$i],          // Reported by
                        $subject[$i],       // Subject
                        $priority_label[$i],
                        $status_label[$i],  // Status label
                        $app_stat_label[$i],// Approval status label
                        $it_stat_label[$i]  // IT approval status label
                    );
                }                
            }

        }
    
        // Return the output for DataTables
        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count, 
            "data" => $data 
        );
    
        echo json_encode($output);
        exit();
    }

    // Kevin's code
    public function print_tickets_msrf() {

        // Get start and end date input.
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');
        $status = $this->input->post('status');

        // Get columns ticket_id, requestor_name, department, date_requested, date_needed, asset_code, and status from service_request_msrf table.
        $this->db->select('ticket_id, requestor_name, department, date_requested, date_needed, asset_code, status');
        $this->db->from('service_request_msrf');

        if($status != '') {
            $this->db->where('status', $status);
        }

        // If start and end date is provided, add where clauses to filter the results.
        if($startDate && $endDate) {
            $this->db->where('created_at >=', $startDate);
            $this->db->where('created_at <=', $endDate);
        }

        // Place results inside a variable.
        $query = $this->db->get();
        $data = $query->result_array();

        // Format Date
        $formattedData = [];
        foreach($data as $row) {
            $row['date_requested'] = date('M j, Y', strtotime($row['date_requested']));
            $row['date_needed'] = date('M j, Y', strtotime($row['date_needed']));

            $formattedData[] = $row;
        }

        // Count the results from the query
        // $totalRecordsQuery = $this->db->query("SELECT COUNT(*) AS total FROM users");
        // $totalRecords = $totalRecordsQuery->row()->total;

        // Place needed information inside an array variable.
        $output = array(
            "draw" => intval($this->input->post('draw')),
            // "recordsTotal" => $totalRecords,
            // "recordsFiltered" => count($data),
            "data" => $formattedData
        );

        // Convert the array into a JSON.
        echo json_encode($output);
        exit();
    }

    /*
        Kevin's codes
    */
    
    public function print_tickets_tracc_concern() {
        
        // Get start and end date input.
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');
        $status = $this->input->post('status');

        // Get columns control_number, reported_by, reported_date, resolved_date, and status from service_request_tracc_concern.
        $this->db->select('control_number, reported_by, reported_date, resolved_date, status');
        $this->db->from('service_request_tracc_concern');

        if($status != '') {
            $this->db->where('status', $status);
        }
        // If start and end date input exists, an additional where clause is added to the query.
        if($startDate && $endDate) {
            $this->db->where('created_at >=', $startDate);
            $this->db->where('created_at <=', $endDate);
        }

        // Place the values from the query to variables.
        $query = $this->db->get();
        $data = $query->result_array();

        // Format Date.
        $formattedData = [];
        foreach($data as $row) {
            $row['reported_date'] = date('M j, Y', strtotime($row['reported_date']));
            $row['resolved_date'] = date('M j, Y', strtotime($row['resolved_date']));

            $formattedData[] = $row;
        }

        // Count the returned rows.
        $totalRecordsQuery = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $totalRecords = $totalRecordsQuery->row()->total;

        // Insert all the data from the database into an array.
        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => count($data),
            "data" => $formattedData
        );

        // Convert the array to a JSON.
        echo json_encode($output);
        exit();
    }
    
}