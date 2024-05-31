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
			                    <form action="" method="POST">
			                    	<div class="col-md-12">
			                    		<div class="form-group">
			                    			<label>MSR#</label>
			                    			<input type="text" name="msrf_number" id="msrf_number" class="form-control" readonly>
			                    		</div>
			                    	</div>
			                    	<div class="col-md-6">
			                            <div class="form-group">
			                                <label>Requestor</label>
			                                <input type="text" name="name" value="" class="form-control select2" style="width: 100%;" readonly>
			                            </div>
			                            <div class="form-group">
			                                <label>Department</label>
												<input type="text" name="department_description" id="department_description" value="" class="form-control select2" style="width: 100%;" readonly/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                </div>
			                            </div>
			                            <div class="col-md-6">
			                                <div class="form-group">
			                                    <label>Date Requested</label>
			                                    <input type="text" name="date_req" id="date_req" class="form-control select2" onload="getDate()" style="width: 100%;" readonly>
			                                </div>
			                                <div class="form-group">
			                                    <label>Date Needed</label>
			                                    <input type="date" name="date_need" class="form-control select2" style="width: 100%;">
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <label>Asset Code</label>
			                                    <input type="text" name="asset_code" class="form-control select2" style="width: 100%;" placeholder="Asset Code">
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="form-group">
			                                    <label>Request Category</label>
			                                    <select class="form-control select2" name="category" id="category" style="width: 100%;">
			                                        <option value="">Select Category</option>
			                                        <option value="computer">Computer (Laptop or Desktop)</option>
			                                        <option value="printer">Printer Concerns</option>
			                                        <option value="network">Network or Internet connection</option>
			                                        <option value="projector">Projector / TV Set-up</option>
			                                        <option value="others">Others</option>
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
			                                        <textarea class="textarea" name="concern" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
			                                    </div>
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