<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php
    $splash_url = \Settings::Get('splash_url');
    $bar = \Settings::Get('bar');
    $splash = \Settings::Get('splash');
    
if(empty($splash_url) === false && $splash === true && isset($image) === false)
{
    $bar = true;
}
elseif(isset($image) === true)
{
    $bar = false;
}

if($bar === true)
{
    
    /*  Meta  */
    $tags = get_meta_tags($url);
    
    /*  Title  */
    function getTitle($url)
    {
            $str = file_get_contents($url);
            if (strlen($str)>0)
            {
                    if(preg_match("/\<title\>(.*)\<\/title\>/", $str, $title))
                    {
                            return $title[1];
                    }
                    else
                    {
                            return '';
                    }
            }
    }
    if (getTitle($url) == "")
    {
            $titletag = "<title>URL Made on ".\Settings::Get('site_name')."</title>";
    }
    else
    {
            $titletag = "<title>". getTitle($url) ."| ".\Settings::Get('site_name')."</title>";
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns:fb="http://ogp.me/ns/fb#">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="description" content="<?php echo isset($tags['description']) === true ? $tags['description'] : '' ; ?>">
		<?php
			if(isset($titletag) === true)
			{
				echo $titletag;
			}
                        
		?>
		
		<link rel="stylesheet" href="<?php echo Uri::Base(); ?>assets/css/bar.css" /> 
		
		<!--[if gte IE 9]>
		<style type="text/css">
			.gradient {
				filter: none;
			}
		</style>
		<![endif]-->
	</head>
	<body>
		<?php if($bar === true)
		{
		?>
			<div id="fb-root"></div>
			<script>
				(function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					
					if (d.getElementById(id))
					{
						return;
					}
					
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=292875815726";
					fjs.parentNode.insertBefore(js, fjs);
					
				}(document, 'script', 'facebook-jssdk'));
			</script>
			
			<div id="bar">
				<div id="meela_shareimg">
					<img src="<?php echo Uri::Base(); ?>/assets/img/like-us.png" name="Like us!" />
				</div>
				<div id="meela_share">
					<fb:like href="<?php echo Uri::Base(); ?>" send="true" layout="button_count" width="450" show_faces="false" font="arial"></fb:like>
				</div>
				<a id="close" href="<?php echo $url; ?>">
					Not working?
				</a>
				<a id="textadf" href="http://mee.la/buynow">
					Buy Meela Script
				</a>
				<div id="count_down"></div>
				<style>
					#count_down{
						text-align: center;
						color: white;
						margin-top:5px;
					}
					#continue_to_site{
						cursor: pointer;
					}
				</style>
				<script type="text/javascript">
					function start_count()
					{
						var counter = document.getElementById('count_down');
						var count = "<?php echo \Settings::Get('continue_seconds');?>";
						var continue_button = '';
						
						var count = <?php echo \Settings::Get('continue_seconds');?>;
						var count_down = setInterval(function()
						{
							count--;
							counter.innerHTML = 'Redirecting in ' + count + ' seconds';
							if (count == 0)
							{
								<?php
									if(\Settings::Get('continue_button') === true)
									{
									?>
										counter.innerHTML = '<div id="continue_to_site">Continue</div>';
									<?php
									}
									else
									{
									?>
										counter.innerHTML = '';
										$(".meela_frame").toggle();
									<?php
									}
								?>
								clearInterval(count_down);
							   
							}
						}, 1000);
					
						$('#continue_to_site').live('click',function()
						{
							$(".meela_frame").toggle();
							counter.innerHTML = '';
						});
					}
				</script>
			</div>
			
		<?php
		}
		
		if($splash === true && empty($splash_url) === false)
		{
		?>
			<iframe class='meela_frame'
				<?php
					if(empty($splash_url) === true)
					{
						echo 'style="display:none;" src=""';
					}
					else
					{
						echo 'src=" '.stripslashes(str_replace(",", "%2C", $splash_url)).'"';
					}
				?>
				width="100%" height="100%" frameborder="0">
			</iframe>
			<?php
			
			if($iframe === true)
			{
			?>
				<iframe class='meela_frame' style="display: none;" src="<?php echo $url; ?>" width="100%" height="100%" frameborder="0"></iframe>
			<?php
			}
			else
			{
			?>
				<script>
					$(document).ready(function(){
						$(document).delay(<?php echo \Settings::Get('continue_seconds');?> * 1000).queue(function()
						{
						    window.location.replace("<?php echo $url?>");
						});
					});	
				</script>
			<?php
			}
			?>
				<script>
					start_count();
				</script>
			<?php
		}
		elseif ($splash === true)
		{
		?>
			<style>
				body
				{
					background-color: white;
				}
				
				#PreLoad
				{
					width: 960px;
					text-align: center;
					overflow: auto;
					margin: 50px auto;
					color: #3D9AC3;
					font-family: HelveticaNeue-Light, "Helvetica Neue Light", "Helvetica Neue", sans-serif;
					font-size: 25px;
				}
				
				.clear
				{
					clear: both;
					height: 80px;
					width: 100%;
				}
				
				#loadgif
				{
					width: 160px;
					height: 160px;
					padding: 10px;
					margin: 0 auto;
				}
			</style>
			<?php
				if($iframe === true)
				{
				?>
					<iframe id="meeela_frame" class='<?php echo $bar === true ? 'meela_frame' : '';?>' style="display: none;" src="<?php echo $url; ?>" width="100%" height="100%" frameborder="0"></iframe>
					<script>
						$(document).ready(function(){
							$('#meeela_frame').delay(<?php echo \Settings::Get('continue_seconds');?> * 1000).queue(function()
							{
								$("#meeela_frame").show();
								$("#PreLoad").fadeOut();
								$("#loadgif").fadeOut();
							});
						});	
					</script>
				<?php
				}
				else
				{
				?>
					<script>
						$(document).ready(function(){
							$(document).delay(<?php echo \Settings::Get('continue_seconds');?> * 1000).queue(function()
							{
								window.location.replace("<?php echo $url; ?>");
							});
						});	
					</script>
				<?php
				}
			?>
			<div class="clear"></div>
			<div id="loadgif">
				<img width="160" height="160" src="/assets/img/loading.gif">
			</div>
			<div style="font-size: 25px;" id="PreLoad">
				You are about to visit: <br/><br/>
				<?php echo $url; ?>
			</div>
		<?php
		}
		elseif($bar === true && $iframe === true)
		{
                    ?>
                        <iframe id="meeela_frame" class='meela_frame' src="<?php echo $url; ?>" width="100%" height="100%" frameborder="0"></iframe>
                    <?php
		}
		else
		{
                    if(isset($image) === false)
                    {
			// just redirect
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: ".$url);
                    }
                    else
                    {
                    ?>
                        <img style="max-width:100%;max-height: 100%;" src="<?php echo $url;?>">
                    <?php
                    }
		}
		
		if (!\Settings::Get('analytics') == "")
		{
		?>
			<script type="text/javascript">
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '<?php echo \Settings::Get('analytics'); ?>']);
				_gaq.push(['_trackPageview']);
			
				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
			<script>_gaq.push(['_setCustomVar', 1, 'URL Visitors', '<?php echo $i; ?>', 1 ]); </script>	
			
		<?php
		}
		?>
	</body>
</html>