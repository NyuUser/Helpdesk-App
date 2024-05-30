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
			                    				<form action="<?= site_url('Main/users_creation_tickets_msfr'); ?>" method="POST">
			                    					<div class="col-md-12">
			                    						<div class="form-group">
			                    							<label>MSR#</label>
			                    							<input type="text" name="msrf_number" id="msrf_number" class="form-control" value="<?= $msrfNumber; ?>" readonly>
			                    						</div>
			                    					</div>
			                    					<div class="col-md-6">
			                                            <div class="form-group">
			                                                <label>Requestor</label>
			                                                <input type="text" name="name" value="<?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?>" class="form-control select2" style="width: 100%;" readonly>
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
        });

        function getDate() {
    		var today = new Date();
			document.getElementById("date_req").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
		}
	</script>
</body>
</html>