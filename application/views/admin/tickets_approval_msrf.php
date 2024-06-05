<div class="content-wrapper">
    <section class="content-header">
		<h1>
			Approval Tickets
			<small>Ticket</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-users"></i> Home</a></li>
			<li class="active">My Tickets</li>
			<li class="active">Creation Tickets</li>
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
			                    <form action="<?= site_url('Main/dept_supervisor_approval'); ?>" method="POST">
			                    	<div class="col-md-12">
			                    		<div class="form-group">
			                    			<label>MSR#</label>
			                    			<input type="text" name="msrf_number" id="msrf_number" value="<?php echo $msrf['ticket_id']; ?>" class="form-control" readonly>
			                    		</div>
			                    	</div>
			                    	<div class="col-md-6">
			                            <div class="form-group">
			                                <label>Requestor</label>
			                                <input type="text" name="name" class="form-control" value="<?php echo $msrf['requestor_name']; ?>" readonly>
			                            </div>
			                            <div class="form-group">
			                                <label>Department</label>
												<input type="text" name="department_description" id="department_description" value="<?php echo $msrf['department']; ?>" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                </div>
			                            </div>
			                            <div class="col-md-6">
			                                <div class="form-group">
			                                    <label>Date Requested</label>
			                                    <input type="text" name="date_req" id="date_req" class="form-control select2" value="<?php echo $msrf['date_requested']; ?>" style="width: 100%;" readonly>
			                                </div>
			                                <div class="form-group">
			                                    <label>Date Needed</label>
			                                    <input type="text" name="date_need" class="form-control select2" value="<?php echo $msrf['date_needed']; ?>" style="width: 100%;" readonly>
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <label>Asset Code</label>
			                                    <input type="text" name="asset_code" class="form-control select2" value="<?php echo $msrf['asset_code']; ?>" style="width: 100%;" placeholder="Asset Code" readonly>
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <label>Request Category</label>
			                                    <select class="form-control select2" name="category" id="category" style="width: 100%;" disabled>
													<option value="">Select Category</option>
													<option value="computer"<?php if ($msrf['category'] == 'computer') echo ' selected'; ?>>Computer (Laptop or Desktop)</option>
													<option value="printer"<?php if ($msrf['category'] == 'printer') echo ' selected'; ?>>Printer Concerns</option>
													<option value="network"<?php if ($msrf['category'] == 'network') echo ' selected'; ?>>Network or Internet connection</option>
													<option value="projector"<?php if ($msrf['category'] == 'projector') echo ' selected'; ?>>Projector / TV Set-up</option>
													<option value="others"<?php if ($msrf['category'] == 'others') echo ' selected'; ?>>Others</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <div class="col-md-12" name="specify" id="specify" style="margin:0 auto; display:none;">
			                                <div class="form-group" >
			                                    <label>Please Specify</label>
			                                    <input type="text" name="specify" class="form-control select2" style="width: 100%;">
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <label>Details Concern</label>
			                                    <div class="box-body pad">
													<textarea class="textarea" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" readonly><?php echo $msrf['details_concern']; ?></textarea>
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

										<?php if ($user_details['emp_id'] == '23-0001') { ?>
											<div class="col-md-12">
												<div class="form-group">
													<label>ICT Approval Status</label>
													<select class="form-control select2" name="approval_stat" id="approval_stat" style="width: 100%;">
														<option value=""></option>
														<option value="Approved">Approved</option>
														<option value="Rejected">Rejected</option>
													</select>
												</div>
											</div>
											<div class="col-md-12" id="ictassign" style="display: none;">
												<div class="form-group">
													<label>ICT Assign To</label>
													<select name="assign_to" class="form-control select2">
														<option value=""></option>
														<?php if (isset($getTeam) && is_array($getTeam)) : ?>
															<?php foreach($getTeam as $team) : ?>
																<?php if (is_array($team)) : // Ensure $team is an array ?>
																	<option value="<?php echo $team['emp_id']; ?>">
																		<?php echo $team['fname'] . ' ' . $team['lname']; ?>
																	</option>
																<?php endif; ?>
															<?php endforeach; ?>
														<?php endif; ?>
													</select>
												</div>
											</div>
											<div class="col-md-12" id="reason" style="display: none;">
												<div class="form-group">
													<label>Reason for Reject Tickets</label>
													<textarea class="textarea" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
												</div>
											</div>
										<?php } ?>
										
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <div class="box-body pad">
			                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Approved Tickets</button>
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