<?php

namespace Fuel\Migrations;

class Create_url_stats_countries
{
	public function up()
	{
		\DBUtil::create_table('url_stats_countries', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'start_ip' => array('constraint' => 20, 'type' => 'bigint'),
			'end_ip' => array('constraint' => 20, 'type' => 'bigint'),
			'country' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('url_stats_countries');
	}
}