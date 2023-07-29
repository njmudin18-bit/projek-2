<nav class="navbar header-navbar pcoded-header">
	<div class="navbar-wrapper">
		<div class="navbar-logo">
			<a href="<?php echo base_url(); ?>adminx">
				<img class="img-fluid" src="<?php echo base_url(); ?>files/uploads/logos/<?php echo $perusahaan->logo_name; ?>" alt="<?php echo $perusahaan->nama; ?>" />
			</a>
			<a class="mobile-menu" id="mobile-collapse" href="#!">
				<i class="feather icon-menu icon-toggle-right"></i>
			</a>
			<a class="mobile-options waves-effect waves-light">
				<i class="feather icon-more-horizontal"></i>
			</a>
		</div>
		<div class="navbar-container container-fluid">
			<ul class="nav-left">
				<li class="header-search">
					<div class="main-search morphsearch-search">
						<div class="input-group">
							<span class="input-group-prepend search-close">
								<i class="feather icon-x input-group-text"></i>
							</span>
							<input type="text" class="form-control" placeholder="Enter Keyword123">
							<span class="input-group-append search-btn">
								<i class="feather icon-search input-group-text"></i>
							</span>
						</div>
					</div>
				</li>
				<li>
					<a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
						<i class="full-screen feather icon-maximize"></i>
					</a>
				</li>
			</ul>
			<ul class="nav-right">
				<li class="header-notification">
					<div class="dropdown-primary dropdown">
						<div class="dropdown-toggle" data-toggle="dropdown">
							<i class="feather icon-bell"></i>
							<span class="badge bg-c-red">5</span>
						</div>
						<ul class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
							<li>
								<h6>Notifications</h6>
								<label class="label label-danger">New</label>
							</li>
							<li>
								<div class="media">
									<img class="img-radius" src="<?php echo base_url(); ?>files/assets/images/profile.jpg" alt="Generic placeholder image">
									<div class="media-body">
										<h5 class="notification-user">John DoeXX</h5>
										<p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
										<span class="notification-time">30 minutes ago</span>
									</div>
								</div>
							</li>
							<li>
								<div class="media">
									<img class="img-radius" src="files/assets/images/avatar-3.jpg" alt="Generic placeholder image">
									<div class="media-body">
										<h5 class="notification-user">Joseph William</h5>
										<p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
										<span class="notification-time">30 minutes ago</span>
									</div>
								</div>
							</li>
							<li>
								<div class="media">
									<img class="img-radius" src="files/assets/images/avatar-4.jpg" alt="Generic placeholder image">
									<div class="media-body">
										<h5 class="notification-user">Sara Soudein</h5>
										<p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
										<span class="notification-time">30 minutes ago</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</li>
				<li class="header-notification">
					<div class="dropdown-primary dropdown">
						<div class="displayChatbox dropdown-toggle" data-toggle="dropdown">
							<i class="feather icon-message-square"></i>
							<span class="badge bg-c-green">3</span>
						</div>
					</div>
				</li>
				<li class="user-profile header-notification">
					<div class="dropdown-primary dropdown">
						<div class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo base_url(); ?>files/assets/images/profile.jpg" class="img-radius" alt="User-Profile-Image">
							<span>
								<?php
								$user_le 	= $this->session->userdata('user_level');
								$level 		= $this->Dashboard_model->getDatalevel($user_le);
								echo $this->session->userdata('user_realName');

								echo " (" . $this->session->userdata('user_nip') . ")";
								?>
							</span>
							<i class="feather icon-chevron-down"></i>
						</div>
						<ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
							<li>
								<a href="#">DEPT: <?php echo $this->session->userdata('user_dept_name'); ?></a>
							</li>
							<li class="userlist-box-pro">
								<a href="#">Roles : <?php echo strtoupper($level->roles_name); ?></a>
							</li>
							<?php
							if ($this->session->userdata('user_level') == '1') {
							?>
								<li>
									<a href="<?php echo base_url(); ?>users">
										<i class="feather icon-users"></i> Users
									</a>
								</li>
							<?php
							}
							?>
							<li>
								<a href="<?php echo base_url(); ?>users/user_profile">
									<i class="feather icon-user"></i> My Profile
								</a>
							</li>
							<li class="userlist-box-pro">
								<a href="<?php echo base_url(); ?>users/update_password">
									<i class="feather icon-lock"></i> Update Password
								</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>adminx/logout" onclick="clear_local_storage()">
									<i class="feather icon-log-out"></i> Logout
								</a>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>
<script type="text/javascript">
	function clear_local_storage() {
		localStorage.removeItem("data_qr");
	}
</script>