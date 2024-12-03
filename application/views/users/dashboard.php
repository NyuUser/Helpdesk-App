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