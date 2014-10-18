<?php

class Controller_Bring extends Controller
{
    public function action_index()
    {        
       \Controller_Migrate::migrate();
        Response::Redirect(Uri::Create('/'));
    }
    

}
