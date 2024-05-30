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
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/iCheck/all.css">
	<style>
		#loading {
    		background: url('spinner.gif') no-repeat center center;
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			z-index: 9999999;
		}
	</style>
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
                        Ticket Creation
                        <small>Ticket</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href=""><i class="fa fa-users"></i> Home</a></li>
                        <li class="active">Creation Tickets</li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tracc" data-toggle="tab">Ticket for TRACC</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tracc">
                                        <section id="new">
                                            <div class="row">
                                                <form action="<?= site_url('Main/users_creation_tickets_tracc'); ?>" method="POST">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>TRN No#</label>
                                                            <input type="text" name="trn_number" id="trn_number" class="form-control" value="<?= $traccNumber; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Requestor</label>
                                                            <input type="text" class="form-control select2" name="name" value="<?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?>" style="width: 100%;">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Department</label>
                                                            <?php foreach ($departments as $department): ?>
																<?php if ($users_det['dept_id'] == $department['recid']): ?>
																	<input type="text" name="department_description" id="department_description" value="<?php echo $department['dept_desc']; ?>" class="form-control select2" style="width: 100%;" readonly/>
																	<input type="hidden" name="dept_id" value="<?php echo $users_det['dept_id']; ?>">
																	<input type="hidden" name="sup_id" value="<?php echo $users_det['sup_id']; ?>">
																<?php endif; ?>
															<?php endforeach; ?>
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
                                                        <div class="form-group row">
                                                            <div class="col-md-4">

                                                            </div>
                                                            <label class="col-md-1">
                                                                <input type="checkbox" name="r3[]" value="LMI" class="minimal">
                                                                LMI
                                                            </label>
                                                            <label class="col-md-1">
                                                                <input type="checkbox" name="r3[]" value="RGDI" class="minimal">
                                                                RGDI
                                                            </label>
                                                            <label class="col-md-1">
                                                                <input type="checkbox" name="r3[]" value="LPI" class="minimal">
                                                                LPI
                                                            </label>
                                                            <label class="col-md-1">
                                                                <input type="checkbox" name="r3[]" value="SV" class="minimal">
                                                                SV
                                                            </label>
                                                            <div class="col-md-4">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Master File</label>
                                                            <select name="master_file" id="master_file" class="form-control">
                                                                <option>Please select Master File concern</option>
                                                                <option value="new">New/Add</option>
                                                                <option value="update">Update</option>
                                                                <option value="orientation">Tracc Orientation</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4" id="new_add" style="display:none;">
                                                            <label>New/Add</label>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Item" class="minimal">
                                                                Item
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Customer" class="minimal">
                                                                Customer
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Supplier" class="minimal">
                                                                Supplier
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Warehouse" class="minimal">
                                                                Warehouse
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Bin No." class="minimal">
                                                                Bin No.
                                                                </label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Others</lable>
                                                                <input type="text" name="others" id="others" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4" id="update" style="display:none;">
                                                            <label>Update</label>
                                                            <div>
                                                                <label>
                                                                    <input type="radio" name="r1" value="System Date Lock" class="minimal">
                                                                    System Date Lock
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="User File Access" class="minimal">
                                                                User File Access
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Item Details" class="minimal">
                                                                Item Details
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="Customer Details" class="minimal">
                                                                Customer Details
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                    <input type="radio" name="r1" value="Supplier Details" class="minimal">
                                                                    Supplier Details
                                                                </label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Others</lable>
                                                                <input type="text" name="others" id="others" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-4" id="orientation" style="display:none;">
                                                            <label>TRACC Orientation</label>
                                                            <div>
                                                                <label>
                                                                <input type="radio" name="r1" value="GPS Account" class="minimal">
                                                                GPS Account
                                                                </label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Others</lable>
                                                                <input type="text" name="others" id="others" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Purpose *</label>
                                                            <input type="text" name="purpose" id="purpose" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Complete Details</label>
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
        </div>
    </div>
    <script src="<?php echo base_url();?>/assets/dist/users_template/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/fastclick/lib/fastclick.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/adminlte.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/demo.js"></script>
	<script src="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/ckeditor/ckeditor.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
	<script type="text/javascript">
		$(function() {
            CKEDITOR.replace('editor1')
            $('.textarea').wysihtml5();
            
            $('#category').change(function() {
            	var val = $(this).val();
            	if(val === "others") {
            		$("#specify").show();
            	} else {
		            $("#specify").hide();
		        }
            });
        });

        $(document).ready(function() {
        	getDate();

            $('#master_file').on('change', function() {
                if (this.value == "new") {
                    $("#new_add").show();
                    $("#update").hide();
                    $("#orientation").hide();
                } else if (this.value == "update") {
                    $("#update").show();
                    $("#new_add").hide();
                    $("#orientation").hide();
                } else if (this.value == "orientation") {
                    $("#orientation").show();
                    $("#new_add").hide();
                    $("#update").hide();
                } else {
                    alert("Please select master file concern");
                    $("#new_add").hide();
                    $("#update").hide();
                    $("#orientation").hide();
                }
            });
        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        });

        function getDate() {
    		var today = new Date();
			document.getElementById("date_req").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
		}
	</script>
</body>
</html>