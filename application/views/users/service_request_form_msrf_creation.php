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
                                        <form action="<?= site_url('Main/update_status_msrf_assign'); ?>" method="POST">
                                            <div class="col-md-12">
			                    				<div class="form-group">
			                    					<label>MSR#</label>
			                    					<input type="text" name="msrf_number" id="msrf_number" class="form-control" value="" readonly>
			                    				</div>
			                    			</div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Requestor</label>
			                                        <input type="text" name="name" value="" class="form-control select2" style="width: 100%;">
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Department</label>
			                                        <input type="text" name="department_description" id="department_description" value="" class="form-control select2" style="width: 100%;"/>
												<input type="hidden" name="dept_id" value="">
												<input type="hidden" name="sup_id" value="">
			                                    </div>
			                                </div>
                                            <div class="col-md-6">
			                                    <div class="form-group">
			                                        <label>Date Requested</label>
			                                        <input type="text" name="date_req" id="date_req" class="form-control select2" value="" style="width: 100%;">
			                                    </div>
			                                    <div class="form-group">
			                                        <label>Date Needed</label>
			                                        <input type="date" name="date_need" class="form-control select2" style="width: 100%;">
			                                    </div>
			                                </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Asset Code</label>
                                                    <input type="text" class="form-control select2" style="width: 100%;">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Request Category</label>
                                                    <select class="form-control select2" style="width: 100%;">
                                                        <option value=""></option>
                                                        <option value="">Computer (Laptop or Desktop)</option>
                                                        <option value="">Printer Concerns</option>
                                                        <option value="">Network or Internet connection</option>
                                                        <option value="">Projector / TV Set-up</option>
                                                        <option value="">Others</option>
                                                    </select>
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