<div class="content-wrapper">
    <section class="content-header" style="margin-left: 35%;">
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-6" id="report_box">
                    <h1>Generate Reports</h1>
                    <div class="box" id="report_filter_box">

                    <table class="report_filter_form">
                        <tr>
                            <!-- Start Date Picker -->
                            <td><label for="start_date" class="report_label">Start Date:</label></td>
                            <td><input type="date" id="start_date" name="start_date" class="report_input"></td>
                        </tr>
                        <tr>
                            <!-- End Date Picker -->
                            <td><label for="end_date" class="report_label">End Date:</label></td>
                            <td><input type="date" id="end_date" name="end_date" class="report_input">
                        </tr>
                        <tr>
                            <!-- Status Picker -->
                            <td><label for="status" class="report_label">Status:</label></td>
                            <td>
                                <select id="status" class="report_input">
                                    <option value="">All</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="On Going">On Going</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <!-- Choose between MSRF or Tracc -->
                            <td><label for="ticket" class="report_label">Ticket:</label></td>
                            <td>
                                <select id="ticket" class="report_input">
                                    <option value="msrf">MSRF Ticket</option>
                                    <option value="tracc">Tracc Concern</option>
                                    <option value="request">Tracc Request</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2><button id="filter" class="report_button">Filter</button></td>
                        </tr>
                    </table>
                    
                </div>

                <div class="box-body" id="tblMsrfBox">
                    <table id="tblTicketsPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Requestor Name</th>
                                <th>Department</th>
                                <th>Date Requested</th>
                                <th>Date Needed</th>
                                <th>Asset Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="box-body" id="tblTraccBox" style="display: none">
                    <table id="tblTicketsTraccConcernPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Control Number</th>
                                <th>Reported By</th>
                                <th>Reported Date</th>
                                <th>Resolved Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="box-body" id="tblTraccRequest" style="display: none">
                    <table id="tblTicketsTraccRequestPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Requested By</th>
                                <th>Department</th>
                                <th>Date Requested</th>
                                <th>Company</th>
                                <th>Complete Details</th>
                                <th>Accomplished By</th>
                                <th>Accomplished Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>