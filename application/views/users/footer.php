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

	<script src="<?php echo base_url();?>assets/dist/users_template/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/fastclick/lib/fastclick.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/adminlte.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/dist/js/demo.js"></script>
	<script src="<?= base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script src="<?= base_url(); ?>assets/dist/users_template/bower_components/ckeditor/ckeditor.js"></script>
	<script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/assets/dist/users_template/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
	<script>

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
			console.log("JavaScript is running");
			$(".close").click(function(){
				$("#modal-success-users, #modal-error-users").modal("hide");
			});
			
			$('#it_status').on('change', function() {
				var val = $(this).val();
				if(val === "") {
					$('#form-add-submit-button').prop("disabled", true);
				} else {
					$('#form-add-submit-button').prop("disabled", false);
				}
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
			
			$('#tblMsrfConcern').DataTable({
				"serverSide": true,
                "processing": true,
                "ajax": {
                	"url": "<?= base_url(); ?>DataTables/get_msrf_ticket",
                	"type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
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
			getDate();
        });

		

        

        function getDate() {
			var today = new Date();
			document.getElementById("date_req").value = ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2) + '-' + today.getFullYear();
		}
	</script>
</body>
</html>