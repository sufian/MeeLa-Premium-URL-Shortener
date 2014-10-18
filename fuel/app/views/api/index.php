<div class="container">
	<div class="row meelab">
                    <h1>Developer API</h1>
                    <h3>Creating A Short Link with a Random URL</h3>
                    
                    
                    
                    <?php
                        if(isset($api_key) === true && empty($api_key) === false)
                        {
                        ?>
                            <strong>Send to API</strong><p><?php echo Uri::Base(); ?>api/create?url={url}&api_key=<?php echo $api_key;?></p>
                        <?php  
                        }
                        else
                        {
                        ?>
                            <strong>Send to API</strong><p><?php echo Uri::Base(); ?>api/create?url={url}&api_key={key_optional}</p>
                        <?php
                        }
                    ?>
                    <h3>Creating A Short Link wit a Custom URL</h3>
                    <?php
                        if(isset($api_key) === true && empty($api_key) === false)
                        {
                        ?>    
                            <strong>Send to API</strong><p><?php echo Uri::Base(); ?>api/create?url={url}&custom={custom_url}&api_key=<?php echo $api_key;?></p>
                        <?php  
                        }
                        else
                        {
                        ?>
                            <strong>Send to API</strong><p><?php echo Uri::Base(); ?>api/create?url={url}&custom={custom_url}&api_key={key_optional}</p>
                        <?php
                        }
                    ?>
                    <br>
                    <strong>Your Response</strong>
                    <p><?php echo Uri::Base(); ?>{url}</p>
        <div>
</div>
<div class="clear"></div>