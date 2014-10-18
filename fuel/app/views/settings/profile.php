<?php        
        if(empty($user->user_providers) === false)
        {
                $provider_array = array();
                foreach($user->user_providers as $provider)
                {
                        $provider_array[] = $provider->provider;
                }
        }
        else
        {
                $provider_array = null;
        }
        
        $facebook_api = \Settings::Get('facebook_app_id');
        $google_api = \Settings::Get('google_client_id');
        $twitter_api = \Settings::Get('twitter_consumer_key');
?>
<div class="form-small clearfix">

        <h2>Settings</h2>

        <form id="loginform" method="post">
            <input required class="margin-top-10 form-control input-large" name="full_name" type="text" placeholder="Full Name" value="<?php echo Auth::get_profile_fields('fullname', 'Not Set'); ?>">
            <input required class="margin-top-10 form-control input-large" name="email" type="text" placeholder="Email Address" value="<?php echo Auth::get_email(); ?>">
            <?php if (isset($user) === true) : ?>
                <?php
                $current_password = Session::get('current_password');
                if(empty($current_password) === false)
                {
                ?>
                    <div class="alert">You just reset your password we have entered in the current password for you as it was auto generated!</div>
                <?php
                }
                ?>
                <input class="margin-top-10 form-control input-large" name="current_password" type="password" placeholder="Password" <?php echo empty($current_password) === false ? 'value="'.$current_password.'"' : ''; ?>>
                <input class="margin-top-10 form-control input-large" name="new_password" type="password" placeholder="New Password">
            <?php endif;?>
            <button class="margin-top-10 btn btn-primary btn-large btn-block" type="submit">Update</button>  
        </form>
        <?php if(empty($facebook_api) === false || empty($google_api) === false || empty($twitter_api) === false): ?>
        <hr>
        <?php endif; ?>
        <?php
        if(empty($facebook_api) === false)
        {
                if(empty($provider_array) === true || in_array('Facebook',$provider_array) === false)
                {
                ?>
                        <a href="<?php echo Uri::Create('auth/login/facebook');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-facebook btn-embossed"><i class="icon-facebook-sign"></i> Connect Facebook Login</a>
                <?php
                }
                else
                {
                ?>
                        <a target="_blank" href="https://www.facebook.com/settings?tab=applications" class="margin-top-10 pull-left btn btn-large btn-block btn-facebook btn-embossed"><i class="icon-facebook-sign"></i> Disconnect Facebook Login</a>
                <?php
                }
        }
        ?>
        <?php if(empty($google_api) === false)
        {
                if(empty($provider_array) === true || in_array('Google',$provider_array) === false)
                {
                ?>
                        <a href="<?php echo Uri::Create('auth/login/google');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-primary btn-embossed"><i class="icon-google-sign"></i> Connect Google Login</a>
                <?php
                }
                else
                {
                ?>
                        <a target="_blank" href="https://accounts.google.com/b/0/IssuedAuthSubTokens?hl=en" class="margin-top-10 pull-left btn btn-large btn-block btn-primary btn-embossed"><i class="icon-google-sign"></i> Disconnect Google Login</a>
                        
                <?php
                }
        }
        ?>
        <?php if(empty($twitter_api) === false)
        {
                if(empty($provider_array) === true || in_array('Twitter',$provider_array) === false)
                {
                ?>
                        <a href="<?php echo Uri::Create('auth/login/twitter');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-twitter btn-embossed"><i class="icon-twitter-sign"></i> Connect Twitter Login</a>
                <?php
                }
                else
                {
                ?>
                
                        <a target="_blank" href="https://twitter.com/settings/applications" class="margin-top-10 pull-left btn btn-large btn-block btn-twitter btn-embossed"><i class="icon-twitter-sign"></i> Disconnect Twitter Login</a>
                        
                <?php
                }
        }
        ?>    
        <?php if(!empty($facebook_api) || !empty($google_api) || !empty($twitter_api)): ?>
        <br><br><br>
        <?php endif; ?>
    <?php
        if(\Settings::Get('api') == true)
        {
        ?>
            <hr>
            <h2>API Key:</h2>
            <input class="form-control input-lg" type="text" value="<?php echo $api_key; ?>">
        <?php
        }
    ?>
    
</div>
