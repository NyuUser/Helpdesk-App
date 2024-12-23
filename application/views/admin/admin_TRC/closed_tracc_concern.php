<?php

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Closed TRACC Concern List
            <small>Control Panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tickets</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="closed_concern" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Control Number</th>
                                    <th>Reported By</th>
                                    <th>Priority</th>
                                    <th>Company</th>
                                    <th>Dept. Head Approval Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

$(document).ready(function() {
    let table = $('#closed_concern').DataTable({
        "info": false,
        "serverSide": true,
        "processing": true,
        "searching": true,
        "ajax": {
            "url": "<?= base_url(); ?>DataTables/get_closed_tracc_concern",
            "type": "POST",
        },
        "columns": [
            { "data": "control_number" },
            { "data": "reported_by" },
            { "data": "priority" },
            { "data": "company" },
            { "data": "approval_status" }
        ],
        "responsive": true,
        "autoWidth": false,
        "lengthChange": false,
    });
});

</script>