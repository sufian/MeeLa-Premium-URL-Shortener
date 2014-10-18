<?php echo View::Forge('core/menu');?>
<style>
	.meelab{
		margin-bottom: 20px;
	}
</style>
<div class="container">
    <?php
        if(Uri::Current() === Uri::Create('admin'))
        {
        ?>
      <div class="row meela-pods">
            <div class="col-lg-12" style="font-size: 40px;width: 100%;display: block;text-align: center;padding-left: 0px ;padding-right: 0px;">
                <?php
                    if(empty($style) === false)
                    {
                    ?>
                        <div style="font-size:15px;" class="alert alert-danger">Please change the random length for your URLs!</div>
                    <?php
                    }
                ?>
                <div class="pod-3 meela-bg">
                    
                    <span class="title">Unique URLs Left</span>
                    <?php
                        $threshold = Settings::get('url_limit_threshold');
                        if(empty($threshold) === true)
                        {
                            $threshold = 1000;
                        }
                        if($urls_left < $threshold)
                        {
                            $style = 'color:red;';
                        }
                        else
                        {
                            $style = null;
                        }
                    ?>
                    <p style="<?php echo $style;?>"><?php echo $urls_left;?></p>
                </div>
	    </div>
	</div>
        <?php
        }
        ?>
	<div class="row meela-pods">
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Total URLs:</span>
				<p><?php echo $total_urls; ?></p>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Total Url Visits:</span>
				<p><?php echo empty($total_hits) === false ? $total_hits : '0' ; ?></p>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Created Today:</span>
				<p><?php echo $created_today; ?></p>
			</div>
		</div>
	</div>

	<div class="row meela-pods">
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Random URL's:</span>
				<p><?php echo $total_urls - $total_custom_urls; ?></p>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Custom URL's:</span>
				<p><?php echo $total_custom_urls; ?></p>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="pod-3 meela-bg">
				<span class="title">Unvisited URL's:</span>
				<p><?php echo $no_clicks; ?></p>
			</div>
		</div>
	</div>
        
        <div class="col-lg-12 margin-bottom-10 margin-top-10 meela-bg meela-blocks">
                <span class="title text-center">Shortened URL's</span>
                <div id="short_urls">
                </div>
        </div>
        
	<div class="meela-blocks">
		<div class="col-lg-7">
			<div class="pod-2 meela-bg margin-bottom-10">
				<span class="title">URL Visitor Map:</span>
				<div id="chart_div_map" style="width: 420px; height: 300px;"></div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="pod meela-bg margin-bottom-10">
				<span class="title">Custom Vs Random:</span>
				<div id="custom_vs_random" style="width: 320px; height: 300px;"></div>
			</div>
		</div>
	</div>
        <div class="col-lg-12 margin-bottom-10 meela-bg meela-blocks">
                        <table class="table table-striped" summary="Shows the recently visited urls">
                                <span class="title text-center padding-bottom-10 padding-top-10">Recently Visited URL's</span>
                                <thead>
                                        <th>URL</th>
                                        <th>Short URL</th>
                                        <th>Hits</th>
                                        <th>Visted</th>
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
                                                                    <td><a href="<?php echo \Uri::Base().'stats/'.$url->short_url;?>">View Stats</a></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                }
                                        ?>
                                </tbody>
                                
                        </table>
        </div>


		<div class="col-lg-12 margin-bottom-10 meela-bg meela-blocks">
			<table class="table table-striped" summary="Shows the recently visited urls">
				<span class="title text-center padding-bottom-10 padding-top-10">Recently Created URL's</span>
				<thead>
					<th>URL</th>
					<th>Short URL</th>
					<th>Hits</th>
					<th>Created</th>
					<th></th>
				</thead>
				<tbody>
					<?php
						if(empty($recently_created) === false)
						{
							foreach($recently_created as $url)
							{
							?>
								<tr>
                                                                    <tr>
                                                                            <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $cut_url;?></a></td>
                                                                            <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a></td>
                                                                            <td><?php echo number_format($url->hits);?></td>
                                                                            <td><?php echo Settings::time_ago($url->updated_at); ?></td>
                                                                            <td><a href="<?php echo \Uri::Base().'stats/'.$url->short_url;?>">View Stats</a></td>
                                                                    </tr>
							<?php
							}
						}
					?>
				</tbody>
				
			</table>
		</div>

		<div class="col-lg-12 margin-bottom-10 meela-bg meela-blocks">
				<table class="table table-striped" summary="Shows the recently visited urls">
					<span class="title text-center padding-bottom-10 padding-top-10">Most Visited URL's</span>
					<thead>
						<th>URL</th>
						<th>Short URL</th>
						<th>Hits</th>
						<th>Created</th>
						<th></th>
					</thead>
					<tbody>
						<?php
							if(empty($most_visited) === false)
							{
								foreach($most_visited as $url)
								{
								?>
                                                                    <tr>
                                                                            <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $cut_url;?></a></td>
                                                                            <td><a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a></td>
                                                                            <td><?php echo number_format($url->hits);?></td>
                                                                            <td><?php echo Settings::time_ago($url->updated_at); ?></td>
                                                                            <td><a href="<?php echo \Uri::Base().'stats/'.$url->short_url;?>">View Stats</a></td>
                                                                    </tr>
								<?php
								}
							}
						?>
					</tbody>
					
				</table>
		</div>


</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="application/x-javascript">
    google.load("visualization", "1.0", {packages:["corechart"]});
    google.load("visualization", "1.0", {packages:["geochart"]});
    
    google.setOnLoadCallback(shortStats);
    function shortStats() {
        var data = google.visualization.arrayToDataTable([
			['Day',  'Visits', 'Created'],
			<?php 
				echo html_entity_decode($short_url_stats);
			?>
        ]);

        var options = {
          hAxis: {title: 'Days'}
        };

        <?php
            if(empty($short_url_stats) === false)
            {
        ?>
            var chart = new google.visualization.AreaChart(document.getElementById('short_urls'));
            chart.draw(data, options);
        <?php
            }
        ?>
      }

	google.setOnLoadCallback(CustomVsRandom);
	function CustomVsRandom()
	{
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
		['Random',<?php echo $total_urls - $total_custom_urls; ?>],
		['Custom',<?php echo $total_custom_urls; ?>]
        ]);

        // Set chart options
        var options = {'width':320, 'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('custom_vs_random'));
        chart.draw(data, options);
    }
	
     google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap()
      {
	
        var data = google.visualization.arrayToDataTable([
		['Country', 'Visits Past 15 Days'],
		<?php 
			echo $country_stats;
		?>
        ]);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('chart_div_map'));
        chart.draw(data, options);
    };
</script>