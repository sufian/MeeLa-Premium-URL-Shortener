<?php

abstract class Controller_Template extends \Fuel\Core\Controller_Template
{
    public static $user_id = null;
    public function before()
    {
        // Lets render the template
        parent::before();
        
        // Check to see if the config exsists
        if (file_exists(APPPATH . 'config/production/db.php') === false)
        {
            Response::Redirect('install');
        }
        
        if(DBUtil::field_exists('urls', array('cached_preview')) === false && file_exists(APPPATH . 'classes/controller/upgrade.php'))
        {
            Response::Redirect(Uri::Create('upgrade'));
        }
        
        $real_base_url = Config::get('base_url');
        Config::set('base_url', str_replace('public/','',$real_base_url));
        
        $base_url = Settings::get('different_short_url');
        if(empty($base_url) === false)
        {
            View::set_global(array(
                'base_url' => $base_url,
            ), false, false);
        }
        
        if(trim(Uri::Base(),'/') == Settings::get('different_short_url'))
        {
            
            if(count(Uri::Segments()) == 2)
            {
                $route = Uri::to_assoc();
                if(isset($route) === true && $route['core'] == '404')
                {
                    // We are good!
                }
                else
                {
                    Response::Redirect(Settings::Get('base_url'));
                }
            }
            else
            {
                Response::Redirect(Settings::Get('base_url'));
            }
        }
        
        $data = null;
       
        if(Auth::Check())
        {
            $user_id =  Auth::get_user_id();
            static::$user_id = $user_id[1];
            
            $data['api_key'] = Auth::get('api_key');
            
            if(empty($data['api_key']) === true)
            {
                if(empty($data['api_key']) === true)
                {
                    $data['api_key']  = preg_replace('/\+|\/|\=|\?/','',\Auth::instance()->hash_password(\Str::random()).static::$user_id);
                    // invalidate the hash
                    \Auth::update_user(
                        array(
                            'api_key' => $data['api_key'],
                        ),
                        Auth::get('username')
                    );
                    
                }
            }
        }
        
        
        // Lets set the default title , you can change it when calling the view
        $this->template->title = ucwords(str_replace('controller_', '',strtolower($this->request->route->controller)));
        
        try{
            Module::load('image');
            $this->template->image_js = true;
        }
        catch(Exception $e){
        }
        
        // Lets get the header and footer and set a variable to use within the template
        $this->template->footer = View::forge('core/footer',$data);
        $this->template->header = View::forge('core/header');
    }
    
    public function after($response)
    {
        $response = parent::after($response);
        
        if(Uri::Current() != Uri::Create('login'))
        {
           
            if(Settings::get('maintenance_mode') === true)
            {
                if(!Auth::member(5))
                {
                    $this->template->content = View::Forge('core/maintenance');
                }
                elseif(Uri::Current() != Uri::Create('admin/settings'))
                {
                    // YOUR GOOD
                    Response::Redirect(Uri::Create('admin/settings'));
                }
            }
        }
        
        return $response;
    }
}