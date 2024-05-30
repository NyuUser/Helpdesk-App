		<footer class="main-footer">
			<div class="pull-right hidden-xs">
	            <b>Version</b> 1.0.0
	        </div>
	        <strong>Copyright &copy; 2024 ICT - Lifestrong Marketing Inc. All rights reserved.</strong>
		</footer>
		<div id="sidebar-overlay"></div>
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
    
    <?php if ($this->session->flashdata('success')): ?>
        
    <?php endif; ?>
    <script type="text/javascript">
        $(document).ready(function() {
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

            $('#tblTickets').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/all_tickets_msrf",
                    "type": "POST"
                },
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false
            });
        });
    </script>
</body>
</html>