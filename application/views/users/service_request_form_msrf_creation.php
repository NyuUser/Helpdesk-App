<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				MSRF Details
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
                                        <form action="<?= site_url('Main/users_creation_tickets_msrf'); ?>" method="POST" enctype="multipart/form-data">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>MSR#</label>
			                    					<!-- <input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?php echo $msrf['ticket_id']; ?>" readonly> -->
                                                    <input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?php echo htmlspecialchars($msrf); ?>" readonly>
			                    				</div>
			                    			</div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Requestor</label>
			                                        <input type="text" name="name" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" style="width: 100%;" readonly>
			                                    </div>
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <input type="text" name="department_description" id="department_description" value="<?php echo isset($get_department[1]['dept_desc']) ? htmlspecialchars($get_department[1]['dept_desc']) : ''; ?>" class="form-control select2" style="width: 100%;" readonly>
                                                    <input type="hidden" name="dept_id" value="<?php echo $users_det['dept_id']; ?>">
                                                    <!-- If supervisor_id is also needed -->
                                                    <input type="hidden" name="sup_id" value="<?php echo htmlspecialchars($users_det['sup_id']); ?>">
                                                </div>
			                                    <!-- <div class="form-group">
			                                        <label>Department</label>
			                                        <input type="text" name="department_description" id="department_description" value="" class="form-control select2" style="width: 100%;"/>
												    <input type="hidden" name="dept_id" value="">
												    <input type="hidden" name="sup_id" value="">
			                                    </div> -->
			                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date Requested</label>
                                                    <input type="text" name="date_req" id="date_req" class="form-control select2" value="" style="width: 100%;" readonly>
                                                </div>
			                                    <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_need" id="date_need" class="form-control select2" style="width: 100%;" required>
			                                    </div>
			                                </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Asset Code</label>
                                                    <input type="text" name="asset_code" id="asset_code" class="form-control select2" style="width: 100%;">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Request Category</label>
                                                    <select class="form-control select2" name="category" id="category" style="width: 100%;" required>
                                                        <option value=""disabled selected>Request Category</option>
                                                        <option value="computer">Computer (Laptop or Desktop)</option>
                                                        <option value="printer">Printer Concerns</option>
                                                        <option value="network">Network or Internet connection</option>
                                                        <option value="projector">Projector / TV Set-up</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- SPECIFY START -->
                                            <div class="col-md-12" id="specifyDiv" name="specifyDiv" style="display: none;">
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <input type="text" name="specify" id="specify" class="form-control" style="width: 100%;">
                                                </div>
                                            </div>
                                            <!-- SPECIFY END -->
                                            

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Details Concern</label>
                                                        <textarea class="form-control" name="msrf_concern" id="msrf_concern" placeholder="Place the details concern here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px; resize: vertical;"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <input type="file" name="uploaded_file" id="uploaded_file" class="form-control" accept="image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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
        $('#categorySelect').change(function() {
            var selectedValue = $(this).val();
            console.log("Selected value: " + selectedValue);

            if (selectedValue === 'others') {
                $('#specifyDiv').show();
                console.log("Showing Specify");
            } else {
                $('#specifyDiv').hide();
                console.log("Hiding Specify");
            }
        });
    });

    $(document).ready(function() {
        // Set the current date in YYYY-MM-DD format
        var today = new Date().toISOString().split('T')[0];
        $('#date_req').val(today);
    });

    $(document).ready(function() {
        function autoResizeTextarea() {
            $(this).css('height', 'auto'); // Reset the height to auto to calculate new height
            $(this).height(this.scrollHeight); // Set height based on content
        }
        
        // Apply the resize function to the textarea on input
        $('#msrf_concerns').on('input', autoResizeTextarea);
        
        // Trigger the resize on page load if there's existing content in the textarea
        $('#msrf_concerns').each(autoResizeTextarea);
        
    });
</script>