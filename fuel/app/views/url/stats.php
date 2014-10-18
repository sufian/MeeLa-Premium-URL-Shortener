<div class="meela-bg margin-bottom-20 clearfix">
    
    <h3 class="stats-long-url"><img src="<?php echo Controller_Url::img_from_url($url); ?>"> <span>Long URL: </span><?php echo $url->url;?></h3>
    <div class="col-lg-5">
        <div class="row stats-pod">
            <div class="col-lg-10 col-lg-offset-1"><span>Total Hits:</span><?php echo $url->hits;?></div>
            <div class="col-lg-10 col-lg-offset-1"><span>Unique Hits:</span><?php echo $unqiue_hits;?></div>
            <div class="col-lg-10 col-lg-offset-1"><span>Total Hits Today:</span><?php echo $hits_today;?></div>
            <div class="col-lg-10 col-lg-offset-1"><span>Unique Hits Today:</span><?php echo $unqiue_hits_today;?></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div id="chart_div_map"></div>
    </div>
    
    <table class="margin-top-20 col-lg-12 table table-striped">
        <caption>Referall Locations</caption>
        <thead>
            <tr>
                <th>Location</th>
                <th>Visits</th>
            </tr>
        </thead>
        <tbody>
            <?php
	    $referral_locations = array();
	    
            if(empty($url->url_stats) === false)
	    {
                foreach($url->url_stats as $url)
		{
		    if(isset($referral_locations[$url->referer]) === true)
		    {
			$referral_locations[$url->referer] = $referral_locations[$url->referer] + 1;
		    }
		    else
		    {
			$referral_locations[$url->referer] = 1;
		    }
                }
            }
	    
	    if(empty($referral_locations) === false)
	    {
		foreach($referral_locations as $referer => $hits)
		{
		?>
		    <tr>
			<td><?php echo empty($referer) === false ? $referer : 'Direct';?></td>
			<td><?php echo $hits;?></td>
		   </tr>
		<?php
		}
	    }
            ?>
	    
	    
	    
        </tbody>
    </table>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="application/x-javascript">
    google.load("visualization", "1.0", {packages:["geochart"]});
    google.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap()
    {
	
        var data = google.visualization.arrayToDataTable([
		['Country', 'Vists By Location'],
		<?php 
			echo $stats;
		?>
        ]);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('chart_div_map'));
        chart.draw(data, options);
    };
</script>