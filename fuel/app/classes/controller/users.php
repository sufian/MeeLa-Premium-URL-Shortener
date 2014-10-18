<?php

class Controller_Users extends Controller_Template
{
    public function action_index($search = null)
    {
        // check for admin
        if(!Auth::member(5))
        {
            \Response::redirect_back('home');
        }
        
        if(Input::Method() === 'POST')
        {
            $users = Input::POST();
            
            if(empty($users) === false)
            {
                // Update the users 
                foreach($users as $user_id => $new_group)
                {
                    $found_user = Model_User::Find(str_replace('user_role_','',$user_id));
                    if(empty($found_user) === false)
                    {
                        $found_user->group_id = $new_group;
                        $found_user->save();
                    }
                }
            }
        }
        
        if(Input::Method() === 'GET' && Input::Get('search'))
        {
            $data['total_count'] = Controller_Search::get_users();
            
            $pagination = Settings::pagination($data['total_count']);
            
            $data['users'] = Controller_Search::get_users($pagination);
            $data['search'] = Input::GET('search');
        }
        else
        {
            $data['total_count'] = Model_User::query()->where('id','!=',static::$user_id)->count();
            
            $pagination = Settings::pagination($data['total_count']);
            
            $data['users'] = Model_User::query()
                ->where('id','!=',static::$user_id)
                ->order_by('username', 'ASC')
                ->rows_offset($pagination->offset)
                ->rows_limit($pagination->per_page)
                ->get();
        }
        
        $data['pagination'] = $pagination->render();
        
        $this->template->content = View::Forge('admin/users',$data);
        
    }
    
    public function action_profile()
    {
        $data = null;
        
        $data['user']= Model_User::query()
            ->related('user_providers')
            ->where('id',static::$user_id)
            ->get_one();
            
        $data['api_key'] = Auth::get('api_key');
        
        if(Input::Method() == 'POST')
        {
           
            $new_password = Input::Post('new_password');
            $current_password = Input::Post('current_password');
            
            if(empty($new_password) === false)
            {
                if(empty($current_password) === true)
                {
                    Session::set('error','You must enter your old password in first!');
                    $this->template->content = View::Forge('settings/profile',$data);
                    return;
                }
                else
                {
                    if(Auth::change_password($current_password, $new_password) === false)
                    {
                        Session::set('error','Wrong Password');
                        $this->template->content = View::Forge('settings/profile',$data);
                        return;
                    }
                    else
                    {
                        Session::delete('current_password');
                    }
                }
                
            }
            
            // update the data for the current user
            try
            {
                Auth::update_user(array(
                    'email'        => Input::Post('email'),  // set a new email address
                    'fullname' => Input::Post('full_name'), 
                ));
            }
            catch (Exception $e)
            {
                Session::set('error',$e->getMessage());
                $this->template->content = View::Forge('settings/profile',$data);
                return;
            }
            
           
            
            Session::set('success','Your profile has been updated');
          
        }

            
        $this->template->content = View::Forge('settings/profile',$data);
    }
    
    public function action_remove($user_id)
    {
        // check for admin
        if(!Auth::member(5))
        {
            \Response::redirect_back('home');
        }
        
        $user = Model_User::query()
            ->where('id',$user_id)
            ->get_one();
            
        $user->delete();
        
        Response::Redirect('users');
    }
    
}