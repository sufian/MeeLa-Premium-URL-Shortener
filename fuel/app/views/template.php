<?php

    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo \Settings::Get('site_name'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A simple and easy to use URL shortener script.">
        <meta name="author" content="CodeCanopy">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            
        <!-- styles -->
        <?php
            Casset::css('bootstrap.css');
            Casset::css('font-awesome.min.css');
            Casset::css('jquery-ui-1.10.3.custom.min.css');
            Casset::css('bootstrap-switch.css');
            Casset::css('base.css');
            Casset::css('colorbox.css');
            
            echo Casset::render_css();
        
        ?>
        <!--[if IE 7]>
            <link href="<?php echo Uri::Create('assets/css/font-awesome-ie7.min.css');?>" rel="stylesheet">
        <![endif]-->
        
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <!-- fav icon -->
        <link rel="shortcut icon" href="<?php echo Uri::Create('assets/img/favicon.ico');?>">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    </head>
    <body>
        <div id="wrap">
              <div class="external-drop top"></div>
        <div class="external-drop right"></div>
        <div class="external-drop bottom"></div>
        <div class="external-drop left"></div>
        
            <?php echo $header; ?>
            <div class="container meela-container">
                <div id="image_to_big" class="hide alert alert-danger">Image file to big Maximum Filesize is : <?php echo min($max_upload, $max_post); ?> MB</div>
                <?php
                    if (file_exists("install.php"))
                    {
                        header('Location: install.php'); exit();
                    }
                ?>
                
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
                
                echo $content;
                
            ?>
                
            </div>
            <div id="push"></div>
        </div>
        <footer id="footer">
            <?php echo $footer; ?>
        </footer>
               

        <div class="hide" id="dialog" title="Confirmation Required" >
            Are you sure you want to continue?
        </div>

    <!-- Placed at the end of the document so the pages load faster -->
    <?php
	Casset::js('jquery.zclip.min.js');
	Casset::js('bootstrap.min.js');
	Casset::js('sortable.js');
	Casset::js('jquery-ui-1.10.3.custom.min.js');
        Casset::js('bootstrap-switch.min.js');
        Casset::js('base.js');
        if(Uri::Current() === Uri::Base() && isset($image_js) === true)
        {
            Casset::add_path('image', APPPATH.'modules/image/');
            Casset::js('image::image.js');
        }
        Casset::js('jquery.colorbox-min.js');
	
	echo Casset::render_js();
    ?>
    <script>
        var text_input = document.getElementById ('urlbox');
 
        if (text_input != null)
        {
           text_input.focus ();
           text_input.select ();
        }
        
        var max_file_size = <?php echo min($max_upload, $max_post); ?>;
        
        $(document).ready(function()
        {
            $('.colorbox').colorbox({photo:true, maxWidth:'95%', maxHeight:'95%'}).resize();
            
            $("#content-short-url .btn").zclip({
                path:'<?php echo Uri::Base();?>assets/swf/ZeroClipboard.swf',
                copy:function(){return $('#appendedInputButton').val();},
                afterCopy:function(){
                    $('#show_copy').slideDown();
                }
            });
            
            $(".confirm").on("click", function(e) {
                    e.preventDefault();
                    var targetUrl = $(this).attr("href");
                    
                    $("#dialog").html($(this).attr('data-text'));
                    $("#dialog").dialog({
                            buttons : {
                                    "Confirm" : function() {
                                            window.location.href = targetUrl;
                                    },
                                    "Cancel" : function() {
                                            $(this).dialog("close");
                                    }
                            }
                    });
                    
                    $("#dialog").dialog("open");
            });
            
            setTimeout(function(){ 
                $(".check").slideUp("slow"); 
            }, 8000 ); 
        });
    </script>
    <?php
        $analytics = \Settings::Get('google_analytics_api_key');
        if(empty($analytics) === false)
        {
        ?>
            <script type="text/javascript">
            
              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', '<?php echo $analytics;?>']);
              _gaq.push(['_trackPageview']);
            
              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();
            
            </script>
        <?php
        }
    ?>



  </body>
</html>