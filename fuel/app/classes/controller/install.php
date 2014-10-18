<?php

class Controller_Install extends Controller
{
    public function action_index()
    {        
        // Check to see if the config exsists
        if (file_exists(APPPATH . 'config/production/db.php') === true)
        {
            // Check to make sure DB tables exists
            if(DBUtil::table_exists('urls') && DBUtil::table_exists('users_groups') && DBUtil::table_exists('url_stats') && DBUtil::table_exists('settings'))
            {
                // return Controller_Install::force_login();
                Response::Redirect('/login');
            }
        }
        else
        {
            if(Input::Method() === 'POST')
            {
                // $database = Input::Post('database');
                // $old_database = Input::Post('old_database');
                // MAKE CONFIG
                
                if (Controller_Install::create_db_config()) {
                        
                    if(Controller_Install::create_tables()) {
                        
                        Controller_Install::force_login();
                    
                    } else {

                        Session::Set('error','You cannot have the same database names for upgrade, please change database names!');
                        return Response::forge(View::forge('install/index'));

                    }
                }
            }
            else
            {
                $data = null;
                if (file_exists(DOCROOT.'assets/cache') === true)
                {
                    $data['public_cache'] = File::get_permissions(DOCROOT.'assets/cache');
                }
                if (file_exists(APPPATH.'config/production') === true)
                {
                    $data['config'] = File::get_permissions(APPPATH.'config/production');
                }
                if (file_exists(APPPATH.'cache') === true)
                {
                    $data['cache'] = File::get_permissions(APPPATH.'cache');
                }
            }
            return Response::forge(View::forge('install/index',$data));
        }

    }
    
    public function create_db_config()
    {
        $file = APPPATH . 'config/production/db.php';
        
        $handle = @fopen($file, 'w');
        
        if (file_exists(APPPATH . 'config/production/db.php') !== true)
        {
            return Controller_Install::display_db();
        }
        else
        {

            $config = "<?php
/**
 * The production database settings.
 */
return array(
    'active' => 'default',
    'default' => array(
        'type' => 'mysqli',
        'connection'  => array(
            'hostname'   => '".Input::POST('hostname')."',
            'database'   => '".Input::POST('database')."',
            'username'   => '".Input::POST('db_username')."',
            'password'   => '".Input::POST('db_password')."',
            'persistent' => false,
        ),
        'identifier'   => '`',
        'table_prefix' => '',
        'charset'      => 'utf8',
        'enable_cache' => true,
        'profiling'    => true,
    ),

);
";
            fwrite($handle, $config);
            
            fclose($handle);
        }

    return true;
    }
    
    public function display_db()
    {
        
        $data['config'] = "
/**
 * The database settings.
 */
return array(
    'active' => 'default',
    'default' => array(
        'type' => 'mysqli',
        'connection'  => array(
            'hostname'   => '".Input::POST('hostname')."',
            'database'   => '".Input::POST('database')."',
            'username'   => '".Input::POST('db_username')."',
            'password'   => '".Input::POST('db_password')."',
            'persistent' => false,
        ),
        'identifier'   => '`',
        'table_prefix' => '',
        'charset'      => 'utf8',
        'enable_cache' => true,
        'profiling'    => true,
    ),

);
";

        return Response::forge(View::forge('install/db_config',$data));
        
    }
    
    public function create_tables()
    {

        if (file_exists(APPPATH . 'config/production/db.php') == true) {
            Log::error('shit worked!');
        }

        // DBUtil::set_connection(null);
        if(DBUtil::table_exists('urls'))
        {
        
            if(DBUtil::field_exists('urls', array('time')))
            {
                // Upgrade Me
                try
                {
                    DBUtil::rename_table('urls', 'v2_urls');
                    DBUtil::rename_table('stats', 'v2_stats');
                    DBUtil::rename_table('settings', 'v2_settings');

                } catch(\Database_Exception $e) {
                    Log::error($e);
                }

                Controller_Install::create_tables();


            } else {
                // Already Installed
            }


        } else {



            $oil_path = str_replace('public/','',DOCROOT);

            try
            {
                @Migrate::current('default', 'app');
                @Migrate::current('auth', 'package');
            }
            catch(\Database_exception $e)
            {
                Debug::Dump('PLEASE REVISIT THIS /install (DONT RELOAD) THAT SHOULD INSTALL THE SCRIPT FOR YOU IF NOT THEN: Access has been denied for the database user , go to fuel/app/config/production/db.php , and edit your username and password!');
                die;
            }

            try
            {
                \DBUtil::create_index('urls','short_url');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('urls','user_id');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {    
                \DBUtil::create_index('urls', array('id', 'short_url'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('urls', array('id', 'user_id'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('urls', array('id', 'short_url','user_id'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {    
                \DBUtil::create_index('url_stats', 'url_id');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }

            try
            {
                \DBUtil::create_index('url_stats', 'country');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }

            try
            {
                \DBUtil::create_index('url_stats', array('id','url_id'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {   
                \DBUtil::create_index('url_stats_countries', 'start_ip');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('url_stats_countries', 'end_ip');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('url_stats_countries', 'country');
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('url_stats_countries', array('start_ip','end_ip'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
            
            try
            {
                \DBUtil::create_index('url_stats_countries', array('start_ip','end_ip','country'));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
           
            try
            {
                \DBUtil::modify_fields('urls', array(
                    'url' => array('constraint' => 255, 'type' => 'varchar'),
                ));
            }
            catch(\Database_exception $e){
                Log::error($e);
            }
           

        }

        return true;
    }
    
    public function force_login()
    {
        if(DBUtil::table_exists('v2_urls'))
        {
            if (DB::count_records('urls') < DB::count_records('v2_urls')) {
                \Controller_Migrate::migrate();
            }
            
        }
        
        if(Input::Method() === 'POST')
        {
            // call Auth to create this user
            $new_user = \Auth::create_user(
                Input::POST('username'), // USER LOGIN
                Input::POST('password'), // PASSWORD
                Input::POST('email'), // EMAIL
                5, // ADMIN GROUP
                array(
                    'fullname' =>   Input::POST('name'),
                )
            );
        }
        else
        {

            // call Auth to create this user
            $new_user = \Auth::create_user(
                'meela', // USER LOGIN
                'password', // PASSWORD
                'meela@mee.la', // EMAIL
                5, // ADMIN GROUP
                array(
                    'fullname' =>   'Meela Admin',
                )
            );
        }
        
        
        $delete_users = Model_User::query()
            ->where('username','admin')
            ->or_where('username','guest')
            ->get();
            
        foreach($delete_users as $user)
        {
            $user->delete();
        }
        
        // if a user was created succesfully
        if ($new_user)
        {
            \Auth::force_login($new_user);
        }
        
        $file = DOCROOT.'assets/url_stats_countries.csv';
        // Insert data into temporary table from file 
        $query = 'LOAD DATA LOCAL INFILE "'.$file.'" INTO TABLE url_stats_countries fields terminated by "," enclosed by \'"\' lines terminated by "\n" (id,start_ip,end_ip,country,created_at,updated_at)';
        \DB::query($query)->execute();
        
        Response::Redirect(Uri::Create('admin/settings'));
    }
}
