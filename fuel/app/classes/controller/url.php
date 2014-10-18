<?php

class Controller_Url extends Controller_Template
{
    public function before()
    {
        parent::before();
        
        if(\Settings::Get('signup_and_approve') === true && Auth::Check() === false)
        {
            Response::Redirect(Uri::Create('signup'));
        }
    }
    
    public static function check_loop($short_url_object)
    {
        $url = $short_url_object->url;
        $short_url = $short_url_object->short_url;
        $iframe = true;
        
        // Check to see if its an image
        if(strpos($url, Uri::Create('assets/screenshots')) === false)
        {
            if(isset($_SERVER['REMOTE_ADDR']) === true)
            {
             
                if($_SERVER['HTTP_USER_AGENT'] == 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)')
                {
                    header('HTTP/1.1 301 Moved Permanently');
                    header("Location: ".$url);
                }
            }
            
            $handle = curl_init($url);
            
            // Need to see the header!
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($handle, CURLOPT_HEADER, TRUE);
            curl_setopt($handle, CURLOPT_AUTOREFERER,TRUE);
            $response = curl_exec($handle);
            
            // Check for 404
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            
            curl_close($handle);
            
            // Make sure they have a valid response
            if(preg_match('/4[0-9][0-9]/',$httpCode))
            {
                Session::set('error','The link your trying togo was (ERROR 404) Not found!');
                Response::Redirect(Uri::Base());
            }
            // See if they have a redirection
            elseif(preg_match('/3[0-9][0-9]/',$httpCode) && $httpCode != 302)
            {
                $base_url = Settings::Get('different_short_url');
                if(empty($base_url) === true)
                {
                    $base_url = Uri::Base();
                }
                $new_response = preg_split("/\n/",substr(strip_tags($response),strpos(strip_tags($response),'Location:')));
                $new_url = str_replace('Location: ','',trim($new_response[0]));
                
                if($base_url.'/'.$short_url == $new_url)
                {
                    // LOOP DETECTED DELETE IT
                    if($short_url_object->delete())
                    {
                        Session::Set('error','Loop detected, url has been deleted!');
                    }
                    Response::Redirect(Uri::Base());
                }
                
                $short_url_object->url = $new_url;
                $short_url_object->save();
            }
            
            $headers = get_headers($url);
            // Can it handle an iframe?
            foreach($headers as $info)
            {
                
                if(preg_match('/X-Frame-Options:/',$info))
                {
                    if(preg_match('/sameorigin/',strtolower($info)) || preg_match('/deny/',strtolower($info)))
                    {
                        // Site does not support iframes
                        $iframe = false;
                    }
                }
            }
        }
        else
        {
            $data['image'] = true;
            $iframe = false;
        }
        $data['url'] =  $url;
        $data['iframe'] =  $iframe;
        
        return $data;
    }
    
    public static function action_view($short_url)
    {
        if(is_object($short_url) === false)
        {
            $short_url = Model_Url::query()
                ->where('short_url',$short_url)
                ->get_one();
        }
        
        $short_url->hits = $short_url->hits + 1;
        $short_url->save();
        
        if(empty($short_url) === false)
        {
            $results = Controller_Url::check_loop($short_url);
           
            $data['url'] = $results['url'];
            $data['iframe'] = $results['iframe'];
            
            if(isset($results['image']) === true)
            {
                $data['image'] = $results['image'];
            }
            
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            {
                $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            else
            {
                $ip = Input::real_ip();
            }
            
            $country = Model_Url_Stats_Country::query()
                ->where('start_ip','<=',ip2long($ip))
                ->where('end_ip','>=',ip2long($ip))
                ->get_one();
                
            if(empty($country) === false)
            {
                $country = $country->country;
            }
            else
            {
                $country = 'N/A';
            }
            
            $lang = Agent::languages();
            
            // Insert Some Stats
            $stats = Model_Url_Stat::forge(array(
                'url_id' => $short_url->id,
                'ip' => $ip,
                'country' => $country,
                'referer' => Input::referrer(),
                'language' => serialize($lang),
            ));
            
            $stats->save();
            return new Response(View::Forge('url/redirect',$data));
        }
        else
        {
            Session::set('error','We couldn\'t');
            
            Response::Redirect(Uri::Base());
        }
    }
    
    public function action_create()
    {
        if(\Settings::Get('signup_and_approve') === true)
        {
            if(!Auth::member(4))
            {
                if(Input::is_ajax() === true)
                {
                    echo $data['error'];
                    die;
                }
                Session::Set('error','You are not authroized to use this application, please contact the admin');
                Response::Redirect(Uri::Base());
            }
        }
        
        if(Input::Method() == 'POST')
        {
            $url = Input::POST('url');
            $custom = Input::POST('custom-url');
            
            if(empty($url) === false)
            {
                // Check to see if its a valid url
                if(filter_var($url, FILTER_VALIDATE_URL) === false)
                {
                    if(Input::is_ajax() === true)
                    {
                        echo 'This is not a valid URL please edit your URL';
                        die;
                    }
                    Session::Set('error','This is not a valid URL please edit your URL');
                    Response::Redirect(Uri::Base());
                }
                
                // Check black list!
                $blocked = Model_Blacklist::query()->get();
                
                if(empty($blocked) === false)
                {
                    foreach($blocked as $block)
                    {
                        // Check aginst the blocked
                        if(preg_match('/'.strtolower($block->expression).'/',strtolower($url)))
                        {
                            if(Input::is_ajax() === true)
                            {
                                echo 'URL is blacklisted!';
                                die;
                            }
                            Session::Set('error','URL is blacklisted!');
                            Response::Redirect(Uri::Base());
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
                        if(Input::is_ajax() === true)
                        {
                            echo 'This website has been blocked because of '.$buffer;
                            die;
                        }
                        Session::Set('error','This website has been blocked because of '.$buffer);
                        Response::Redirect(Uri::Base());
                    }
                }
                $length = strlen($url);
                $new_url_object = Controller_Url::shortenit($url,$custom);
                $data['short_url'] = $new_url_object->short_url;
                
                $data['url_object'] = $new_url_object;
                
                if(Input::is_ajax() === true)
                {
                    $base_url = Settings::get('different_short_url');
                    
                    if(empty($base_url) === true)
                    {
                        echo Uri::Create($data['short_url']);
                       
                    }
                    else
                    {
                        echo $base_url.'/'.$data['short_url'];
                    }
                    
                    die;
                }
            }
            else
            {
                if(Input::is_ajax() === true)
                {
                    echo 'Error';
                    die;
                }
                Response::Redirect(Uri::Base());
            }
        }
        else
        {
            if(Input::is_ajax() === true)
            {
                echo 'Error';
                die;
            }
            Response::Redirect(Uri::Base());
        }
        
        $this->template->content = View::Forge('url/created',$data);
    }
	
    public static function shortenit($url,$custom = null,$api = null)
    {
        if(empty($api) === false || Input::is_ajax() === true)
        {
            $ajax = true;
        }
        else
        {
            $ajax = false;
        }

        if ($url{strlen($url) - 1} == "/")
        {
            $url = substr($url, 0, -1);
        }
        if (!preg_match("/^(ht|f)t(p|ps)\:\/\//si", $url))
        {
            $url = "http://".$url;
        }
        
        $length = strlen($url);
        $count  = 0;
        
        $user_id = 0;
        if(empty($api) === false && $api !== true)
        {
            $user = \Model\Auth_Metadata::query()
                ->where('key','api_key')
                ->where('value',$api)
                ->get_one();
            
            if(empty($user) === false)
            {
                $user_id = $user->id;
            }
            else
            {
                echo 'Not a valid API Key!';
            }
        }
        else
        {
            $user_id = static::$user_id;
        }
        
        if(empty($user_id) === true)
        {
            $user_id = 0;
        }
        
        if (empty($custom) === false)
        {
            
            $short_url = str_replace(" ","",$custom);
            
            $found_url = Model_Url::query()
                ->where('short_url',$short_url)
                ->get_one();
                
            if(empty($found_url) === false)
            {
                if($ajax === true)
                {
                    echo 'Custom Short URL already taken , choose a differnt name';
                    die;
                }
                else
                {
                    Session::set('error','Custom Short URL already taken , choose a differnt name');
                    Response::Redirect(Uri::Base());
                }
            }
            $custom = true;
        }
        else
        {
            $custom = false;
            $success = false;
            
            while ($success === false)
            {
                $short_url = Controller_Url::generate_url();
                
                $found_url = Model_Url::query()
                    ->where('short_url',$short_url)
                    ->get_one();
                
                if(empty($found_url) === true)
                {
                    $success = true;
                }
            }
            
        }
        
        $new_url = Model_Url::Forge(array(
            'short_url' => $short_url,
            'url' => $url,
            'hits' => 0,
            'custom' => $custom,
            'user_id' => $user_id,
         ));
        
        if($new_url->save())
        {
            if($ajax === false)
            {
                Session::set('success','Url has been shortened!');
            }
        }
        else
        {
            if($ajax === true)
            {
                echo 'Unknown Error';
                die;
            }
            else
            {
                Session::set('error','Unknown Error');
                Response::Redirect(Uri::Base());
            }
        }
        return $new_url;
    }
    
    public static function generate_url()
    {
        $keys = \Settings::Get('character_set');
        
        if(empty($keys) === true)
        {
            $keys = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $i    = 0;
        $url  = "";
        
        $random_length = \Settings::Get('random_url_length');
        
        if(empty($random_length) === true)
        {
            $random_length = 5;
        }
        while ($i < $random_length)
        {
                $random = mt_rand(0, strlen($keys) - 1);
                $url   .= $keys{$random};
                $i++;
        }
        return $url;
    }

    public function action_list($all = false, $images = false , $screenshots = false)
    {
        if(Auth::check() === false)
        {
            Response::Redirect(Uri::Create('login'));
        }
        if($all == 'false' || $all === false)
        {
            $all = false;
        }
        else
        {
            $all = true;
        }
        
        if($screenshots == "true")
        {
            $image = false;
            $data['screenshots'] = true;
        }
        
        
        if($images == "true")
        {
           
            $data['images'] = true;
            $per_page = Settings::get('images_per_page');
            if(empty($per_page) === true)
            {
                $per_page = 5;
            }
        }
        else
        {
            $per_page = Settings::get('data_per_page');
            if(empty($per_page) === true)
            {
                $per_page = 25;
            }
        }
        if($all === false)
        {
            // check for admin
            if(!Auth::member(5))
            {
                Response::Redirect(Uri::Create('user/urls'));
            }
        }
        
        if(Input::Method() === 'GET' && Input::Get('search'))
        {
            $data['total_count'] = Controller_Search::get_urls($all,null,$images);
            $pagination = Settings::pagination($data['total_count'],$per_page);
            
            
            $data['search'] = Input::GET('search');
            
            $data['my_urls'] = Controller_Search::get_urls($all,$pagination,$images);
        }
        else
        {
            if($all === true)
            {
                $data['total_count'] = Model_Url::query();
                if($images == "true")
                {
                    $data['total_count']->where('url','LIKE',Uri::Create('assets/screenshots').'%');
                }
                else
                {
                    $data['total_count']->where('url','NOT LIKE',Uri::Create('assets/screenshots').'%');
                }
                $data['total_count'] = $data['total_count'] ->count();
            }
            else
            {
                $data['total_count'] = Model_Url::query()->where('user_id',static::$user_id);
                
                
                if($images == "true")
                {
                    $data['total_count']->where('url','LIKE',Uri::Create('assets/screenshots').'%');
                }
                else
                {
                    $data['total_count']->where('url','NOT LIKE',Uri::Create('assets/screenshots').'%');
                }
                $data['total_count'] = $data['total_count']->count();
            }
            
            $pagination = Settings::pagination($data['total_count'],$per_page);
            
            $data['my_urls'] = Model_Url::query();
            
            if($all === false)
            {
                $data['my_urls']->where('user_id',static::$user_id);
            }
            
            if($images == "true") 
            {
                $data['my_urls']->where('url','LIKE',Uri::Create('assets/screenshots').'%');
            }
            else
            {
                $data['my_urls']->where('url','NOT LIKE',Uri::Create('assets/screenshots').'%');
            }
           
            $data['my_urls'] = $data['my_urls']
                ->order_by('created_at','DESC')
                ->rows_offset($pagination->offset)
                ->rows_limit($per_page)
                ->get();
        }
        
        $data['pagination'] = $pagination->render();
        
        $this->template->content = View::Forge('url/list',$data);
    }

    public function action_edit()
    {
        $url_id = Input::POST('url_id');
        
        $new_url = Input::POST('url');
        $new_short_url = Input::POST('short_url');
        
        $url = Model_Url::find($url_id);
        
        if($url->user_id != static::$user_id && !Auth::member(5))
        {
            echo 'This isn\'t your URL!';
            die;
        }
        else
        {
            if(filter_var($new_url, FILTER_VALIDATE_URL))
            {
                $url->url = $new_url;
            }
            else
            {
                echo 'This is not a valid URL please edit your URL';
                die;
            }
            
            if(empty($new_short_url) === false)
            {
                $found_url = Model_Url::query()
                    ->where('short_url', $new_short_url)
                    ->get_one();
                    
                if(empty($found_url) === true || $found_url->id == $url_id)
                {
                    $url->short_url = $new_short_url;
                }
                else
                {
                    echo 'Short URL already taken, try a differnt one!';
                    die;
                }
            }
            
            if($url->save())
            {
                echo true;
                die;
            }
            else
            {
                'error';
                die;
            }
        }
        
    }
    
    public function action_delete($url_id)
    {
        $url = Model_Url::find($url_id);
        
        if($url->user_id != static::$user_id && !Auth::member(5))
        {
            Session::set('error','This isn\'t your URL!');
            Response::Redirect_Back('user/urls');
        }
        else
        {
           
            if($url->delete())
            {
                $url_stats = Model_Url_Stat::query()
                    ->where('url_id',$url_id)
                    ->rows_limit(1)
                    ->limit(1)
                    ->count();
                if(empty($url_stats) === false)
                {
                    $url_stats = DB::delete('url_stats')
                        ->where('url_id',$url_id)
                        ->execute();
                }
            
                Session::set('success','Url has been deleted!');
                Response::Redirect_Back('user/urls');
            }
            else
            {
                Session::set('error','Unknown Error!');
                Response::Redirect_Back('user/urls');
            }
        }
        
    }
    public function action_stats($short_url)
    {
        $data['url'] = Model_Url::query()
            ->where('short_url',$short_url)
            ->get_one();
            
        if(empty($data['url']) === false)
        {
            $data['unqiue_hits'] = DB::select('ip')->distinct()->from('url_stats')
                ->where('url_id', $data['url']->id)
                ->execute();
                
            $data['unqiue_hits'] = count($data['unqiue_hits']);
                
            $data['unqiue_hits_today'] = DB::select('ip')->distinct()->from('url_stats')
                ->where('url_id', $data['url']->id)
                ->where('created_at','>=',strtotime('today 12:01'))
                ->where('created_at','<=',strtotime('today 12:01 + 1 day'))
                ->execute();
                
            $data['unqiue_hits_today'] = count($data['unqiue_hits_today']);
            
            $data['hits_today'] = DB::select('id')->from('url_stats')
                ->where('url_id', $data['url']->id)
                ->where('created_at','>=',strtotime('today 12:01'))
                ->where('created_at','<=',strtotime('today 12:01 + 1 day'))
                ->execute();
            
            $data['hits_today'] = count($data['hits_today']);
            
            $new_results = '';
            // Get countries Stats
            $countries = DB::select('country')
                ->from('url_stats')
                ->distinct(true)
                ->where('url_id',$data['url']->id)
                ->execute()
                ->as_array();
                
            if(empty($countries) === false)
            {
                foreach($countries as $country)
                {
                    $hit_count = Model_Url_Stat::query()
                        ->related('url')
                        ->where('country',$country)
                        ->where('url_id',$data['url']->id)
                        ->count();
                        
                    $new_results .= "['".$country['country']."', ".$hit_count."], ";
                }
                
                $data['stats'] =  $new_results;
            }
            else
            {
                $data['stats'] = null;
            }
            
            $this->template->content = View::forge('url/stats',$data);
        }
        else
        {
            Session::Set('error','No URL was found');
            Response::Redirect(Uri::Base());
        }
    }
    
    public function action_make_image()
    {
        $url_id = \Input::GET('url_id');
        
        $url_object = \Model_Url::query()
            ->where('id',$url_id)
            ->get_one();
        
        Controller_Url::img_from_url($url_object);
    }
    
    public static function img_from_url($url_object, $device = "desktop")
    {
        $api_key = \Settings::get('google_pagespeed_insights_api_key');
        if(empty($api_key) === false)
        {
            if(empty($url_object) === false && is_object($url_object))
            {
                if(empty($url_object->cached_preview) === true)
                {
                    $image = false;
                    if ($url_object->url != null && $api_key != null) {
                        if ($device != "desktop")
                        {
                            $device = "mobile";
                        }
                        
                        $data = @json_decode(file_get_contents("https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=" .urlencode($url_object->url) . "&strategy=" . $device ."&screenshot=true&fields=responseCode%2Cscreenshot&key=" . $api_key));
                        if (@$data->responseCode == 200) {
                           
                            $image = 'data:image/png;base64,'.str_replace(array("-", "_"), array("+", "/"), $data->screenshot->data);
                            
                            $url_object->cached_preview = $image;
                            $url_object->save();
                            if(Input::is_ajax())
                            {
                                echo $image;
                                die;
                            }
                            else
                            {
                                return $image;
                            }
                        }
                    }
                }
                else
                {
                    if(\Input::is_ajax())
                    {
                        echo $url_object->cached_preview;
                        die;
                    }
                    else
                    {
                        return $url_object->cached_preview;
                    }
                }
            }
            else
            {
                if(\Input::is_ajax())
                {
                    echo 'Invalid input, this is not a url object!';
                    die;
                }
                else
                {
                    \Session::set('error','Invalid input, this is not a url object!');
                }
            }
        }
        else
        {
            if(\Input::is_ajax())
            {
                echo "error : No Google PageSpeed Insights API Key has been set please vist : https://code.google.com/apis/console to get a key!";
                die;
            }
            else
            {
               \Session::set('error','No Google PageSpeed Insights API Key has been set please vist : https://code.google.com/apis/console to get a key!');
                \Response::Redirect_Back();
            }
        }
    }
}