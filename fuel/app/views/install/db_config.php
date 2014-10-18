<h1>Mee.la Installer</h1>
<h2>DB Config</h2>

 <?php
    $error = Session::get('error');
    $success = Session::get('success');
    
    if(empty($error) === false)
    {
      Session::delete('error');
    ?>
        <div style="text-align: center;" class="alert alert-danger"><?php echo $error;?></div>
    <?php
    }
    if(empty($success) === false)
    {
      Session::delete('success');
    ?>
        <div style="text-align: center;" class="alert alert-success"><?php echo $success;?></div>
    <?php
    }
?>
<p>
    Your config could not be created, please copy and paste the following into file "<?php echo DOCROOT;?>fuel/app/config/production/db.php"
</p>
<pre>
    <?php echo '&lt;?php<br>'.$config;?>
</pre>


<a href="<?php Uri::Create('install/force_login');?>">Finish Installer</a>