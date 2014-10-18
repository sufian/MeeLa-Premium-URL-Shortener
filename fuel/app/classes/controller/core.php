<?php

class Controller_Core extends Controller_Template
{
    public static $url_found = null;
    public function action_404()
    {
        // Lets see if theres a URL to redirect
    // $url = trim($_SERVER['REQUEST_URI'],'/');
    $url = str_replace(Uri::Base(),'',Uri::Create($_SERVER['REQUEST_URI']));

        $parts = preg_split('/\//',$url);
        // var_dump($parts);
        if(count($parts) == 1)
        {
            $url = str_replace('%20',' ',$url);
            static::$url_found = Model_Url::query()
                ->where('short_url',$url)
                ->get_one();
                
            if(empty(static::$url_found) === false)
            {
               return Controller_Url::action_view(static::$url_found);
            }
        } else if(count($parts) == 2)
        {
            // $url = str_replace('%20',' ',$url[1]);
            $url = explode('/', $url);
            $url = $url[1];
            static::$url_found = Model_Url::query()
                ->where('short_url',$url)
                ->get_one();
            if(empty(static::$url_found) === false)
            {
               return Controller_Url::action_view(static::$url_found);
            }
        }

        
        $data = new stdClass;
        
        $this->template->content = View::forge('core/404');
    }
}