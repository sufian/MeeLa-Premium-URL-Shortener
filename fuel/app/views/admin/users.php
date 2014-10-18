<?php echo View::Forge('core/menu');?>

<div class="meela_box meelab">
        <div class="col-lg-4 pull-right">
        <form method="get">
            <div class="<?php echo isset($search) === true ? 'input-group' : '';?>">
                <input placeholder="Search for Users" class="form-control" name="search" type="text" value="<?php echo isset($search) === true ? $search : '' ;?>">
                <?php
                    if(isset($search) === true)
                    {
                    ?>
                        <span class="input-group-addon">
                            <a href="<?php echo Uri::Create(Uri::Current());?>"> <i class="icon-remove"></i></a>
                        </span>
                    <?php
                    }
                    ?>
            </div>
        </form>
    </div>
     
<?php
if(empty($users) === false)
{   
    echo htmlspecialchars_decode($pagination);
?>
<form method="post">
    <table class="sortable table table-condensed table-striped" summary="Shows the recently visited urls">
        <thead>
            <th>Full Name</th>
            <th>Email</th>
            <th>User Level</th>
            <th>API Key</th>
        </thead>
        <tbody>
            <?php
            $options = array(
                'Admin' => '5',
                'Member' => '4',
                'User' => '3',
                'Banned' => '1'  
            );
               
                foreach($users as $user)
                {
                ?>
                        <tr>
                                <td><a data-text="<?php echo $user->email;?> will be removed!" class="confirm" href="<?php echo Uri::Create('users/remove/'.$user->id);?>"><i style="cursor: pointer;" class='icon-remove'></i></a> <?php echo $user['user_fname'];?> </td>
                                <td><?php echo $user->email;?></td>
                                <td>
                                    <select name="user_role_<?php echo $user->id;?>">
                                                <?php
                                                    foreach($options as $role => $value)
                                                    {
                                                        if($value === $user->group_id)
                                                        {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        else
                                                        {
                                                            $selected = '';
                                                        }
                                                    ?>
                                                        <option value="<?php echo $value;?>" <?php echo $selected;?>><?php echo $role;?></option>
                                                    <?php
                                                    }
                                                ?>
                                        </select>
                                </td>
                                <td><?php echo $user['api_key'];?></td>
                        </tr>
                <?php
                }
            ?>
        </tbody>
    </table>
    <button class="btn btn-success">Update Users</button>
</form>
<?php
echo htmlspecialchars_decode($pagination);
}
else
{
?>
<div>
    No Users found!
</div>
<?php
}
?>
</div>
