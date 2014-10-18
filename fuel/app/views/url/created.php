<?php
$url_preview = Settings::get('url_preview');
$api_key = \Settings::get('google_pagespeed_insights_api_key');

if(empty($url_preview) === true || empty($api_key) ===true)
{
    $url_preview = false;
}
if($url_preview === true)
{
?>
    <script>
        $(document).ready(function()
        {
            $.get('<?php echo Uri::Create('url/make_image?url_id='.$url_object->id.'');?>', function(data_url)
            {
                $('#preview_text').hide();
                $('#preview').attr('src',data_url);
            });
        });
        
        
    </script>
<?php
}
    if(isset($base_url) === false)
    {
        $base_url = Uri::Base();
    }
    else
    {
        $base_url = $base_url.'/';
    }
?>

<div class="row">
<div class="col-lg-8 col-lg-offset-2">
	<h2>Your new URL has been created!</h2> 

<div class="row">
    <div class="col-lg-8 col-lg-offset-2" id="content-short-url">
            <hr>
            <div style="display: none;" id="show_copy" class="alert alert-info">Copied</div>
            <div class="input-group margin-bottom-10">
                    <input id="appendedInputButton" class="form-control input-lg" type="text" value="<?php echo $base_url.$short_url;?>">
                    <span class="input-group-btn">
                            <button style="border-radius: 0 3px 3px 0;" type="button" class="btn-flashfix btn btn-primary btn-lg"><span id="flashbtn">Copy Me!</span></button>
                    </span>
            </div>
            <hr>
    </div>
</div>
<?php
if($url_preview === true)
{
    
?>
    <div class="meela-bg">
        <div class="row" style="text-align: center;">
            <?php
                if(isset($image) === false)
                {
                ?>
                    <h2 id="preview_text">Image Preivew Loading</h2>
                <?php
                }
            ?>
           
            <img style="<?php echo  isset($image) === true ? 'width: 100%;' : '';?>" id="preview" src="<?php echo  isset($image) === true? $url->url : Uri::Create('assets/img/loading.gif');?>">
        </div>
    </div>
<?php
}
?>
<br><br>
<?php if (\Settings::Get('qr_code') == 1 && \Settings::Get('social_media') == 1): ?>
	<div class="meela-bg">
		<div class="row">
			<div class="col-lg-6 text-center">
				<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo $base_url.$short_url; ?>" />
			</div>
			<div class="col-lg-6">
                            
				<a href="<?php echo Uri::Create('stats/'.$short_url); ?>" target="_blank" class="margin-top-27 btn btn-large btn-block btn-primary">URL Stats</a>
				<a href="http://www.facebook.com/sharer.php?u=<?php echo $base_url.$short_url; ?>" target="_blank" class="margin-top-10 btn btn-large btn-block btn-facebook"><i class="icon-facebook-sign"></i> Facebook</a>
				<a href="https://twitter.com/share?url=<?php echo $base_url.$short_url; ?>" target="_blank" class="margin-top-10 btn btn-large btn-block btn-twitter"><i class="icon-twitter"></i> Twitter</a>
			</div>
		</div>
	</div>
<?php elseif (\Settings::Get('qr_code') == 0 && \Settings::Get('social_media') == 1): ?>
	<div class="row margin-top-10">
		<div class="col-lg-6 col-lg-offset-3 meela-bg">
			<a href="<?php echo $base_url."stats/".$short_url; ?>" target="_blank" class="btn btn-large btn-block btn-primary">URL Stats</a>
			<a href="http://www.facebook.com/sharer.php?u=<?php echo $base_url.$short_url; ?>" target="_blank" class="margin-top-10 btn btn-large btn-block btn-facebook"><i class="icon-facebook-sign"></i> Facebook</a>
			<a href="https://twitter.com/share?url=<?php echo $base_url.$short_url; ?>" target="_blank" class="margin-top-10 btn btn-large btn-block btn-twitter"><i class="icon-twitter"></i> Twitter</a>
		</div>
	</div>
<?php elseif (\Settings::Get('qr_code') == 1 && \Settings::Get('social_media') == 0): ?>
	<div class="row margin-top-10">
		<div class="col-lg-6 col-lg-offset-3 meela-bg text-center">
			<img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?php echo $base_url.$short_url; ?>" />
		</div>
	</div>
<?php endif; ?>


</div>
</div>