<?php
class Controller_Search extends Controller_Template
{
    public static function get_users($pagination = null)
    {
        $term = Input::GET('search');
        
        $results =  \Model\Auth_User::query()
            ->related('metadata')
            ->where('username', 'LIKE', '%'.$term.'%')
            ->or_where('email', 'LIKE', '%'.$term.'%')
            ->or_where_open()
                ->where('metadata.value', 'LIKE', '%'.$term.'%')
                ->where('metadata.key', 'fullname')
            ->or_where_close();
            if(empty($pagination) === false)
            {
                $results = $results->rows_offset($pagination->offset)
                    ->rows_limit($pagination->per_page)
                    ->get();
            }
            else
            {
                $results = $results->count();
            }
            
            
        return $results;
    }
    
    public static function get_urls($all, $pagination = null, $images = false)
    {
        $term = Input::GET('search');
        
        
        $results =  Model_Url::query()
            ->where('short_url','LIKE','%'.$term.'%')
            ->or_where('url','LIKE','%'.$term.'%');
            
            if($all === false)
            {
                $results->where('user_id',static::$user_id);
            }
            if($images == false || $images == "false")
            {
                
                $results->where('url','NOT LIKE',Uri::Create('assets/screenshots').'%');
            }
            else
            {
                $results->where('url','LIKE',Uri::Create('assets/screenshots').'%');
            }
            
            if(empty($pagination) === false)
            {
                $results = $results->rows_offset($pagination->offset)
                    ->rows_limit($pagination->per_page)
                    ->get();
            }
            else
            {
                $results = $results->count();
            }
            
        return $results;
    }
}
