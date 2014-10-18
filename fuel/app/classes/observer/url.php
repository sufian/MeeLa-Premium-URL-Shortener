<?php

class Observer_Url extends Orm\Observer
{

    public function after_load(Model_Url $url)
    {
     
        if(Uri::Current() != Uri::Create('core/404'))
        {
            if(strpos($url->url, Uri::Create('assets/screenshots')) !== false)
            {
                if($url->custom == false)
                {
                    $url->custom = $url->url;
                    $url->url = 'Image URL';
                }
                else
                {
                    $url->custom = $url->url;
                    $url->url = 'Image : '.$url->short_url;
                }
            }
        }
    }
}