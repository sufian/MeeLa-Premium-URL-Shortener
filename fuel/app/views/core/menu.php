<ul class="nav nav-tabs">
    <?php
    // check for admin
    if(Auth::member(5))
    {
    ?>
        <li <?php echo Uri::Current() == Uri::Create('admin') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('admin');?>">Admin Dashboard</a>
        </li>
        <li <?php echo Uri::Current() == Uri::Create('admin/urls') || Uri::Current() == Uri::Create('admin/urls/preview') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('admin/urls');?>">Moderation</a>
        </li>
        <li <?php echo Uri::Current() == Uri::Create('admin/users') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('admin/users');?>">Users</a>
        </li>
        <li <?php echo Uri::Current() == Uri::Create('admin/settings') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('admin/settings');?>">Settings</a>
        </li>
    <?php    
    }
    ?>
        <li <?php echo Uri::Current() == Uri::Create('user') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('user');?>">Dashboard</a>
        </li>
       <li <?php echo Uri::Current() == Uri::Create('user/urls') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('user/urls');?>">My URLs</a>
        </li>
    <?php
    if(Module::loaded('image') === true)
    {
    ?>
        <li <?php echo Uri::Current() == Uri::Create('user/images') ? 'class="active"' : '';?>>
            <a href="<?php echo Uri::Create('user/images');?>">My Images</a>
        </li>
    <?php
    }
    ?>
    <?php
    if(Auth::member(5))
    {
    ?>    
        <li>
            <?php
            if(!Session::get('profiler'))
            {
            ?>
                <a href="<?php echo Uri::Create('admin/profiler');?>">Enable Profiler</a>
            <?php
            }
            else
            {
            ?>
                <a href="<?php echo Uri::Create('admin/profiler');?>">Disable Profiler</a>
            <?php
            }
            ?>
        </li>
    <?php
    }
    ?>
</ul>