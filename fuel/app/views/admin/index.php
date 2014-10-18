<?php

$config_array = array(
    'Site Name' => array(
        'default_value' => 'Mee.la!',
        'description' => 'Title that is in the header',
        'type' => 'text'
    ),
    'Maintenance Mode' => array(
        'default_value' => 'false',
        'description' => '?',
        'type' => 'boolean'
    ),
    'Site description' => array(
        'default_value' => '<h2>What is MeeLa?</h2><p>Welcome to Mee.La! Are you sick of sharing long URL\'s to your friends? Would you like to rickroll a friend without him having any idea about the link you\'re referring him to? Then you\'ve come to the right place! Enter any URL in the above field, and we will give you a short and unique mee.la URL that you can share. Gone are the days where you need to email people long complicated links, you can just MeeLa it and tell them the simple link which is easy to remember.</p>',
        'description' => 'Shows the description of this site on the main page (if empty wont show)',
        'type' => 'textarea'
    ),
    'Title Descrption' => array(
        'default_value' => '<h2>Shorten your long URLs, Just paste them below.</h2>',
        'description' => 'Shows the description right below title',
        'type' => 'textarea'
    ),
    'Top 3 Pods' => array(
        'default_value' => true,
        'description' => 'Shows total urls, total url visits, and a random url',
        'type' => 'boolean'
    ),
    'Recently Viewed Table' => array(
        'default_value' => true,
        'description' => 'Shows a table of the recently viewed urls',
        'type' => 'boolean'
    ),
    'Recently Created Table' => array(
        'default_value' => true,
        'description' => 'Shows a table of the recently created urls',
        'type' => 'boolean'
    ),
    'Most Popular Table' => array(
        'default_value' => true,
        'description' => 'Shows a table of the most popular urls',
        'type' => 'boolean'
    ),
    'API' =>  array(
        'default_value' => 'true',
        'description' => 'If you want people to be able to use the API ',
        'type' => 'boolean',
    ),
    'Facebook APP ID' => array(
        'default_value' => '',
        'description' => 'If you use facebook login you can put it here',
        'type' => 'text'
    ),
    'Facebook APP Secret Key' => array(
        'default_value' => '',
        'description' => 'If you use facebook login you can put it here',
        'type' => 'text'
    ),
    'Twitter Consumer Key' => array(
        'default_value' => '',
        'description' => 'If you use twitter login you need this key from the dev api, To have twitter login working you must also put the call back as '.Uri::Base().'/twitter/callback Otherwise it will not work!',
        'type' => 'text'
    ),
    'Twitter Consumer Secret Key' => array(
        'default_value' => '',
        'description' => 'If you use twitter login you need this secret key from the dev api',
        'type' => 'text'
    ),
    'Google Client ID' => array(
        'default_value' => '',
        'description' => 'If you use google login you can put it here',
        'type' => 'text'
    ),
    'Google Client Secret ID' => array(
        'default_value' => '',
        'description' => 'If you use google login you can put it here',
        'type' => 'text'
    ),
    'Google Analytics API Key' => array(
        'default_value' => '',
        'description' => 'If you use google anayltics you can put it here',
        'type' => 'text'
    ),
    'Google Safe API Key' => array(
        'default_value' => '',
        'description' => 'Checks to make sure links are secure',
        'type' => 'text'
    ),
    'URL Preview' => array(
        'default_value' => true,
        'description' => 'Allows them to preview their created URL, note this will not work with AJAX creation, Notice you must have an Google PageSPeed Insights API Key!',
        'type' => 'boolean'
    ),
    'Google PageSpeed Insights API Key' => array(
        'default_value' => '',
        'description' => 'API Key for URL previews',
        'type' => 'text'
    ),
    'Social Media' => array(
        'default_value' => true,
        'description' => 'Social media sharing on the create url page',
        'type' => 'boolean'
    ),
    'QR Code' => array(
        'default_value' => true,
        'description' => 'Share a QR code on the create url page',
        'type' => 'boolean'
    ),
    'Bar' => array(
        'default_value' => true,
        'description' => 'Shows the bar at the top of the page when visitng a short link',
        'type' => 'boolean'
    ),
    'Flash Copy' => array(
        'default_value' => true,
        'description' => 'allows you to press copy when creating a short link',
        'type' => 'boolean'
    ),
    'Splash' => array(
        'default_value' => true,
        'description' => 'Shows the splash when visting short url',
        'type' => 'boolean'
    ),
    'Splash Url' => array(
        'default_value' => '',
        'description' => 'Splash URL : only enabled if splash is enabled , if empty loading splash will take its place, If used BAR WILL BE Automatically turned on',
        'type' => 'text'
    ),
    'Continue Button' => array(
        'default_value' => 'false',
        'description' => 'The continue button shows on the bar , after the count down, bar must be enabled',
        'type' => 'boolean'
    ),
    'Continue Seconds' => array(
        'default_value' => '5',
        'description' => 'The number of seconds before they are allowed to continue or be redirected',
        'type' => 'text'
    ),
    'Advertisments' => array(
        'default_value' => '',
        'description' => 'You can add adverstiments in the next version?',
        'type' => 'textarea'
    ),
    'Random URL Length' => array(
        'default_value' => 5,
        'description' => 'You can change the length of your short urls here',
        'type' => 'text'
    ),
    'Character Set' => array(
        'default_value' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'description' => 'You can change the length of your short urls here',
        'type' => 'select',
        'array' => array(
           'Numbers' => '0123456789', 
           'Lower Case Letters' =>'abcdefghijklmnopqrstuvwxyz', 
           'Upper Case Letters' =>'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 
           'Numbers & Lower Case Letters' =>'0123456789abcdefghijklmnopqrstuvwxyz', 
           'Numbers & Upper Case Letters' =>'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 
           'Numbers & Lower / Upper Case Letters' =>'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 
           'Lower / Upper Case Letters' =>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 
        ),
    ),
    'URL Limit Threshold' => array(
        'default_value' => 1000,
        'description' => 'The amount of URLs left in the unqiue sample space before it will show you on the admin panel',
        'type' => 'text'
    ),
    'AJAX Shorten' => array(
        'default_value' => false,
        'description' => 'You can allow an ajax result',
        'type' => 'boolean'
    ),
    'Signup and Approve' => array(
        'default_value' => false,
        'description' => 'If turned on , a user will have to sign up then approved to a member for access.',
        'type' => 'boolean'
    ),
    'Admin Only' => array(
        'default_value' => false,
        'description' => 'If turned on only admins can login.',
        'type' => 'boolean'
    ),
    'Bookmarklet' => array(
        'default_value' => true,
        'description' => 'Quick Bookmark',
        'type' => 'boolean'
    ),
    'Admin Email' => array(
        'default_value' => 'support@'.str_replace('http:','',str_replace('/','',Uri::Base(false))),
        'description' => 'Used for emails being sent out',
        'type' => 'text'
    ),
    'Different Short Url' => array(
        'default_value' => null,
        'description' => 'If you want to use a differn\'t URL for routing your urls',
        'type' => 'text'
    ),
    'Images Per Page' => array(
        'default_value' => 5,
        'description' => 'Number of results to show by default',
        'type' => 'text'
    ),
    'Data Per Page' => array(
        'default_value' => 25,
        'description' => 'Number of results to show by default',
        'type' => 'text'
    ),
);

?>
<?php echo View::Forge('core/menu');?>

<div class="margin-bottom-20">
    <div class="meela-bg">
        <h2>Site Settings</h2>
        <form method="post">
        <?php foreach($config_array as $config => $values) { ?>
            <div class="clearfix">
                <div class="col-lg-5 text-right">
                    <p class="admin-settings lead"><?php echo $config;?> <a href="#" data-toggle="tooltip" title="<?php echo $values['description'];?>"><i class="icon-question-sign"></i></a></p>
                </div>
                <div class="col-lg-7">
                    <?php
                    $setting_name = strtolower(str_replace(' ','_',$config));
                    if(isset($settings[$setting_name]) === true)
                    {
                        $setting_value = $settings[$setting_name];
                    }
                    else
                    {
                        $setting_value = $values['default_value'];
                    }
                    
                    if($values['type'] === 'boolean')
                    {
                        if($setting_value === true)
                        {
                            $checked = 'checked';
                        }
                        else
                        {
                            $checked = '';
                        }
                        ?>
                        <input name="<?php echo $setting_name;?>" type="hidden" value="false">
                        <div class="make-switch">
                            <input name="<?php echo $setting_name;?>" type="checkbox" <?php echo $checked;?>>
                        </div>
                    <?php } else if($values['type'] === 'textarea') { ?>
                        <textarea class="admin-textarea form-control" name="<?php echo $setting_name;?>"><?php echo $setting_value;?></textarea>
                    <?php } else if($values['type'] === 'select') { ?>
                            <select class="form-control" name="<?php echo $setting_name;?>">
                                <?php
                                    foreach($values['array'] as $option => $value)
                                    {
                                        if($setting_value == $value)
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        else
                                        {
                                            $selected = '';
                                        }
                                    ?>
                                        <option <?php echo $selected;?> value="<?php echo $value;?>">
                                            <?php echo $option;?>
                                        </option>
                                    <?php
                                    }
                                ?>
                            </select>
                    <?php
                    } else { ?>
                        <input class="form-control" name="<?php echo $setting_name;?>" type="text" value="<?php echo $setting_value;?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <div class="clearfix">
            <div class="col-lg-offset-5 col-lg-7">
                <button class="btn btn-primary btn-large btn-block">Update Settings</button>
            </div>
        </div>

        <input name="base_url" type="hidden" value="<?php echo Uri::Base();?>">
        </form>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        current = null;
        $('#add_block').keypress(function (e)
        {
            if (e.which == 13)
            {
                e.preventDefault();
                $('.autolist').click();
            }
        });
        
        $('.autolist').click(function()
        {
            $(this).parent().find('.error').remove();
            var new_value = $('.block-input').val();
            if (new_value == '')
            {
                return;
            }
            current = $(this);
            $.ajax({
                type : 'POST',
                url: '<?php echo Uri::Base();?>admin/add_blocked',
                data : { blocked : new_value },
            }).done(function(data)
            {
                data = jQuery.parseJSON(data);
                
                if (typeof data.error != 'string')
                {
                    $('#blocked_urls').append('<tr data-id="'+data+'"><td>' + new_value + '</td><td><i style="cursor:pointer" class="remove icon-remove"></i></td></tr>');
                   $('#add_block').val('');
                }
                else
                {
                    $('#blocked_error').html(data.error).toggleClass('hide');
                }
            });
        });
        
        $('#blocked .remove').live('click',function()
        {
            to_remove = $(this).parent().parent();
            $.ajax({
                type : 'POST',
                url: '<?php echo Uri::Base();?>admin/remove_blocked',
                data : { blocked_id : to_remove.attr('data-id') },
            });
            
            to_remove.remove();
            
        });
    });
    
</script>

<div class="meela-bg margin-bottom-20">
    <h2>Block IP / Domain List</h2>
    <div class="row">
        <form id="blocked">
            <div class="col-lg-6 col-lg-offset-3">
                <div id="blocked_error" class="error alert alert-danger hide"></div>
                <div class="input-group">
                    <span class="input-group-btn">
                        <div class="btn btn-primary btn-lg autolist">+</div>
                    </span>
                    <input id="add_block" type="text" class="block-input input-lg form-control">
                </div>
            </div>
            <div class="table-responsive col-lg-6 col-lg-offset-3 margin-top-20">
                
                <table id="blocked_urls" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>URL</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if(empty($blocked) === false)
                        {
                            foreach($blocked as $item)
                            {
                            ?>
                                <tr data-id="<?php echo $item->id;?>">
                                    <td><?php echo $item->expression;?></td>
                                    <td><i style="cursor:pointer" class="remove icon-remove"></i></td>
                                </tr>
                            <?php
                            }
                        }
                    ?>
                  </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
        if(Module::loaded('image') === true)
        {
        ?>
            <div class="meela-bg margin-bottom-20">
                <h2>Image Upload Limits</h2>
                <div class="row">
                    <?php
                        $max_upload = (int)(ini_get('upload_max_filesize'));
                        $max_post = (int)(ini_get('post_max_size'));
                    ?>
                    <p>Upload Max File Size : <?php echo $max_upload; ?> MB</p>
                    <p>Post Max File Size : <?php echo $max_post; ?> MB</p>
                    <p>Therefore -> Upload Max : <?php echo min($max_upload, $max_post); ?> MB</p>
                </div>
            </div>
        <?php
        }
        ?>
</div>

