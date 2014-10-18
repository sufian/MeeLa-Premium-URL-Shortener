<?php

class Controller_Api extends Controller_Template
{
    public function before()
    {
        parent::before();
        if(\Settings::Get('api') !== true)
        {
            echo 'API DISABLED';
            Session::Set('error','API Disabled');
            Response::redirect(Uri::Base());
            die;
        }
    }
    
    
    public function action_index()
    {
        $data['api_key'] = Auth::get('api_key');
        
        $this->template->content = View::forge('api/index',$data);
    }
    
    public function action_create()
    {
        
        $url = Input::Get('url');
        $custom = Input::Get('custom');
        
        $api = Input::Get('api_key');
        
        if(empty($api) === true)
        {
            $api = true;
        }
        
        if(empty($url) === false)
        {
            // Check to see if its a valid url
            if(filter_var($url, FILTER_VALIDATE_URL) === false)
            {
                echo 'You did not enter a valid url in, please try again';
                die;
            }
               
             
            // Check black list!
            $blocked = Model_Blacklist::query()->get();
            
            if(empty($blocked) === false)
            {
                foreach($blocked as $block)
                {
                    // Check aginst the blocked
                    if(preg_match('/'.strtolower($block['blocked']).'/',strtolower($url)))
                    {
                        echo 'URL Blacklisted';
                        die;
                    }
                }
            }
                
            // Lets generate them a url
            $safe = \Settings::Get('google_safe_api_key');
            
            // Is it safe?
            if (empty($safe) === false)
            {
                $m_url = 'https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey='.$safe.'&appver=1.0&pver=3.0&url='.$url;
                $curl_handle = curl_init();
                curl_setopt($curl_handle,CURLOPT_URL,$m_url);
                curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
                $buffer = curl_exec($curl_handle);
                curl_close($curl_handle);
                
                if(empty($buffer) === false)
                {
                    echo 'This website has been blocked because of '.$buffer;
                    die;
                }
            }
                
            $length = strlen($url);
            $data['short_url_raw'] = Controller_Url::shortenit($url,$custom,$api);
            $data['url'] = $url;
            $data['short_url'] = $data['short_url_raw']['short_url'];
            
            echo \Uri::Create($data['short_url']);
            die;
        }
        else
        {
            echo 'Error';
            die;
        }
    }
}