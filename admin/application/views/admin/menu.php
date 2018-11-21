<body class="page-body">

    <div class="page-container">
        <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

        <!-- Add "fixed" class to make the sidebar fixed always to the browser viewport. -->
        <!-- Adding class "toggle-others" will keep only one menu item open at a time. -->
        <!-- Adding class "collapsed" collapse sidebar root elements and show only icons. -->
        <div class="sidebar-menu toggle-others fixed">

            <div class="sidebar-menu-inner">

                <header class="logo-env">

                    <!-- logo -->
                    <div class="logo">
                        <h1 class="menuLogo">10k-Club</h1>
                        <!--<a href="<?php echo site_url('dashboard'); ?>" class="logo-expanded">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png" width="80" alt="" />
                        </a>

                        <a href="<?php echo site_url('dashboard'); ?>" class="logo-collapsed">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png" width="40" alt="" />
                        </a>-->
                         <!-- <h3 style="color:#fff">10k-Club</h3>  -->
<!--                        <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="" width="150" />-->
                    </div>

                    <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
                    <div class="mobile-menu-toggle visible-xs">
                        <a href="#" data-toggle="user-info-menu">
                            <i class="fa-arrow-down"></i>
                            <!--<span class="badge badge-success">7</span>-->
                        </a>

                        <a href="#" data-toggle="mobile-menu">
                            <i class="fa-bars"></i>
                        </a>
                    </div>

                </header>

                <ul id="main-menu" class="main-menu">
                    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                    <li <?php if($this->uri->segment(1) == 'dashboard'){ ?>class="active"
                        <?php } ?>>
                            <a href="<?php echo site_url('dashboard'); ?>">
                                <i class="fa fa-dashboard"></i>
                                <span class="title">Dashboard</span>
                            </a>
                    </li>
                    
                    
                    
                    
                    <li <?php if($this->uri->segment(2) == 'favourite_list'){ ?>class="active"
                        <?php } ?>>
                            <a href="<?php echo site_url('admin/favourite_list'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Favourites</span>
                            </a>
                    </li>
                    
                    
  

                </ul>

            </div>

        </div>

        <div class="main-content">

            <nav class="navbar user-info-navbar" role="navigation">
                <!-- User Info, Notifications and Menu Bar -->

                <!-- Left links for user info navbar -->
                <!--<ul class="user-info-menu left-links list-inline list-unstyled">

                    <li class="hidden-sm hidden-xs">
                        <a href="#" data-toggle="sidebar">
                            <i class="fa-bars"></i>
                        </a>
                    </li>

                </ul>-->
                <!-- Left links for user info navbar -->
                <ul class="user-info-menu left-links list-inline list-unstyled">

<!--                    <li class="hidden-sm hidden-xs">
                        <a href="#" data-toggle="sidebar">
                            <i class="fa-bars"></i>
                        </a>
                    </li>-->
<!--                    <li class="dropdown hover-line">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa-bell-o"></i>
                            <span class="badge badge-green" id="count_notif">15</span>
                        </a>

                        <ul class="dropdown-menu messages">
                            <li>
                                <ul class="dropdown-menu-list list-unstyled ps-scrollbar" id="noti_list">

                                </ul>
                            </li>

                            <li class="external">
                                <a href="#">
                                    <span>Close</span>
                                    <i class="fa-link-ext"></i>
                                </a>
                            </li>
                        </ul>
                    </li>-->

<!--                    <li class="dropdown hover-line">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa-comments-o"></i>
                            <span id="chat_count" class="badge badge-purple"></span>
                        </a>

                        <ul class="dropdown-menu notifications">
                            <li class="top">
                                <p class="small">
                                                                        <a href="#" class="pull-right">Mark all Read</a>
                                    You have <strong id="count_chat_notif">0</strong> new messages.
                                </p>
                            </li>

                            <li>
                                <ul class="dropdown-menu-list list-unstyled ps-scrollbar" id="chat_list">

                                </ul>
                            </li>

                            <li class="external">
                                <a href="#">
                                    <span>Close</span>
                                    <i class="fa-link-ext"></i>
                                </a>
                            </li>
                        </ul>
                    </li>-->

                </ul>

                <!-- Right links for user info navbar -->
                <ul class="user-info-menu right-links list-inline list-unstyled">

                    <li class="dropdown user-profile">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url(); ?>assets/images/user-4.png" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
                            <span>
                                <?php
                                if ($this->session->userdata('admin_email'))
                                    echo $this->session->userdata('admin_email');
                                ?>
                                <i class="fa-angle-down"></i>
                            </span>
                        </a>

                        <ul class="dropdown-menu user-profile-menu list-unstyled">
                            <li>
                                <a href="<?php echo site_url('change-password'); ?>">
                                    <i class="fa-wrench"></i> Change Password
                                </a>
                            </li>
                            <li class="last">
                                <a href="<?php echo site_url('logout'); ?>">
                                    <i class="fa-lock"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
                
            </nav>

<!-- Modal 4 (Confirm)-->
	<div class="modal fade" id="modal-4" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				
				<div class="modal-header">
					<h4 class="modal-title">Alert</h4>
				</div>
				
				<div class="modal-body" id="AlertMsgId">
				
					New Tablet Added! Please allocate to student!
					
				</div>
				
				<div class="modal-footer" id="AlertButtonId">
                                    <a href="<?php echo site_url('admin/tablet_allocation'); ?>"><button type="button" class="btn btn-info" >Continue</button></a>
				</div>
			</div>
		</div>
	</div>

            
            