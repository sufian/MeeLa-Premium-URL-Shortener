<?php

class Controller_Login extends Controller_Template
{
    
    public function action_index()
    {
         // was the login form posted?
        if (\Input::method() == 'POST')
        {
            // perform a login
            if (Auth::login(Input::Post('username'), Input::Post('password')))
            {
                // the user is succesfully logged in
                \Response::redirect_back('home');
            }
            else
            {
                // ERROR USER NAME OR PASS BAD
                $user = Model_User::query()
                    ->where('username',Input::Post('username'))
                    ->get_one();
                if(empty($user) === false)
                {
                    Session::Set('error','Invalid password!');
                }
                else
                {
                    Session::Set('error','There is no username / email : '.Input::Post('username'));
                }
            }
        }
        $this->template->content = View::forge('login/index');
    }
    
    public function action_logout()
    {
        Auth::logout();
        Session::Delete();
        \Response::redirect('home');
    }
    
    public function action_recover($hash = null)
    {
        if(Input::Method() === "POST")
        {
            if ($user = \Model\Auth_User::find_by_email(Input::POST('email')))
            {
                // generate a recovery hash
                $hash = \Auth::instance()->hash_password(\Str::random()).$user->id;
                
                 // and store it in the user profile
                \Auth::update_user(
                    array(
                        'lostpassword_hash' => $hash,
                        'lostpassword_created' => time()
                    ),
                    $user->username
                );
                
                // send an email out with a reset link
                \Package::load('email');
                $email = \Email::forge();
                
                $html = 'Your password recovery link <a href="'.Uri::Create('login/recover/'.$hash).'">Recover My Password!</a>';
                
                // use a view file to generate the email message
                $email->html_body($html);
                
                // give it a subject
                $email->subject(\Settings::Get('site_name').' Password Recovery');
                
                
                // GET ADMIN EMAIL FROM SETTINGS?
                $admin_email = Settings::get('admin_email');
                if(empty($admin_email) === false)
                {
                    $from = $admin_email;
                }
                else
                {
                    $from = 'support@'.str_replace('http:','',str_replace('/','',Uri::Base(false)));
                }
                
                
                $email->from($from);
                $email->to($user->email, $user->fullname);

                // and off it goes (if all goes well)!
                try
                {
                    // send the email
                    $email->send();
                    Session::set('success','Email has been sent to '.$user->email.'! Please check your spam folder!');
                }
                
                catch(\Exception $e)
                {
                    Session::Set('error','We failed to send the eamil , contact '.$admin_email);
                    \Response::redirect_back();
                }
            }
            else
            {
                Session::Set('error','Sorry there is not a matching email!');
            }
        }
        elseif(empty($hash) === false)
        {
            $hash = str_replace(Uri::Create('login/recover/'),'',Uri::current());
            $user = substr($hash, 44);
            
            if ($user = \Model\Auth_User::find_by_id($user))
            {
                // do we have this hash for this user, and hasn't it expired yet , must be within 24 hours
                if (isset($user->lostpassword_hash) and $user->lostpassword_hash == $hash and time() - $user->lostpassword_created < 86400)
                {
                    // invalidate the hash
                    \Auth::update_user(
                        array(
                            'lostpassword_hash' => null,
                            'lostpassword_created' => null
                        ),
                        $user->username
                    );
    
                    // log the user in and go to the profile to change the password
                    if (\Auth::instance()->force_login($user->id))
                    {
                        Session::Set('current_password',Auth::reset_password($user->username));
                        Response::Redirect(Uri::Create('user/settings'));
                    }
                }
                
            }
            
            Session::Set('error','Invalid Hash!');
        }
        $this->template->content = View::forge('login/recover');
    }
    
    public function action_signup()
    {
        // already logged in?
        if (\Auth::check())
        {
            // yes, so go back to the page the user came from, or the
            // application home if no previous page can be detected
            \Response::redirect_back('home');
        }
        
        // was the login form posted?
        if (\Input::method() == 'POST')
        {
            // Default Group
            // 3 Users
            // Moderators
            // 5 Admins
            
            // call Auth to create this user
            $created = \Auth::create_user(
                Input::Post('username'), // USER LOGIN
                Input::Post('password'), // PASSWORD
                Input::Post('email'), // EMAIL
                \Config::get('application.user.default_group', 3), // DEFAULT GROUP
                array(
                    'fullname' =>   Input::Post('name'),
                )
            );
            
            // if a user was created succesfully
            if ($created)
            {
                \Auth::instance()->login(\Input::param('email'), \Input::param('password'));
                // and go back to the previous page, or show the
                // application home if we don't have any
                \Response::redirect_back('home');
            }
            else
            {
                // oops, creating a new user failed?
            }
        }
        
        $this->template->content = View::forge('login/signup');
    }
}
