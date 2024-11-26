<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?= base_url(); ?>sys/admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview <?= ($active_menu == 'system_tickets_list' || $active_menu == 'open_tickets' || $active_menu == 'other_menu' || $active_menu == 'msrf' || $active_menu == 'tracc_concern' || $active_menu == 'tracc_request') ? 'active' : ''; ?>">
            <!-- <li class="treeview <?= ($active_menu == 'system_tickets_list' || ($active_menu == 'open_tickets' && $this->uri->segment(5) == 'list' && $this->uri->segment(6) == 'ticket') || $active_menu == 'other_menu' || $active_menu == 'msrf' || $active_menu == 'tracc_concern' || $active_menu == 'tracc_request') ? 'active' : ''; ?>"> -->
                <a href="#">
                    <i class="fa fa-ticket"></i> <span>Tickets</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'msrf') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/msrf"><i class="fa fa-circle-o"></i> MSRF Form List</a></li>
                   
                    <li class="<?= ($active_menu == 'tracc_concern') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_concern"><i class="fa fa-circle-o"></i> TRACC Concern List</a></li>
      
                    <li class="<?= ($active_menu == 'tracc_request') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_request"><i class="fa fa-circle-o"></i> TRACC Request List</a></li>
   
                    <!-- <li class="treeview <?= ($active_menu == 'open_tickets') ? 'active' : ''; ?>">
                        <a href="#"><i class="fa fa-folder-open-o"></i> Open Tickets
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($active_menu == 'open_tickets' && $this->uri->segment(5) == 'list' && $this->uri->segment(6) == 'ticket') ? 'active' : ''; ?>">
                                <a href="<?= base_url(); ?>sys/admin/list/ticket/msrf"><i class="fa fa-circle-o"></i> MSRF Form List</a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_concern"><i class="fa fa-circle-o"></i> TRACC Concern List</a>
                            </li>
                            <li>
                               <a href="<?= base_url(); ?>sys/admin/list/ticket/tracc_request"><i class="fa fa-circle-o"></i> TRACC Request List</a>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </li>
            <li class="treeview <?= ($active_menu == 'system_administration' || $active_menu == 'users' || $active_menu == 'team') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-gear"></i> <span>System Administration</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($active_menu == 'users') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/users"><i class="fa fa-users"></i> Users</a></li>
                    <li class="<?= ($active_menu == 'team') ? 'active' : ''; ?>"><a href="<?= base_url(); ?>sys/admin/team"><i class="fa solid fa-user-plus"></i> Departments</a></li>
                </ul>
            </li>
            <li class="<?= ($active_menu == 'print') ? 'active' : ''; ?>">
                <a href="<?= base_url(); ?>sys/admin/print">
                    <i class="fa fa-print"></i> <span>Print Report</span>
                </a>
            </li>
        </ul>
	</section>
</aside>