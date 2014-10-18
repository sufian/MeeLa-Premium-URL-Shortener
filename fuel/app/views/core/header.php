<div class="navbar navbar-meela shadow <?php if (Uri::Base() == Uri::Current() || Uri::Base().'home' == Uri::Current()) {echo "nav-remove top-minus-80";} else {echo "navbar-fixed-top";} ?>">
    <div class="container">
        <!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
    
        <?php
            $site_name = \Settings::Get('site_name');
            if(empty($site_name) === true)
            {
                $site_name = 'Mee.La';
            }
        ?>
        <!-- Be sure to leave the brand out there if you want it shown -->
        <a class="navbar-brand" href="<?php echo Uri::Base(); ?>" title="<?php echo $site_name; ?>"><?php echo $site_name; ?></a>
    
        <!-- Place everything within .navbar-collapse to hide it until above 768px -->
        <div class="nav-collapse collapse navbar-responsive-collapse <?php if (Uri::base(false).Uri::string() == Uri::Base()){echo "responsive-margin";} ?>">
            <ul class="nav navbar-nav pull-right">
                <?php if (Uri::base(false).Uri::string() != Uri::Base()): ?>
                    <li><a href="<?php echo Uri::Base();?>" title="Home">Home</a></li>
                <?php endif; ?>
                <?php if(Auth::check()): ?>
                    <?php if(Auth::member(5)): ?>
                        <li><a href="<?php echo Uri::Create('admin');?>" title="Admin Panel">Admin</a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?php echo Uri::Create('user');?>" title="User Dashboard">Dashboard</a></li>
                    <li><a href="<?php echo Uri::Create('user/settings');?>" title="User Profile"><?php echo Auth::get_profile_fields('fullname', 'Not Set'); ?></a></li>
                    <li><a href="<?php echo Uri::Create('logout');?>" title="Logout"><i class="icon-off icon-white"></i></a></li>
                    
                <?php else: ?>
                    <?php
                    $admin_only = \Settings::Get('admin_only');
                    if(empty($admin_only) === true || $admin_only): ?>
                        <li><a href="<?php echo Uri::Create('login');?>" title="Login">Login</a></li>
                        <li class="active"><a href="<?php echo Uri::Create('signup');?>" title="Signup">Sign Up</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>    
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</div><!-- /.navbar -->