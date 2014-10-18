<?php

class Model_Url_Stat extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'url_id',
		'ip',
		'country',
		'referer',
		'language',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'url'	
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
	);
	protected static $_table_name = 'url_stats';

}
