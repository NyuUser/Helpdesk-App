<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Employee
			<small>List of employee</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">List of Employee</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<table id="tblUsers" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Username</th>
                                    <th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Username</th>
                                    <th>Action</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Modal Show Lock Account -->
<div class="modal fade" id="modalLock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Helpdesk Notification</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<form action="<?= site_url('Main/lock_users');?>" method="POST">
				<input type="hidden" name="id" id="id">
				<div class="modal-body">
					<p>Do you want to Lock this Employee ID: <span id="employee_id"></span></p>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-success float-right">Submit</button>
			    </div>
			</form>
		</div>
	</div>
</div>
<!-- Modal Show Unlock Account -->
<div class="modal fade" id="modalUnlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Helpdesk Notification</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<form action="<?= site_url('Main/unlock_users');?>" method="POST">
				<input type="hidden" name="id" id="recid">
				<div class="modal-body">
					<p>Do you want to Unlock this Employee ID: <span id="employee_id"></span></p>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-success float-right">Submit</button>
			    </div>
			</form>
		</div>
	</div>
</div>