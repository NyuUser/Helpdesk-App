<nav class="navbar navbar-static-top">
	<div class="container">
		<div class="navbar-header">
          	<a href="<?= base_url(); ?>" class="navbar-brand"><b>ICT</b> Helpdesk</a>
          	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            	<i class="fa fa-bars"></i>
          	</button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        	<ul class="nav navbar-nav">
        		<li class="active">
                    <a href="<?= base_url(); ?>sys/users/dashboard">Tickets<span class="sr-only">(current)</span></a>
                </li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Concerns <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">MSRF List Concern</a></li>
                        <li><a href="#">TRACC List Concern</a></li>
                    </ul>
				</li>
        	</ul>
        </div>
        <div class="navbar-custom-menu">
        	<ul class="nav navbar-nav">
        		<li class="dropdown user user-menu">
        			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
        				<span class="glyphicon glyphicon-user"></span>
        				<span class="hidden-xs"><?php echo $user_details['fname'] . " " . $user_details['mname'] . " " . $user_details['lname']; ?></span>
        			</a>
        			<ul class="dropdown-menu">
                		<li class="user-footer">
                  			<div class="pull-right">
                    			<a href="<?= site_url('Main/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                  			</div>
                		</li>
        			</ul>
        		</li>
        	</ul>
        </div>
	</div>
</nav>