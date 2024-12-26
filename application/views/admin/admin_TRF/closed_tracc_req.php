<?php

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Closed TRACC Request List
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
                        <table id="closed_req" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Requested By</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Dept. Head Approval Status</th>
                                    <th>ICT Approval Status</th>
                                    <th>Accomplished Date</th>
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
    let table = $('#closed_req').DataTable({
        "info": false,
        "serverSide": true,
        "processing": true,
        "searching": true,
        "ajax": {
            "url": "<?= base_url(); ?>DataTables/get_closed_tracc_request",
            "type": "POST",
        },
        "columns": [
            { "data": "ticket_id" },
            { "data": "requested_by" },
            { "data": "subject" },
            { "data": "priority" },
            { "data": "status" },
            { "data": "approval_status" },
            { "data": "it_approval_status" },
            { "data": "accomplished_by_date",
                "render": function(data, type, row) {
                    if (data) {
                        let date = new Date(data);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });
                        return formattedDate;
                    }
                    return ""; // Return empty string if no date
                }
            }
        ],
        "responsive": true,
        "autoWidth": false,
        "lengthChange": false,
    });
});

</script>