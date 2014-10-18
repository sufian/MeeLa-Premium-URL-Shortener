<?php

class Model_User_Provider extends \Orm\Model
{
	protected static $_properties = array(
            'id',
            'parent_id',
            'provider',
            'uid',
            'secret',
            'access_token',
            'expires',
            'refresh_token',
            'user_id',
            'created_at',
            'updated_at',
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
	protected static $_table_name = 'users_providers';

}
