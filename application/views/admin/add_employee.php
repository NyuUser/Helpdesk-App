<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Form Add Employee
			<small>Add employee</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Add Employee</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Employee List</h3>
				<div class="box-tools pull-right">
            		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          		</div>
			</div>
			<form action="<?= site_url('Main/employee_add'); ?>" method="POST">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Employee No.</label>
								<input type="text" name="emp_id" id="emp_id" class="form-control">
							</div>
							<div class="form-group">
								<label>Firstname</label>
								<input type="text" name="fname" id="fname" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Middlename</label>
								<input type="text" name="mname" id="mname" class="form-control">
							</div>
							<div class="form-group">
								<label>Lastname</label>
								<input type="text" name="lname" id="lname" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Company Email</label>
								<input type="email" name="email" id="email" class="form-control">
							</div>
							<div class="form-group">
								<label>Department</label>
								<select name="department" class="form-control">
							        <option value="">Please select department</option>
							        <?php foreach ($department_data[1] as $department): ?>
							            <option value="<?php echo $department['recid']; ?>"><?php echo $department['dept_desc']; ?></option>
							        <?php endforeach; ?>
							    </select>
							</div>
							<div class="form-group">
								<label>Position</label>
								<input type="text" name="position" id="position" class="form-control">
							</div>
							<div class="form-group">
								<label>Username</label>
								<input type="text" name="username" id="username" class="form-control">
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" id="password" class="form-control">
							</div>
							<div class="form-group">
								<label>Confirm Password</label>
								<input type="password" name="cpassword" id="cpassword" class="form-control">
							</div>
							<div class="form-group">
								<label>
	                  				<input type="checkbox" class="minimal" id="showPassword">
	                  				Show Password
	                			</label>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button class="btn btn-info float-left">Submit</button>
						<a href="<?= base_url(); ?>sys/admin/users" class="btn btn-danger float-left">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>