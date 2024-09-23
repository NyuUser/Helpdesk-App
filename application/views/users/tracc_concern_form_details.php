<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				TRACC Concern Details
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
                                        <form action="<?= site_url('Main/acknowledge_as_resolved'); ?>" method="POST">
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
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Requested</label>
                                                    <input type="text" name="date_req" id="date_req" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Received by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="received_by" id="received_by" value="<?= $tracc_con['received_by']; ?>" readonly>
                                                        <option value=""disabled selected>Received By</option>
                                                        <option>SIR CK</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Noted by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="noted_by" id="noted_by" value="<?= $tracc_con['noted_by']; ?>" readonly>
                                                        <option value=""disabled selected>Noted By</option>
                                                        <option>SIR CK</option>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="app_stat" id="app_stat" disabled>
                                                        <option value=""disabled selected>Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ICT Approval Status <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="it_app_stat" id="it_app_stat" disabled>
                                                        <option value=""disabled selected>ICT Approval Status</option>
                                                        <option value="Approved"<?php if ($tracc_con['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($tracc_con['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($tracc_con['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>       
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Solution/Details <span style = "color:red;">*</span></label>
                                                        <textarea class="form-control" name="solution" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;" readonly></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved by <span style = "color:red;">*</span></label>
                                                    <select class="form-control select2" name="resolved_by" id="resolved_by" readonly>
                                                        <option value=""disabled selected>Noted By</option>
                                                        <option>SIR CK</option>
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Resolved Date <span style = "color:red;">*</span></label>
                                                    <input type="date" name="res_date" id="res_date" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
			                    					<label>Acknowledge as resolved by</label>
                                                    <input type="text" name="ack_as_res_by" id="ack_as_res_by" class="form-control" value="<?= $tracc_con['ack_as_resolved']; ?>" required>
			                    				</div>                                                
			                                </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acknowledge as Resolved Date</label>
                                                    <input type="date" name="ack_as_res_date" id="ack_as_res_date" class="form-control select2" value="<?= $tracc_con['ack_as_resolved_date']; ?>" style="width: 100%;" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>To be filled by MIS <span style = "color:red;">*</span></label>
                                                    <div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_mis" id="checkbox_mis" disabled> For MIS Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_lst" id="checkbox_lst" disabled> For LST Concern
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block; margin-right: 20px;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_system_error" id="checkbox_system_error" disabled> System Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="display: inline-block;">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="checkbox_user_error" id="checkbox_user_error" disabled> User Error
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="margin-top: -5px;">
                                                    <label>Received by</label>
                                                    <input type="text" name="received_by_lst" value="" class="form-control select2" placeholder="LST Coordinator" readonly>
                                                </div>

                                            </div>
                                            
                                            <div class="col-md-6">                            
                                                <div class="form-group">
                                                    <label>Others</label>
                                                    <input type="text" name="others" value="" class="form-control select2" placeholder="Please Specify" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date" value="" class="form-control select2" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Submit Tickets</button>
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
        $('#date_req').val(today);
    });

</script>