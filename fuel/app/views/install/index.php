 <!DOCTYPE html>
<html>
<head>
  <title>MeeLa Installer</title>

  <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/base.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <style type="text/css">
  .green { color: green; }
  .red { color: red; }
  </style>
</head>
<body>



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



<div class="form-small clearfix margin-bottom-20">
<h3>Mee.la Installer</h3>
<hr>
<div class="form-group">
    <label>CHMOD Check:</label>
    <p>Make sure these directories are chmod 777.</p>
    <hr>
    <p><strong>assets/cache :</strong>
        <?php
            if(empty($public_cache) === false)
            {
                if($public_cache == '0777' || $public_cache == '0775')
                {
                    echo '<i class="green icon-ok icon-large"></i>';
                }
                else
                {
                    echo '<i class="red icon-remove icon-large"></i>';
                }
            }
            else
            {
                echo 'Directory is missing please create! <i class="red icon-remove icon-large"></i>';
            }
        ?>
    </p>
    <p><strong>fuel/app/config/production :</strong>
        <?php
            if(empty($config) === false)
            {
                if($config == '0777' || $config == '0775')
                {
                    echo '<i class="green icon-ok icon-large"></i>';
                }
                else
                {
                    echo '<i class="red icon-remove icon-large"></i>';
                }
            }
            else
            {
                echo 'Directory is missing please create! <i class="red icon-remove icon-large"></i>';
            }
        ?>
    </p>
    <p><strong>fuel/app/cache :</strong>
        <?php
            if(empty($cache) === false)
            {
                if($cache == '0777' || $cache == '0775')
                {
                    echo '<i class="green icon-ok icon-large"></i>';
                }
                else
                {
                    echo '<i class="red icon-remove icon-large"></i>';
                }
            }
            else
            {
                echo 'Directory is missing please create! <i class="red icon-remove icon-large"></i>';
            }
        ?>
    </p>
</div>
<hr>
<form method="POST" role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">DB Host</label>
    <input class="form-control input-large" name="hostname" type="text" value="localhost">
   </div>

   <div class="form-group">
    <label for="exampleInputEmail1">DB Name</label>
    <input class="form-control input-large" name="database" type="text" value="meela">
   </div>
   
   <div class="form-group">
      <label for="exampleInputEmail1">DB User</label>
      <input class="form-control input-large" name="db_username" type="text">
   </div>
   
   <div class="form-group">
    <label for="exampleInputEmail1">DB Password</label>
    <input class="form-control input-large" name="db_password" type="password">
   </div>
   


    <h3>Admin Account Info</h3> 

    <div class="form-group">
      <label for="exampleInputEmail1">Username</label>
      <input class=" form-control input-large" name="username" type="text" placeholder="Username">
    </div>
   
    <div class="form-group">
      <label for="exampleInputEmail1">First Name</label>
      <input class="form-control input-large" name="name" type="text" placeholder="First Name">
    </div>
   
    <div class="form-group">
      <label for="exampleInputEmail1">Email Address</label>
      <input class="form-control input-large" name="email" type="text" placeholder="Email Address">
    </div>
   
    <div class="form-group">
      <label for="exampleInputEmail1">Password</label>
      <input class="form-control input-large" name="password" type="password" placeholder="Password">
    </div>
   
    <button class="btn btn-default btn-lg btn-block">Submit</button>
</form>
</div>


<script>
    $(document).ready(function()
    {
        $('#migrate_button').click(function()
        {
            $('#migrate').toggle();
            
            if($(this).html() == 'Yes')
            {
                $(this).html('No');
            }
            else
            {
                $(this).html('Yes');
            }
            
        });
        
        
    });
    
    
    
</script>


</body>
</html>