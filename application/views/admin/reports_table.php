<div class="content-wrapper">
    <section class="content-header" style="margin-left: 35%;">
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-6" style="width: auto; margin-left: 35%; margin-top: 10px;">
                    <h1>Generate Reports</h1>
                    <div class="box" style="width: 440px; padding: 20px;">

                    <table style="height: 300px; width: 400px;">
                        <tr>
                            <!-- Start Date Picker -->
                            <td><label for="start_date" style="font-size: 18px;">Start Date:</label></td>
                            <td><input type="date" id="start_date" name="start_date" style="border-width: 0; padding: 7px; border-radius: 15px; width: 100%;"></td>
                        </tr>
                        <tr>
                            <!-- End Date Picker -->
                            <td><label for="end_date" style="font-size: 18px;">End Date:</label></td>
                            <td><input type="date" id="end_date" name="end_date" style="border-width: 0; padding: 7px; border-radius: 15px; width: 100%;">
                        </tr>
                        <tr>
                            <!-- Status Picker -->
                            <td><label for="status" style="font-size: 18px;">Status:</label></td>
                            <td>
                                <select id="status" style="border-width: 0; padding: 7px; border-radius: 15px; width: 100%;">
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
                            <td><label for="ticket" style="font-size: 18px;">Ticket:</label></td>
                            <td>
                                <select id="ticket" style="border-width: 0; padding: 7px; border-radius: 15px; width: 100%;">
                                    <option value="msrf">MSRF Ticket</option>
                                    <option value="tracc">Tracc Concern</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2><button id="filter" style="padding: 7px; width: 100%; border-radius: 15px; border-width: 0; background-color: lightgreen;">Filter</button></td>
                        </tr>
                    </table>

                    <!-- Filter Button -->
                    
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
                <div class="box-body" id="tblTraccBox">
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

            </div>
        </div>
    </section>
</div>