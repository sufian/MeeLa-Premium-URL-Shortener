<a href="<?php echo Uri::Base();?>" title="Home">Home</a> |
<?php
    if(\Settings::Get('api') === true)
    {
        if(empty($api_key) === true && \Settings::Get('bookmarklet') === true)
        {
        ?>
            <a href="javascript:void(location.href='<?php echo Uri::Base();?>api/create?url='+encodeURIComponent(location.href))" title="<?php echo \Settings::Get('site_name'); ?> Bookmarklet">Bookmarklet</a> |
    
        <?php
        }
        elseif(\Settings::Get('bookmarklet') === true)
        {
        ?>
            <a href="javascript:void(location.href='<?php echo Uri::Base();?>api/create?url='+encodeURIComponent(location.href))+'&api_key=<?php echo $api_key;?>" title="<?php echo \Settings::Get('site_name'); ?> Bookmarklet">Bookmarklet</a> |
        <?php
        }
    }
?>

 <?php if(\Settings::Get('api') === true): ?><a href="<?php echo Uri::Create('api');?>" title="Developer API">Developer</a> | <?php endif; ?> Copyright &copy; <?php echo date('Y'); ?> - <?php echo \Settings::Get('site_name'); ?>