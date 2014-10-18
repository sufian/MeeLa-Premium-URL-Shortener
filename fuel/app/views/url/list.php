<script>
  $(document).ready(function()
  {
    var current_url_id = null;
    var current_data = null;
    
    $('.edit').click(function()
    {
      $('#full_url').val($(this).attr('data-url'));
      $('#short_url').val($(this).attr('data-short-url'));
      $('#edit_url').modal('show');
      
      current_url_id = $(this).attr('data-url-id');
      current_data = $(this);
      
      $('#form_error').addClass('hide');
      
    });
    
    
    $('#edit_url_form').click(function()
    {
      $('#form_error').addClass('hide');
      
      $.ajax({
	type: 'POST',
	url: "<?php echo Uri::Create('url/edit');?>",
	data: {
	  url_id : current_url_id,
	  url : $('#full_url').val(),
	  short_url : $('#short_url').val(),
	}
      }).done(function(results) {
	if (results != 1)
	{
	  $('#form_error').removeClass('hide').html(results);
	}
	else
	{
	  $('#url_'+current_url_id).html($('#full_url').val()).attr('href',$('#full_url').val())
	  $('#short_'+current_url_id).html($('#short_url').val()).attr('href',$('#short_url').val())
	  
	  $('#url_data_'+current_url_id).attr('data-short-url',$('#short_url').val());
	  $('#url_data_'+current_url_id).attr('data-url',$('#full_url').val());
	  
	  $('#edit_url').modal('hide');
	}
      });
    });
  });
</script>
<?php
echo View::Forge('core/menu');?>

<div class="col-lg-12 margin-bottom-10 meela-bg meela-blocks">
    <?php
    if(isset($images) === false && Uri::Current() == Uri::Create('admin/urls'))
    {
    ?>
    <div class="col-lg-4 pull-left">
        <a href="<?php echo Uri::Create('admin/urls/preview');?>" class="btn btn-primary">Site Preview Mode</a>
    </div>
    <?php
    }
    elseif(Uri::Current() == Uri::Create('admin/urls/preview'))
    {
    ?>
        <div class="col-lg-4 pull-left">
            <a href="<?php echo Uri::Create('admin/urls');?>" class="btn btn-primary">URL List</a>
        </div>
    <?php
    }
    ?>
    <div class="col-lg-4 pull-right">
        <form method="get">
            <div class="<?php echo isset($search) === true ? 'input-group' : '';?>">
                <input placeholder="Search for <?php echo isset($images) === false ? 'URLs' : 'Images' ;?>" class="form-control" name="search" type="text" value="<?php echo isset($search) === true ? $search : '' ;?>">
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
        if(empty($my_urls) === false)
	{
	echo htmlspecialchars_decode($pagination);
        ?>
        <div id="short_urls">
            <table class="sortable table table-striped" summary="Shows the most viewed urls">
                <thead>
                        <th>URL</th>
                        <th>Short URL</th>
                        <th>Hits</th>
                        <th>Viewed At</th>
                        <th></th>
                </thead>
                <tbody>
		  <?php
                        foreach($my_urls as $url)
                        {
                        ?>
                                <tr>
                                    <?php
                                        if(strpos($url->url, Uri::Create('assets/screenshots')) !== false || isset($images) === true || isset($screenshots) === true)
                                        {
                                            if(isset($screenshots) === true)
                                            {
                                            ?>
                                                <td>
                                                    <a class="confirm" href="<?php echo Uri::Create('url/delete/'.$url->id);?>">
                                                        <i class="icon-remove"></i>
                                                    </a>
                                                    <?php
                                                    
                                                        $url_parts = parse_url($url->url);
                                                        $host = $url_parts['host'];
                                                    ?>
                                                    
                                                    
                                                    <a class="confirm" data-text="All URLs LIKE <?php echo $host;?> will be blocked!" href="<?php echo Uri::Create('admin/add_blocked/'.str_replace('.','-',$host).'');?>">
                                                        <i class="icon-ban-circle"></i>
                                                    </a>
                                                    <img src="<?php echo Controller_Url::img_from_url($url); ?>">
                                                </td>
                                            <?php
                                            }
                                            else
                                            {
                                        ?>
                                            <td>
                                                <a data-text="This image will be removed!" class="confirm" href="<?php echo Uri::Create('url/delete/'.$url->id);?>">
                                                    <i class="icon-remove"></i>
                                                </a>
                                                <img href="<?php echo $url->custom;?>" class="colorbox" style="cursor: pointer;width:300px;" src="<?php echo $url->custom;?>">
                                            </td>
                                        <?php
                                            }
                                        }
                                        else
                                        {
                                        ?>
                                            <td>
                                                <a data-text="This URL will be removed!" class="confirm" href="<?php echo Uri::Create('url/delete/'.$url->id);?>">
                                                    <i class="icon-remove"></i></a> <a target="_blank" id="url_<?php echo $url->id;?>" href="<?php echo $url->url;?>">
                                                    <?php
                                                        if(Auth::member(5) && Uri::Create('admin/urls') == Uri::Current())
                                                        {
                                                            $url_parts = parse_url($url->url);
                                                            $host = $url_parts['host'];
                                                        ?>
                                                            <a class="confirm" data-text="All URLs LIKE <?php echo $host;?> will be blocked!" href="<?php echo Uri::Create('admin/add_blocked/'.str_replace('.','-',$host).'');?>">
                                                                <i class="icon-ban-circle"></i>
                                                            </a>
                                                        <?php
                                                        }
                                                    ?>
                                                    <?php echo $url->url;?>
                                                </a>
                                            </td>
                                        <?php
                                        }
                                    ?>
                                        <td>
                                          <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><img style="width: 20px;" src="<?php echo Uri::Create('assets/img/clean-icon-set/facebook.png');?>"></a>
                                          <a target="_blank" href="https://twitter.com/share?url=<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><img style="width: 20px;" src="<?php echo Uri::Create('assets/img/clean-icon-set/twitter.png');?>"></a>
                                          <a  id="short_<?php echo $url->id;?>" <a target="_blank" href="<?php echo isset($base_url) === true ?  $base_url.'/'.$url->short_url  : Uri::Create($url->short_url);?>"><?php echo $url->short_url;?></a>
                                        </td>
                                        <td><?php echo $url->hits;?></td>
                                        <td><?php echo empty($url->updated_at) === false ? Settings::time_ago($url->updated_at) : Settings::time_ago($url->created_at) ;?></td>
                                        <td><a href="<?php echo \Uri::Base().'stats/'.$url->short_url;?>">View Stats</a></td>
                                        <td><i id="url_data_<?php echo $url->id;?>" data-url="<?php echo $url->url;?>" data-short-url="<?php echo $url->short_url;?>" data-url-id="<?php echo $url->id;?>" style="cursor: pointer;" class="icon-edit edit"></td>
                                </tr>
                        <?php
                        }
		  ?>
                </tbody>
                
            </table>
        </div>
        <?php echo htmlspecialchars_decode($pagination);
        }
        else
        {
        ?>
        <div>
            <?php
                if(isset($search) === true)
                {
                ?>
                    No URLs Found!
                <?php
                }
                else
                {
                ?>
                    You dont have any URLs!
                <?php
                }
                ?>
        </div>
        
        <?php
        }
        ?>
</div>
<div id="edit_url" class="modal fade">
  <div class="modal-dialog">
    
    <form>
      <div class="modal-content">
	
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <h4 class="modal-title">Editing Url</span> </h4>
	</div>
	<div class="modal-body">
	    <div class="row">
	    	<div class="col-lg-8 col-lg-offset-2">
	    		<input class="form-control" id="full_url" type="text">
	    		<input class="form-control margin-top-20" id="short_url" type="text">
			</div>	    
		</div>
	    <div id="form_error" class="alert alert-danger hide"></div>
	</div>
	<div class="modal-footer">
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  <div id="edit_url_form" type="button" class="btn btn-primary">Save changes</div>
	</div>
      </div>
    </form>
  </div>
</div>