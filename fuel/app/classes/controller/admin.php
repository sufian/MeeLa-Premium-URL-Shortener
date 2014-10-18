<?php

class Controller_Admin extends Controller_Template
{
    public function before()
    {
        parent::before();
        
        // check for admin
        if(!Auth::member(5))
        {
            \Response::redirect_back('home');
        }
    }
    
    static function mathFact( $s ) 
    { 
      $r = (int) $s; 

      if ( $r < 2 ) 
        $r = 1; 
      else { 
        for ( $i = $r-1; $i > 1; $i-- ) 
          $r = $r * $i; 
      } 

      return( $r ); 
    }

    public function action_index()
    {
        $data['settings'] = Settings::get_all();
        
        if (\Input::method() == 'POST')
        {
            Controller_Settings::update(Input::Post());
            $data['settings'] = Settings::get_all(true);
        }
        
        $data['blocked'] = $blacklist_item = Model_Blacklist::query()->get();
        
        
         $keys = \Settings::Get('character_set');
        
        if(empty($keys) === true)
        {
            $keys = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $random_length = \Settings::Get('random_url_length');
        
        if(empty($random_length) === true)
        {
            $random_length = 5;
        }
        
        $url_sample_space = DB::select(DB::expr('count(id) as count'))->from('urls')->where(DB::expr('char_length(short_url)'), $random_length)->limit(1)->execute()->as_array();
        
        $data['urls_left'] = (Controller_Admin::mathFact(strlen($keys)) / (Controller_Admin::mathFact(strlen($keys) - $random_length) * Controller_Admin::mathFact($random_length))) - $url_sample_space[0]['count'];
            
        $this->template->content = View::Forge('admin/index',$data);
    }
    
    public function action_profiler()
    {
        
        $profiler = Session::get('profiler');
        if(empty($profiler) === false)
        {
            Session::Delete('profiler');
        }
        else
        {
            Session::Set('profiler',true);
        }
        Response::Redirect_Back();
    }
    
    public function action_add_blocked($block = null)
    {
        if(empty($block) === true)
        {
            $block = $_POST['blocked'];
        }
        else
        {
            $block = str_replace('-','.',$block);
        }
        
        $blacklist_item = Model_Blacklist::query()
                ->where('expression',$block)
                ->get_one();
                
        if(empty($blacklist_item) === true)
        {
            $blacklist_item = Model_Blacklist::forge(array(
                'expression' => $block,
            ));
            
            $blacklist_item->save();
            
            if(Input::is_ajax() === true)
            {
                
                echo $blacklist_item->id;
            }
        }
        else
        {
            if(Input::is_ajax() === true)
            {
                print_r(Format::forge(array('error' => 'Already Exists'))->to_json());
            }
            else
            {
                Session::set('error',$block.' is already in the blacklist!');
            }
        }
        
        if(Input::is_ajax() === true)
        {
            die;
        }
        Response::Redirect_Back();
    }
    
    public function action_remove_blocked()
    {
        $blacklist_item = Model_Blacklist::query()
            ->where('id',$_POST['blocked_id'])
            ->get_one();
            
        $blacklist_item->delete();
    }
    
}