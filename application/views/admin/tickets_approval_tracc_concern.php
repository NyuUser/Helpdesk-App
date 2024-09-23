<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Concern Approval
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">TRACC Concern Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#tracc_concern" data-toggle="tab">Ticket for TRACC Concern</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tracc_concern">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/admin_list_tracc_concern'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>Control Number</label>
                                                    <input type="text" name="control_number" id="control_number" class="form-control" value="<?= $tracc_con['control_number']; ?>" readonly>                                                 
			                    				</div>                                               
			                    			</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Module Affected</label>
                                                    <input type="text" name="module_affected" id="module_affected" class="form-control" value="<?= $tracc_con['module_affected']; ?>" readonly>
			                    				</div>                                                
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Company</label>
                                                    <select class="form-control select2" name="company" id="company" disabled>
                                                        <option value=""disabled selected>Company Category</option>
                                                        <option value="lmi"<?php if ($tracc_con['company'] == 'LMI') echo ' selected'; ?>>LMI</option>
                                                        <option value="rgdi"<?php if ($tracc_con['company'] == 'RGDI') echo ' selected'; ?>>RGDI</option>
                                                        <option value="lpi"<?php if ($tracc_con['company'] == 'LPI') echo ' selected'; ?>>LPI</option>
                                                        <option value="sv"<?php if ($tracc_con['company'] == 'SV') echo ' selected'; ?>>SV</option>
                                                    </select>                    
			                    				</div>                                             
			                                </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                        <textarea class="form-control" name="concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"disabled><?= $tracc_con['tcr_details']; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Reported by</label>
                                                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Date Reported</label>
                                                    <input type="text" name="date_rep" id="date_rep" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Received by</label>
                                                    <select class="form-control select2" name="received_by" id="received_by">
                                                        <option value=""disabled selected>Received By</option>
                                                        <option value="CK">Sir. CK</option>
                                                        <option value="HANNA">Ms. Hanna</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Noted by</label>
                                                    <select class="form-control select2" name="noted_by" id="noted_by">
                                                        <option value=""disabled selected>Noted By</option>
                                                        <option value="CK">Sir. CK</option>
                                                        <option value="HANNA">Ms. Hanna</option>    
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat">
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ICT Approval Status</label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat">
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                        <option value="Resolved"<?php if ($tracc_con['approval_status'] == 'Resolved') echo ' selected'; ?>>Resolved</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details</label>
                                                        <textarea class="form-control" id= "solution" name="solution" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by</label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by">
                                                        <option value=""disabled selected>Resolved By</option>
                                                        <option value="CK">Sir. CK</option>
                                                        <option value="HANNA">Ms. Hanna</option>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved Date</label>
                                                    <input type="date" name="res_date" id="res_date" class="form-control select2" value="" style="width: 100%;">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Acknowledge as resolved by</label>
                                                    <input type="text" name="ack_as_res" id="ack_as_res" class="form-control" value="<?= $tracc_con['ack_as_resolved']; ?>" readonly>
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $tracc_con['ack_as_resolved_date']; ?>" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS</label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis"> For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst"> For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error"> System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error"> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" value="" class="form-control select2" placeholder="LST Coordinator">
                                                </div>

                                            </div>-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS</label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis"> For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst" onclick="toggleLstFields()"> For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error"> System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error"> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Received by and Date fields (initially hidden) -->
                                                <div class="form-group" id="received_by_lst_section" style="display:none; margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" value="" class="form-control select2" placeholder="LST Coordinator">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">                            
                                                <div class="form-group">
                                                    <label>Others</label>
                                                    <input type="text" name="others" value="" class="form-control select2" placeholder="Please Specify">
                                                </div>
                                            </div>

                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" value="" class="form-control select2">
                                                </div>
                                            </div>-->

                                            <div class="col-md-6" id="date_section" style="display:none;">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date_lst" value="" class="form-control select2">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Validate Tickets</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<script>

    $(document).ready(function() {
        // Set the current date in YYYY-MM-DD format
        var today = new Date().toISOString().split('T')[0];
        $('#date_rep').val(today);
        $('#res_date').val(today);
    });

    function toggleLstFields() {
        var lstCheckbox = document.getElementById('checkbox_lst');

        var receivedBySection = document.getElementById('received_by_lst_section');
        var dateSection = document.getElementById('date_section');

        if (lstCheckbox.checked) {
            receivedBySection.style.display = 'block';
            dateSection.style.display = 'block';
        } else {
            receivedBySection.style.display = 'none';
            dateSection.style.display = 'none';
        }
    }

</script>