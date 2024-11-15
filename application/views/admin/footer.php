		<footer class="main-footer">
			<div class="pull-right hidden-xs">
	            <b>Version</b> 1.0.0
	        </div>
	        <strong>Copyright &copy; 2024 ICT - Lifestrong Marketing Inc. All rights reserved.</strong>
		</footer>
		<div id="sidebar-overlay"></div>
	</div>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">ICT Helpdesk Information Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <p><?php echo $this->session->flashdata('success'); ?></p>
                    <?php elseif ($this->session->flashdata('error')): ?>
                        <p><?php echo $this->session->flashdata('error'); ?></p>
                    <?php else: ?>
                        <p>No message available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">ICT Helpdesk Information Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Modal -->
    <div id="printMsrfModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Print Filtered Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Use the filters below to narrow down the data you want to print:</p>

                    <!-- Form for Filters -->
                    <form id="printFilterForm">
                        <!-- Date Range Filter -->
                        <div class="form-group">
                            <label for="startDate">Start Date:</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>

                        <!-- Status Filter -->
                        <div class="form-group">
                            <label for="statusFilter">Status:</label>
                            <select class="form-control" id="statusFilter" name="statusFilter" required>
                                <option value="" disabled selected>Statuses</option>
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPrint">Print</button>
                </div>
            </div>
        </div>
    </div>


	<script src="<?php echo base_url();?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo base_url();?>/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= base_url(); ?>/assets/plugins/iCheck/icheck.min.js"></script>
	<script>
		$.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/raphael/raphael.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/morris.js/morris.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/moment/min/moment.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <script src="<?= base_url(); ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="<?= base_url(); ?>assets/dist/dist/js/adminlte.min.js"></script>
    <script src="<?= base_url(); ?>/assets/dist/dist/js/pages/dashboard.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?= base_url(); ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if($this->session->flashdata('success')): ?>
                $('#successModal').modal('show');
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                $('#errorModal').modal('show');
            <?php endif; ?>

            $('#it_approval_stat').on('change', function() {
                if (this.value == "Approved") {
                    $('#ictassign').show();
                    $('#reason').hide();
                } else if (this.value == "Rejected") {
                    $('#ictassign').hide();
                    $('#reason').show();
                }else if (this.value == "Resolved") {
                    $('#ictassign').hide(); 
                    $('#reason').hide(); 
                }
            });

            $(document).on('click', '.lock_btn', function() {
                var emp_id = $(this).data('empid');
                var emp_no = $(this).closest('tr').find('td:eq(0)').text();

                $('#modalLock').find('#employee_id').text(emp_no);
                $('#id').val(emp_id);

                $('#modalLock').modal('show');
            });

            $(document).on('click', '.unlock_btn', function() {
                var emp_id = $(this).data('empid');
                var emp_no = $(this).closest('tr').find('td:eq(0)').text();

                $('#modalUnlock').find('#employee_id').text(emp_no);
                $('#recid').val(emp_id);

                $('#modalUnlock').modal('show');
            });

            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

            $('input[type="checkbox"].minimal').on('ifChanged', function(event){
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#password').attr('type', 'text');
                    $('#cpassword').attr('type', 'text');
                } else {
                    $('#password').attr('type', 'password');    
                    $('#cpassword').attr('type', 'password');
                }
            });

            $('#tblUsers').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/employee",
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [{
                    text: 'Add Employee',
                    className: 'btn btn-info',
                    action: function (e, dt, node, config) {
                        // $("#modal-add-users").modal("show");
                        window.location.href = '<?= base_url(); ?>' + 'sys/admin/add/employee';
                    }
                }],
                "columnDefs": [{
                    'target': 2,
                    "data": "btn_action",
                    'orderable': false,
                    "className": "text-center"
                }]
            });

            let table = $('#tblTickets').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_msrf",
                    "type": "POST",
                    "data": function(d) {
                        // Add additional filter parameters to the DataTables request
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.statusFilter = $('#statusFilter').val();
                    }
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                /*"dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [
                    {
                        text: 'Print Data',
                        className: 'btn btn-primary',
                        action: function (e, dt, node, config) {
                            // Open the modal when the button is clicked
                            $('#printMsrfModal').modal('show');
                        }
                    }
                ]*/
            });

            // Handle the confirm print action inside the modal
            $('#confirmPrint').on('click', function() {
                // Prevent the modal from closing if the form is not valid
                var form = $('#printFilterForm')[0];
                
                // Check form validity
                if (!form.checkValidity()) {
                    form.reportValidity(); // Show validation errors
                    return; // Exit function if form is invalid
                }

                // Close the modal
                $('#printMsrfModal').modal('hide');

                // Redraw the table with new filter parameters
                table.ajax.reload(function() {
                    // After table is reloaded, trigger the print functionality
                    table.button('.buttons-print').trigger();
                }, false); // false to avoid resetting the page number
            });


            $('#tblTicketsTraccConcern').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_tracc_concern", 
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false
            });

            $('#tblTicketsTraccRequest').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_tracc_request",
                    "type": "POST"
                }, 
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false
            });
            

            $('#tblDepartment').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_departments",
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                'dom': "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [{
                    text: 'Add Department',
                    className: 'btn btn-info',
                    action: function (e, dt, node, config) {
                        // $("#modal-add-users").modal("show"); 
                        window.location.href = '<?= base_url(); ?>' + 'sys/admin/add/department';
                    }
                }],
                "columnDefs": [{
                    'target': 2,
                    "data": "btn_action",
                    'orderable': false,
                    "className": "text-center"
                }]
            });


        });
    </script>
</body>
</html>