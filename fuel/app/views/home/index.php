<?php
$title = \Settings::Get('site_name');
if(empty($title) === true)
{
    $title = "Meela";
}
?>
<a href="<?php echo Uri::Base();?>" title="Meela - Url Shortner"><div id="logo"><?php echo $title;?> </div></a>
<?php


$title_descrption = Settings::get('title_descrption');
if(empty($title_descrption) === false)
{
?>
<div class="home-h1">
    <?php echo $title_descrption;?>
</div>
<?php
}
?>

<form id="url_creator" method="post" action="<?php echo Uri::Create('url/create');?>">
    <div class="row">
      <div class="col-lg-12">
        <div class="input-group">
          <input id="urlbox" class="form-control meela-shorten-input" name="url" pladeholder="http://" type="text">
          <span class="input-group-btn">
            <button class="btn btn-large btn-primary meela-shorten-btn" type="submit" id="submit">Shorten!</button>
          </span>
        </div>
      </div>
    </div>
    <div class="meela-custom row clearfix margin-top-10 margin-bottom-20">
        <div class="col-lg-3 col-lg-offset-2">	
            <label class="meela-custom-label" for="custom-url"><span style="font-size:9px;"></span>Custom URL <em>(Optional)</em></label>
        </div>
        <div class="col-lg-5">
            <div class="input-group">
            <span class="input-group-addon"><?php echo Uri::Base();?></span>
            <input id="custombox" class="meela-shorten-custom-input form-control" type="text" name="custom-url">
            </div>
        </div>
    </div>
</form>
<form id="img_creator" method="post" action="<?php echo Uri::Create('image/create');?>">
    <div class="row">
        <div class="col-lg-12">
            <div class="input-group">
                <button style="width:75%;" class="btn btn-large btn-primary meela-shorten-btn" type="submit" id="img_submit">Shorten!</button>
                <div id="img_reset" style="float: right;width:20%;" class="btn btn-large btn-danger meela-shorten-btn">Reset!</div>
                <img id="insert_data_img">
                <input id="data_img_url" type="hidden" name="data_img_url" value="">
            </div>
        </div>
    </div>
    <div class="meela-custom row clearfix margin-top-10 margin-bottom-20">
        <div class="col-lg-4 col-lg-offset-1">	
                <label class="meela-custom-label" for="custom-url"><span style="font-size:9px;"></span>Custom URL<em>(Recommended)</em></label>
        </div>
        <div class="col-lg-6">
            <div class="input-group">
            <span class="input-group-addon"><?php echo Uri::Base();?></span>
            <input id="custombox" class="meela-shorten-custom-input form-control" type="text" name="custom-url">
            </div>
        </div>
    </div>
</form>
<?php
if(\Settings::Get('ajax_shorten') === true)
{
?>


<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="display:none" id="content-short-url">
	<hr>
	<div style="display: none;" id="show_copy" class="alert alert-info">Copied</div>
	<div class="input-group margin-bottom-10">
		<input id="appendedInputButton" class="form-control input-lg" type="text">
		<span class="input-group-btn">
			<button style="border-radius: 0 3px 3px 0;" type="button" class="btn-flashfix btn btn-primary btn-lg"><span id="flashbtn">Copy Me!</span></button>
		</span>
	</div>
	<hr>
</div>
</div>

	<script>
		$(document).ready(function()
		{
			$('#url_creator').submit(function(e)
			{
                            e.preventDefault();
                            if ($('#urlbox').val() != '')
                            {
                                $.ajax({
                                        type : 'POST',
                                        url: "<?php echo Uri::Create('url/create');?>",
                                        data: {
                                                ajax : true,
                                                custom: $('#custombox').val(),
                                                url: $('#urlbox').val(),
                                        }
                                }).done(function(e){
                                        $('#appendedInputButton').val(e);
                                        $('#urlbox').val('');
                                        $('#content-short-url').slideDown();
                                        $("#content-short-url .btn").zclip({
                                            path:'<?php echo Uri::Base();?>assets/swf/ZeroClipboard.swf',
                                            copy:function(){return $('#appendedInputButton').val();},
                                            afterCopy:function(){
                                                $('#show_copy').slideDown();
                                            }
                                        });
                                });
                            }
			});
		});
	</script>
<?php
}
?>
<div class="container">
<?php
	$ad = \Settings::get('advertisments');
	if(empty($ad) === false)
	{
	?>
		<div class="meela ad margin-bottom-10">
			<?php echo $ad;?>
		</div>
	<?php
	}
?>
<div class="clear"></div>
<?php

if(\Settings::Get('top_3_pods') === true)
{
?>
<div class="row meela-pods">
	<div class="col-lg-4">
		<div class="pod-3 meela-bg">
			<span class="title">Total Urls:</span>
			<p><?php echo number_format($url_count);?></p>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="pod-3 meela-bg">
			<span class="title">Total Url Visits:</span>
			<p><?php echo number_format($click_count);?></p>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="pod-3 meela-bg">
			<span class="title">Random Link:</span>
			<!-- <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$random  : Uri::Create($random);?>">Click Me</a></td> -->
			<td><a target="_blank" href="http://flol.com">Click Me</a></td>
		</div>	
	</div>
</div>

<?php
}
$description = \Settings::Get('site_description');
if(empty($description) === false)
{
?>
	<div class="col-lg-12 meela-bg margin-bottom-10" style="text-align: center;">
		<?php echo $description;?>
    </div>
<?php
}


if(\Settings::Get('recently_viewed_table') == 'true')
{
    
?>
	<div class="col-lg-12 meela-bg margin-bottom-10 bs-table-scrollable">
		<h2>Recently Visited URL's</h2>
		<table class="sortable table table-striped" summary="Shows the most viewed urls">
		<thead>
			<th>URL</th>
			<th>Short URL</th>
			<th>Hits</th>
			<th>Viewed At</th>
		</thead>
		<tbody>
			<?php
				if(empty($recently_viewed) === false)
				{
                                    foreach($recently_viewed as $url)
                                    {
                                            $cut_url = (strlen($url->url) >= 50) ? substr($url->url,0,50).'...' : $url->url;
                                    ?>
                                            <tr>
                                                    <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $cut_url;?></a></td>
                                                    <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a></td>
                                                    <td><?php echo number_format($url->hits);?></td>
                                                    <td><?php echo Settings::time_ago($url->updated_at); ?></td>
                                            </tr>
                                    <?php
                                    }
				}
			?>
		</tbody>
		
	    </table>
        <div id="urlrecentview"></div>
    </div>
<?php
}


if(\Settings::Get('recently_created_table') == 'true')
{
?>
	<div class="col-lg-12 meela-bg margin-bottom-10 bs-table-scrollable">
	    <h2>Recently Created URL's</h2>
		<table class="sortable table table-striped" summary="Shows the most viewed urls">
		<thead>
			<th>URL</th>
			<th>Short URL</th>
			<th>Hits</th>
			<th>Created</th>
		</thead>
		<tbody>
			<?php
				if(empty($recently_created) === false)
				{
					foreach($recently_created as $url) {
						$cut_url = (strlen($url->url) >= 50) ? substr($url->url,0,50).'...' : $url->url;
					?>
						<tr>
							<td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $cut_url;?></a></td>
							<td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a></td>
							<td><?php echo number_format($url->hits);?></td>
							<td><?php echo Settings::time_ago($url->created_at); ?></td>
						</tr>
					<?php
					}
				}
			?>
		</tbody>
		
	    </table>
	</div>
<?php 
}
if(\Settings::Get('most_popular_table') == 'true') {
?>
	<div class="col-lg-12 meela-bg margin-bottom-10 bs-table-scrollable">
	    <h2>Most Viewed URL's</h2>
	    <table class="sortable table table-striped" summary="Shows the most viewed urls">
		<thead>
			<th>URL</th>
			<th>Short URL</th>
			<th>Hits</th>
			<th>Last Visisted</th>
		</thead>
		<tbody>
			<?php
				if(empty($most_popular) === false)
				{
					foreach($most_popular as $url) {
						$cut_url = (strlen($url->url) >= 50) ? substr($url->url,0,50).'...' : $url->url;
					?>
						<tr>
							<td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $cut_url;?></a></td>
							<td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a></td>
							<td><?php echo number_format($url->hits);?></td>
							<td><?php echo empty($url->updated_at) === false ?  Settings::time_ago($url->updated_at) : Settings::time_ago($url->created_at); ?></td>
						</tr>
					<?php
					}
				}
			?>
		</tbody>
		
	    </table>
	</div>
<?php } ?>




</div> <!-- /container -->



<script type="text/javascript">
$(window).scroll(function () {
  if (window.pageYOffset >= 50) {
    $(".navbar-meela").addClass("navbar-fixed-top").removeClass("nav-remove top-minus-80");
  } else {
    $(".navbar-meela").addClass("nav-remove top-minus-80").removeClass("navbar-fixed-top");
  }
});
</script>