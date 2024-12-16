<style>

.dashboard-table {
    margin-top: 30px;
    margin-bottom: 30px;
    margin-left: auto;
    margin-right: auto;
}

.table-data {
    padding: 5px;
    vertical-align: top;
}

.summary-table {
    width: 100%;
    height: 420px;
}

.summary {
    padding: 5px;
}

.summary-data {
    background-color: white;
    height: 100%;
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 0px 1px lightgray;
    transition: 0.5s;
}

.summary-data:hover {
    box-shadow: 0px 0px 5px lightgray;
    transition: 0.3s;
}

.summary-data-table {
	width: 100%;
}

.table-data-summary {
    background-color: white;
    padding: 20px;
    width: 570px;
    height: 420px;
    border-radius: 5px;
    box-shadow: 0px 0px 1px lightgray;
    transition: 0.5s;
}

.table-data-summary:hover {
    box-shadow: 0px 0px 5px lightgray;
    transition: 0.3s;
}


.table-data tr:hover {
    background-color: #ecf0f5;
}

.status-dropdown {
    padding: 5px;
    border-radius: 5px;
    border-color: lightgray;
    font-size: 11px;
}

.status-button {
    padding: 5px;
    width: 70px;
    border-radius: 5px;
    border-width: 0;
    background-color: #337ab7;
    color: white;
    font-size: 11px;
}

</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
            <h1>
          		Dashboard
          		<small>Control Panel</small>
        	</h1>
			<ol class="breadcrumb">
			    <li><a href="<?= base_url(); ?>sys/users/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="<?= base_url(); ?>sys/users/dashboard">Dashboard</a></li>
			</ol>
        </section>
		<section>
			<table class="dashboard-table">
				<tr>
					<td class="table-data" style="padding: 0;">
						<table class="summary-table">
							<tr>
								<td class="summary" colspan=3>
									<div class="summary-data">
										<h3>Hello, <?= $user_details['fname']; ?>!</h3>
										<p>Today is <?= date("F j, Y, l"); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="summary">
									<div class="summary-data">
										<b>MSRF</b>
										<table class="summary-data-table">
											<tr>
												<th>Total:</th>
												<td align="right"><b><?= $msrf['count']; ?></b></td>
											</tr>
											<tr>
												<td>In Progress Tickets:</td>
												<td align="right"><?= $msrf['In Progress']; ?></td>
											</tr>
											<tr>
												<td>On Going Tickets:</td>
												<td align="right"><?= $msrf['On Going']; ?></td>
											</tr>
											<tr>
												<td>Resolved Tickets:</td>
												<td align="right"><?= $msrf['Resolved']; ?></td>
											</tr>
											<tr>
												<td>Closed Tickets:</td>
												<td align="right"><?= $msrf['Closed']; ?></td>
											</tr>
											<tr>
												<td>Rejected Tickets:</td>
												<td align="right"><?= $msrf['Rejected']; ?></td>
											</tr>
											<tr>
												<td>Returned Tickets:</td>
												<td align="right"><?= $msrf['Returned']; ?></td>
											</tr>
										</table>
									</div>
								</td>
								<td class="summary">
									<div class="summary-data">
										<p><b>Tracc Concerns</b> <i>Total: <?= $concerns['count']; ?></i></p>
										Open Tickets: <?= $concerns['Open']; ?><br>
										Done Tickets: <?= $concerns['Done']; ?><br>
										In Progress Tickets: <?= $concerns['In Progress']; ?><br>
										Resolved Tickets: <?= $concerns['Resolved']; ?><br>
										Closed Tickets: <?= $concerns['Closed']; ?><br>
										Rejected Tickets: <?= $concerns['Rejected']; ?><br>
									</div>
								</td>
								<td class="summary">
									<div class="summary-data">
										<p><b>Tracc Requests</b> <i>Total: <?= $requests['count']; ?></i></p>
										Open Tickets: <?= $requests['Open']; ?><br>
										Approved Tickets: <?= $requests['Approved']; ?><br>
										In Progress Tickets: <?= $requests['In Progress']; ?><br>
										Resolved Tickets: <?= $requests['Resolved']; ?><br>
										Closed Tickets: <?= $requests['Closed']; ?><br>
										Rejected Tickets: <?= $requests['Rejected']; ?><br>
									</div>
								</td>
							</tr>
							<!-- <tr>
								<td class="summary" colspan=3>
									<div class="summary-data">
										<h4>Ticket Updates</h4>
										<table id="history" class="table">
											<thead>
												<tr>
													<th>ID</th>
													<th>Module</th>
													<th>Status</th>
													<th>Created Date</th>
												</tr>
											</thead>
											<tbody >
												<tr>
													<td>Hello</td>
													<td>Hello</td>
													<td>Hello</td>
													<td>Hello</td>
												</tr>
												<tr>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
												</tr>
												<tr>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
												</tr>
												<tr>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
												</tr>
												<tr>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
												</tr>
												<tr>
													<td>Hello</td>
													<td>Hello</td>
													<td>Hello</td>
													<td>Hello</td>
												</tr>
												<tr>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
												</tr>
												<tr>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
												</tr>
												<tr>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
													<td>Hi</td>
												</tr>
												<tr>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
													<td>Wazzap</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr> -->
						</table>
					
					</td>
					<td class="table-data">
						<div class="table-data-summary">
							<a href="list/tickets/msrf">
								<h2>MSRF Concerns</h2>
							</a>
							<select id="msrfStatus" class="status-dropdown">
								<option value="">All Status</option>
								<option>Open</option>
								<option>In Progress</option>
								<option>On Going</option>
								<option>Resolved</option>
								<option>Closed</option>
								<option>Rejected</option>
								<option>Returned</option>
							</select>
							<button id="filterMsrf" class="status-button">Filter</button>
							<table id="tblMsrf" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-data">
						<div class="table-data-summary">
							<a href="list/tickets/tracc_concern">
								<h2>Tracc Concerns</h2>
							</a>
							<select id="concernStatus" class="status-dropdown">
								<option value="">All Status</option>
								<option>Open</option>
								<option>Done</option>
								<option>In Progress</option>
								<option>Rejected</option>
								<option>Resolved</option>
								<option>Closed</option>
							</select>
							<button id="filterConcern" class="status-button">Filter</button>
							<table id="tblConcerns" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
						</div>
					</td>
					<td class="table-data">
						<div class="table-data-summary">
							<a href="list/tickets/tracc_request">
								<h2>Tracc Requests</h2>
							</a>
							<select id="requestStatus" class="status-dropdown">
								<option value="">All Status</option>
								<option>Open</option>
								<option>Approved</option>
								<option>In Progress</option>
								<option>Rejected</option>
								<option>Resolved</option>
								<option>Closed</option>
							</select>
							<button id="filterRequest" class="status-button">Filter</button>
							<table id="tblRequests" class="table">
								<thead>
									<tr>
										<th>Ticket ID</th>
										<th>Status</th>
									</tr>
								</thead>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</section>
    </div>
</div>