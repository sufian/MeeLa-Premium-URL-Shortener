<?php

class Model_Url extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'short_url',
		'url',
		'hits',
		'custom',
		'user_id',
                'cached_preview',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'user'	
	);
	
	protected static $_has_many = array(
		'url_stats'	
	);
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
                'Observer_Url' => array(
			'events' => array('after_load'),
		),
	);
	protected static $_table_name = 'urls';

}
