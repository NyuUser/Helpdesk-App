<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				MSRF Deatils
				<small>Ticket</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">MSRF Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Ticket for MSRF</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/update_status_msrf_assign'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>MSR#</label>
			                    					<input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?= $msrf['ticket_id']; ?>" readonly>
			                    				</div>
			                    			</div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Requestor</label>
			                                        <input type="text" name="name" value="<?= $msrf['requestor_name']; ?>" class="form-control select2" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Department</label>
			                                        <input type="text" name="department_description" id="department_description" value="<?= $msrf['department']; ?>" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                    </div>
			                                </div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Date Requested</label>
			                                        <input type="text" name="date_req" id="date_req" class="form-control select2" value="<?= $msrf['date_requested']; ?>" style="width: 100%;" readonly>
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="text" name="date_need" class="form-control select2" value="<?= $msrf['date_needed']; ?>" style="width: 100%;" readonly>
			                                    </div>
			                                </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                    <div class="box-body pad">
                                                        <textarea class="textarea" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled><?= $msrf['details_concern']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Approval Status</label>
                                                    <select class="form-control select2" name="approval_stat" style="width: 100%;" <?php if ($msrf['approval_status'] == 'Approved' || $msrf['approval_status'] == 'Rejected') echo 'disabled'; ?>>
                                                        <option value=""></option>
                                                        <option value="Approved"<?php if ($msrf['approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>ICT Supervisor Status</label>
                                                    <select name="it_approval_stat" class="form-control select2" <?php if ($msrf['it_approval_status'] == 'Approved' || $msrf['it_approval_status'] == 'Rejected') echo 'disabled'; ?>>
                                                        <option value=""></option>
                                                        <option value="Approved"<?php if ($msrf['it_approval_status'] == 'Approved') echo ' selected'; ?>>Approved</option>
                                                        <option value="Pending"<?php if ($msrf['it_approval_status'] == 'Pending') echo ' selected'; ?>>Pending</option>
                                                        <option value="Rejected"<?php if ($msrf['it_approval_status'] == 'Rejected') echo ' selected'; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Ticket Status</label>
                                                    <select name="it_status" id="it_status" class="form-control select2">
                                                        <option value="">Please select status for reference</option>
                                                        <option value="On going">On going</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary" disabled>Submit Tickets</button>
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