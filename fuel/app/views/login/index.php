<?php 
$facebook_api = \Settings::Get('facebook_app_id');
$google_api = \Settings::Get('google_client_id');
$twitter_api = \Settings::Get('twitter_consumer_key');
if (!Auth::check()) : ?>
<div class="form-small clearfix">
    <h3>Login</h3>
    <form id="loginform" method="post">
        <?php if(empty($errors) === false) { ?>
            <div class="alert">
            <ul>
                <?php 
                if(is_array($errors)) {
                    foreach($errors as $error) {
                        echo '<li>'.$error.'</li>';
                    }
                }
                else {
                    echo '<li>'.$errors.'</li>';
                } 
            ?>
            </ul>
        </div>
        <?php } ?>
        <input required class="margin-top-10 form-control input-large" name="username" type="text" placeholder="Username">
        <input required class="margin-top-10 form-control input-large" name="password" type="password" placeholder="Password">
        <button id="login_btn" class="margin-top-10 btn btn-primary btn-large btn-block" type="submit">Login</button>
        <div class="margin-bottom-10 margin-top-10 text-center"><small><a href="<?php echo Uri::Create('login/recover');?>">forgot your password ?</a></small></div>
        <?php if(empty($facebook_api) === false) { ?>
            <a href="<?php echo Uri::Create('auth/login/facebook');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-primary btn-embossed"><i class="icon-facebook-sign"></i> Facebook Login</a>
        <?php } ?>
        <?php if(empty($google_api) === false) { ?>
            <a href="<?php echo Uri::Create('auth/login/google');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-primary btn-embossed"><i class="icon-google-sign"></i> Google Login</a>
        <?php } ?>
        <?php if(empty($twitter_api) === false) { ?>
            <a href="<?php echo Uri::Create('auth/login/twitter');?>" class="margin-top-10 pull-left btn btn-large btn-block btn-primary btn-embossed"><i class="icon-twitter-sign"></i> Twitter Login</a>
        <?php } ?>
    </form>
</div>
<?php else : ?>
<a href="logout">Logout</a>
<?php endif; ?>
