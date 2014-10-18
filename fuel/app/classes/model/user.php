<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'group_id',
		'email',
		'last_login',
		'previous_login',
                'created_at',
                'updated_at'
	);
	
	protected static $_has_many = array(
		'urls',
		'user_providers' => array(
			'key_from' => 'id',
			'model_to' => 'Model_User_Provider',
			'key_to' => 'parent_id',
			'cascade_save' => true,
			'cascade_delete' => false,
		)
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
	protected static $_table_name = 'users';

}
