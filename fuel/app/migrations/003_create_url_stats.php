<?php

namespace Fuel\Migrations;

class Create_url_stats
{
	public function up()
	{
		\DBUtil::create_table('url_stats', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'url_id' => array('constraint' => 11, 'type' => 'int'),
			'ip' => array('constraint' => 255, 'type' => 'varchar'),
			'country' => array('constraint' => 255, 'type' => 'varchar'),
			'referer' => array('constraint' => 255, 'type' => 'varchar'),
			'language' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('url_stats');
	}
}