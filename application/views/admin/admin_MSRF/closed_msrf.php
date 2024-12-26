<?php

?>

<div class="content-wrapper">
    <p>Closed MSRF List</p>
    <div>
        <table id="closed_msrf">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Requestor Name</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Dept. Head Approval Status</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
    let table = $('#closed_msrf').DataTable({
        "info": false,
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "<?= base_url(); ?>DataTables/get_closed_msrf",
            "type": "POST",
        },
        "columns": [
            { "data": "ticket_id" },
            { "data": "requestor_name" },
            { "data": "subject" },
            { "data": "priority" },
            { "data": "status" },
            { "data": "approval_status" }
        ]

    })
});

</script>