<h1>Mee.la Installer</h1>
<h2>Support</h2>

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