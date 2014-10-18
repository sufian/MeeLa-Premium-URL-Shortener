<?php

class Controller_Dashboard extends Controller_Template
{
    public function before()
    {
        parent::before();
        
        if(Auth::Check() === false)
        {
            Response::Redirect(Uri::Base());
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
    
    public function action_view($all = null)
    {
        $limit = 25;
        
        if(empty($all) === false)
        {
            // check for admin
            if(!Auth::member(5))
            {
                Response::Redirect(Uri::Create('user'));
            }
        }
        
        // Total Urls
        $data['total_urls'] = Model_Url::query();
        if(empty($all) === true)
        {
            $data['total_urls']->where('user_id',static::$user_id);
        }

        $data['total_urls'] = $data['total_urls']->count();
        
        if(Uri::Current() == Uri::Create('admin'))
        {
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
            
            $data['urls_left'] = (Controller_Dashboard::mathFact(strlen($keys)) / (Controller_Dashboard::mathFact(strlen($keys) - $random_length) * Controller_Dashboard::mathFact($random_length))) - $url_sample_space[0]['count'];
        }
            
        // Total Hits
        $data['total_hits'] = DB::select(DB::Expr('SUM(hits) as hits'))->from('urls');
            if(empty($all) === true)
            {
                $data['total_hits']->where('user_id',static::$user_id);
            }
        
        $data['total_hits'] = $data['total_hits']->execute()->as_array();
        $data['total_hits'] = reset($data['total_hits']);
        $data['total_hits'] = $data['total_hits']['hits'];
        
        // No Clicks 
        $data['no_clicks'] = Model_Url::query()
            ->where('hits',0);
            if(empty($all) === true)
            {
                $data['no_clicks']->where('user_id',static::$user_id);
            }
            
        $data['no_clicks'] = $data['no_clicks']->count();

        // Total Custom Urls
        $data['total_custom_urls'] = Model_Url::query()
            ->where('custom',1);
            if(empty($all) === true)
            {
                $data['total_custom_urls']->where('user_id',static::$user_id);
            }
            
        $data['total_custom_urls'] = $data['total_custom_urls']->count();

        // Created Today Urls
        $data['created_today'] = Model_Url::query()
            ->where('created_at','>=',strtotime('today 12:01 AM'));
            if(empty($all) === true)
            {
                $data['created_today']->where('user_id',static::$user_id);
            }
            
        $data['created_today'] = $data['created_today']->count();
            
        // Most visted Urls
        
        $data['most_visited'] = Model_Url::query();
            if(empty($all) === true)
            {
                $data['most_visited']->where('user_id',static::$user_id);
            }
            $data['most_visited']->order_by('hits','desc')
            ->limit($limit);
            
        $data['most_visited'] = $data['most_visited']->get();

        // Created Today Urls
        $data['recently_created'] = Model_Url::query();
            if(empty($all) === true)
            {
                $data['recently_created']->where('user_id',static::$user_id);
            }
            $data['recently_created']->order_by('created_at','desc')
            ->limit($limit);
            
        $data['recently_created'] = $data['recently_created']->get();
            
        if(empty($all) === true)
        {
            $data['recently_viewed'] = Model_Url::query()
                ->order_by('updated_at','desc')
                ->where('updated_at','!=','created_at')
                ->where('user_id',static::$user_id)
                ->limit($limit)
                ->get();
        }
        else
        {
            $data['recently_viewed'] = Model_Url::query()
                ->order_by('updated_at','desc')
                ->where('updated_at','!=',null)
                ->limit($limit)
                ->get();
        }

        // Short URL Stats string for google graphs
        $m= date("m");
        $de= date("d");
        $y= date("Y");
        $new_results = '';
        
        if(empty($all) === true)
        {
            $date_vist_counts = DB::query('  
                SELECT
                COUNT(url_stats.id) as hits,
                DAY(FROM_UNIXTIME(url_stats.created_at)) as day,
                MONTH(FROM_UNIXTIME(url_stats.created_at)) as month,
                YEAR(FROM_UNIXTIME(url_stats.created_at)) as year
                FROM `url_stats`
                INNER JOIN `urls` ON urls.id = url_stats.url_id
                WHERE url_stats.created_at >= '.strtotime('12:01 AM TODAY - 15 days').'
                AND urls.user_id = '.static::$user_id.'
                GROUP BY year,month,day')
                ->execute()
                ->as_array();
            
            $date_created_counts = DB::query('  
                SELECT
                COUNT(id) as created,
                DAY(FROM_UNIXTIME(created_at)) as day,
                MONTH(FROM_UNIXTIME(created_at)) as month,
                YEAR(FROM_UNIXTIME(created_at)) as year
                FROM `urls`
                WHERE created_at >= '.strtotime('12:01 AM TODAY - 15 days').'
                AND user_id = '.static::$user_id.'
                GROUP BY year,month,day')
                ->execute()
                ->as_array();
        }
        else
        {
              $date_vist_counts = DB::query('  
                SELECT
                COUNT(id) as hits,
                DAY(FROM_UNIXTIME(created_at)) as day,
                MONTH(FROM_UNIXTIME(created_at)) as month,
                YEAR(FROM_UNIXTIME(created_at)) as year
                FROM `url_stats`
                WHERE created_at >= '.strtotime('12:01 AM TODAY - 15 days').'
                GROUP BY year,month,day')
                ->execute()
                ->as_array();
            
            $date_created_counts = DB::query('  
                SELECT
                COUNT(id) as created,
                DAY(FROM_UNIXTIME(created_at)) as day,
                MONTH(FROM_UNIXTIME(created_at)) as month,
                YEAR(FROM_UNIXTIME(created_at)) as year
                FROM `urls`
                WHERE created_at >= '.strtotime('12:01 AM TODAY - 15 days').'
                GROUP BY year,month,day')
                ->execute()
                ->as_array();
                
        }
        
        $created_counts_array = null;
        foreach($date_created_counts as $created_counts)
        {
            $created_counts_array[$created_counts['year'].'-'.$created_counts['month'].'-'.$created_counts['day']] = $created_counts;
        }
        
        foreach($date_vist_counts as $vists)
        {
            if(isset($created_counts_array[$vists['year'].'-'.$vists['month'].'-'.$vists['day']]) === true)
            {
                $created_count = $created_counts_array[$vists['year'].'-'.$vists['month'].'-'.$vists['day']]['created'];
            }
            else
            {
                $created_count = 0;
            }
            
            $date_timestamp = strtotime($vists['year'].'-'.$vists['month'].'-'.$vists['day']);
            
            $new_results .= "['".date('l dS F Y',$date_timestamp)."', ".$vists['hits'].", ".$created_count."], ";
        }
        
        $data['short_url_stats'] =  $new_results;
        
        $new_results = '';
        // Get countries Stats
        
        if(empty($all) === true)
        {
            $countries = DB::select('country',DB::expr('count(url_stats.id) as hits'))
                ->from('url_stats')
                ->join('urls','LEFT')->on('urls.id', '=', 'url_stats.url_id')
                ->where('urls.user_id',static::$user_id)
                ->group_by('country');
        }
        else
        {
            $countries = DB::select('country',DB::expr('count(id) as hits'))->from('url_stats')->group_by('country');
        }
        
        $countries = $countries->execute()->as_array();

        if(empty($countries) === false)
        {
            foreach($countries as $country)
            {
                $new_results .= "['".$country['country']."', ".$country['hits']."], ";
            }
        }
        
        $data['country_stats'] = $new_results;
        
        $data['short_urls'] = Model_Url::query();
            if(empty($all) === true)
            {
                $data['short_urls']->where('user_id',static::$user_id);
            }
            $data['short_urls']->rows_limit($limit);
            
        $data['short_urls'] = $data['short_urls']->get();
            
        $this->template->content = View::Forge('dashboard/index',$data);
    }
}
