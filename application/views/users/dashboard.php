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
								<td class="summary">
									<div class="summary-data">
										<p><b>MSRF</b> <i>Total: <?= $msrf['count']; ?></i></p>
										Open Tickets: <?= $msrf['Open']; ?><br>
										In Progress Tickets: <?= $msrf['In Progress']; ?><br>
										On Going Tickets: <?= $msrf['On Going']; ?><br>
										Resolved Tickets: <?= $msrf['Resolved']; ?><br>
										Closed Tickets: <?= $msrf['Closed']; ?><br>
										Rejected Tickets: <?= $msrf['Rejected']; ?><br>
										Returned Tickets: <?= $msrf['Returned']; ?><br>
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
							<tr>
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
							</tr>
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