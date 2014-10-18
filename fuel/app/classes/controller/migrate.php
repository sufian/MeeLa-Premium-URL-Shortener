<?php

class Controller_Migrate extends Controller
{
    public static function migrate()
    {
        // Provide Details for old DB
        // $database = Config::get('db.default.connection.database');

        //$database = $database['default']['connection']['database'];

        $query = DB::query('INSERT INTO urls (`id`,`short_url`,`url`,`hits`,`custom`,`created_at`,`updated_at`) SELECT `id`,`short_url`,`url`,`hits`,`custom`,'.DB::expr("FROM_UNIXTIME(created)").','.DB::expr("FROM_UNIXTIME(created)").' FROM `v2_urls`');
        
        // $query->execute(Database_Connection::instance('old_database'));
        $query->execute();
        
        $query = DB::query('INSERT INTO url_stats (`url_id`,`ip`,`country`,`referer`,`updated_at`,`created_at`) SELECT `urlid`,`ip`,`country`,`locfrom`,'.DB::expr("FROM_UNIXTIME(cdate)").','.DB::expr("FROM_UNIXTIME(cdate)").' FROM `v2_stats`');
        
        // $query->execute(Database_Connection::instance('old_database'));
        $query->execute();

        DB::update('urls')->value("custom", "0")->where('custom', '=', '2')->execute();
        
        return;
    }
    
}
