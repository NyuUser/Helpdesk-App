<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ICT Helpdesk Application</title>
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/css/bootstrap.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/users_template/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue layout-top-nav">
	<div class="wrapper">
		<header class="main-header">
			<nav class="navbar navbar-static-top">
				<div class="container">
					<div class="navbar-header">
          				<a href="<?= base_url(); ?>" class="navbar-brand"><b>ICT</b> Helpdesk</a>
          				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            				<i class="fa fa-bars"></i>
          				</button>
        			</div>

        			<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        				<ul class="nav navbar-nav">
        					<li class="active"><a href="<?= base_url(); ?>sys/users/dashboard">My Tickets <span class="sr-only">(current)</span></a></li>
        				</ul>
        			</div>
        			<div class="navbar-custom-menu">
        				<ul class="nav navbar-nav">
        					<li class="dropdown user user-menu">
        						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
        							<span class="glyphicon glyphicon-user"></span>
        							<span class="hidden-xs"><?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?></span>
        						</a>
        						<ul class="dropdown-menu">
                					<li class="user-footer">
                  						<div class="pull-right">
                    						<a href="<?= site_url('Main/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                  						</div>
                					</li>
        						</ul>
        					</li>
        				</ul>
        			</div>
				</div>
			</nav>
		</header>
		<div class="content-wrapper">
			<div class="container">
				<section class="content-header">
					<h1>
          				Tickets
          				<small>List of My Tickets</small>
        			</h1>
			        <ol class="breadcrumb">
			          	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			          	<li><a href="#">Dashboard</a></li>
			          	<li class="active">My Tickets</li>
			        </ol>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<div class="box-body">
									<table id="tblTickets" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Ticket ID</th>
												<th>Requestor Name</th>
												<th>Subject</th>
												<th>Priority</th>
												<th>Status</th>
												<th>Approval Status</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Ticket ID</th>
												<th>Requestor Name</th>
												<th>Subject</th>
												<th>Priority</th>
												<th>Status</th>
												<th>Approval Status</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      				<div class="modal-header">
        				<h4 class="modal-title" id="myModalLabel">ICT Helpdesk Information</h4>
      				</div>
      				<div class="modal-body">
					  	<div class="modal-body modal-padding-custom">
							<div class="form-group">
        						<label>List of Tickets</label>
								<select name="concern" id="concern" class="form-control">
									<option>Please select concern tickets</option>
									<option value="MSRF">MSRF Form</option>
									<option value="TRACC">TRACC Form</option>
								</select>
							</div>
						</div>
      				</div>
      				<div class="modal-footer">
						<button type="button" class="btn btn-success" name="concern_btn" id="concern_btn">Next</button>
        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      				</div>
    			</div>
  			</div>
		</div>

		<footer class="main-footer">
    		<div class="container">
      			<div class="pull-right hidden-xs">
        			<b>Version</b> 1.0.0
      			</div>
      			<strong>Copyright &copy; 2024-2025 <a href="https://adminlte.io">ICT Helpdesk</a>.</strong> All rights
      			reserved.
    		</div>
  		</footer>
	</div>

	<!-- Modal for Success -->
	<?php if ($this->input->get('success')): ?>
		<?php echo urldecode($this->input->get('success')); ?>
	<?php endif; ?>

	<!-- Modal for Error -->
	<?php if ($this->input->get('error')): ?>
		<?php echo urldecode($this->input->get('error')); ?>
	<?php endif; ?>

	<!-- Modal for Success -->
	<div id="modal-success-users" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<p>Success! Your message here.</p>
		</div>
	</div>

	<!-- Modal for Error -->
	<div id="modal-error-users" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<p>Error occurred. Please try again.</p>
		</div>
	</div>

	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/fastclick/lib/fastclick.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/adminlte.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/demo.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
	<script>
		$(document).ready(function() {
			console.log("JavaScript is running");
			$(".close").click(function(){
				$("#modal-success-users, #modal-error-users").modal("hide");
			});

			$('#concern_btn').click(function() {
				var selectedValue = $('#concern').val();

				if (selectedValue == "MSRF") {
					window.location.href = "<?= base_url(); ?>sys/users/create/tickets/msrf";
				} else if (selectedValue == "TRACC") {
					window.location.href = "<?= base_url(); ?>sys/users/create/tickets/tracc";
				} else {
					alert("Please Selected for creation of tickets");
				}
			});
			
			$('#tblTickets').DataTable({
				"serverSide": true,
                "processing": true,
                "ajax": {
                	"url": "<?= base_url(); ?>DataTables/get_msrf_ticket",
                	"type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [{
                    text: 'Create Tickets',
                    className: 'btn btn-success',
                    action: function (e, dt, node, config) {
                    	// window.location.href = "<?= base_url(); ?>sys/users/create/tickets";
						$('#myModal').modal("show");
                    }
                }],
                "columnDefs": [{
                    'target': 4,
					"render": function(data, type, row, meta) {
						console.log(data);
						return '<span class="label label-primary">hi</span>';
					},
                    'orderable': false,
                    "className": "text-center"
                }]
			});
		});
	</script>
</body>
</html>