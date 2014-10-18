<?php

namespace Fuel\Migrations;

class Create_urls
{
	public function up()
	{
		\DBUtil::create_table('urls', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'short_url' => array('constraint' => 255, 'type' => 'varchar'),
			'url' => array('type' => 'longblob'),
			'hits' => array('constraint' => 11, 'type' => 'int'),
			'custom' => array('type' => 'boolean'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'cached_preview' => array('type' => 'longblob', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('urls');
	}
}