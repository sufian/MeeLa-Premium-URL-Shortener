<?php
class Controller_Auth extends Controller_Template
{
    public function action_test()
    {
        echo 'test'; die;
    }
    public function action_login($provider = null)
    {
    
            if(\Auth::Check())
            {
                $user = Model_User::query()
                    ->related('user_providers')
                    ->where('id',static::$user_id)
                    ->get_one();
                
                if(empty($user->user_providers) === false)
                {
                    $provider_array = array();
                    foreach($user->user_providers as $provider)
                    {
                        $provider_array[] = $provider->provider;
                    }
                }
                
                if(in_array($provider,$provider_array))
                {
                    Response::Redirect(Uri::Base());
                }
            }
            
            // bail out if we don't have an OAuth provider to call
            if ($provider === null)
            {
                \Response::redirect_back();
            }
        
            // load Opauth, it will load the provider strategy and redirect to the provider
            \Auth_Opauth::forge();
    }
    
    public function action_callback()
    {
        // Opauth can throw all kinds of nasty bits, so be prepared
        try
        {
            // get the Opauth object
            $opauth = \Auth_Opauth::forge(false);
            
            // and process the callback
            $status = $opauth->login_or_register();
            
            // fetch the provider name from the opauth response so we can display a message
            $provider = $opauth->get('auth.provider', '?');
            
            // deal with the result of the callback process
            switch ($status)
            {
                // a local user was logged-in, the provider has been linked to this user
                case 'linked':
                    // inform the user the link was succesfully made
                    // and set the redirect url for this status
                     Session::set('success','You have connected your '.$provider.' account!');
                break;
            
                // the provider was known and linked, the linked account as logged-in
                case 'logged_in':
                    // inform the user the login using the provider was succesful
                    // and set the redirect url for this status
                break;
            
                // we don't know this provider login, ask the user to create a local account first
                case 'register':
                    // inform the user the login using the provider was succesful, but we need a local account to continue
                    // and set the redirect url for this status
                    
                    switch ($provider)
                    {
                        case 'Twitter' :
                            $user_login = $opauth->get('auth.raw.screen_name');
                            $email = $opauth->get('auth.raw.screen_name').'@twitter.com';
                        break;
                        case 'Google' :
                            $user_login = str_replace('@gmail.com','',$opauth->get('auth.raw.email'));
                            $email = $opauth->get('auth.raw.email');
                        break;
                        case 'Facebook' :
                            $user_login = $opauth->get('auth.raw.username');
                            $email = $opauth->get('auth.raw.username').'@facebook.com';
                        break;
                    }
                    
                    // call Auth to create this user
                    $found_user = Model_User::query()
                        ->where('username',$user_login)
                        ->or_where('email',$email)
                        ->get_one();
                        
                    if(empty($found_user) === false)
                    {
                        if($found_user->email == $email)
                        {
                            // FORCE LOGIN AND REGISTER
                            Auth::force_login($found_user->id);
                        }
                        else
                        {
                            // Username already taken
                            Session::set('error',$user_login.' , Username already taken, please register manually or try a differnt account');
                            Response::Redirect(Uri::Base());
                        }
                    }
                    else
                    {
                        $user_id = \Auth::create_user(
                            $user_login,
                            md5($opauth->get('auth.credentials.token')), // PASSWORD
                            $email,
                            \Config::get('application.user.default_group', 3), // DEFAULT GROUP
                            array(
                                'fullname' =>   $opauth->get('auth.info.name'),
                            )
                        );
                        
                        Controller_Auth::Create_User($opauth,$user_id);
                    }
                    
                    $opauth->login_or_register();
                    
                    Session::set('success','You have connected your '.$provider.' account!');
                break;
            
                // we didn't know this provider login, but enough info was returned to auto-register the user
                case 'registered':
                    // inform the user the login using the provider was succesful, and we created a local account
                    // and set the redirect url for this status
                break;
            
                default:
                    throw new \FuelException('Auth_Opauth::login_or_register() has come up with a result that we dont know how to handle.');
            }
            // redirect to the url set
            
            \Response::redirect(Uri::Base());
        }
        
        // deal with Opauth exceptions
        catch (\OpauthException $e)
        {
            Session::set('error',ucfirst($e->getMessage()).'!');
            \Response::redirect_back();
        }
        
        // catch a user cancelling the authentication attempt (some providers allow that)
        catch (\OpauthCancelException $e)
        {
            Session::set('error','Something went wrong!');
            \Response::redirect_back();
        }
    }
    
    public static function Create_User($opauth,$user_id = null)
    {
        if(empty($user_id) === true)
        {
            $user_id = static::$user_id;
        }
        
        if($user_id != 0 && empty($user_id) === false)
        {
            // call Opauth to link the provider login with the local user
            $insert_id = $opauth->link_provider(array(
                'parent_id' =>  $user_id,
                'user_id' =>  0,
                'provider' => $opauth->get('auth.provider'),
                'uid' => $opauth->get('auth.uid'),
                'access_token' => $opauth->get('credentials.token', null),
                'secret' =>$opauth->get('credentials.secret', null),
                'refresh_token' => $opauth->get('credentials.refresh_token', null),
                'expires' => $opauth->get('credentials.expires', null),
                'created_at' => time(),
            ));
        }
        else
        {
            Response::Redirect(Uri::Base());
        }
        
    }
}