<?php
class Controller_Home extends Controller_Template
{
	public function action_index()
	{
        $data = null;
        if(\Settings::Get('top_3_pods') === true)
        {
            $data['url_count'] = Model_Url::query()
            ->count();
                
		    $hits = DB::select(DB::Expr('SUM(hits) as hits'))->from('urls')->execute()->as_array();
		    $data['click_count'] = $hits[0]['hits'];
            
		    $random = DB::query('SELECT short_url FROM urls as r1 JOIN (Select (RAND() * (SELECT MAX(id) FROM urls)) as id) as r2 WHERE r1.id >= r2.id ORDER BY r1.id ASC LIMIT 1')->execute()->as_array();
            $random = reset($random);
            $data['random'] = $random['short_url'];
        }
            
        $limit = 15;
		
        if(\Settings::Get('recently_viewed_table') == 'true')
        {
            $data['recently_viewed'] = Model_Url::query()
                ->order_by('updated_at','desc')
                ->where('updated_at','!=','created_at')
                ->limit($limit)
                ->get();
        }
            
        if(\Settings::Get('recently_created_table') == 'true')
        {
            $data['recently_created'] = Model_Url::query()
                ->order_by('created_at','desc')
                ->limit($limit)
                ->get();
        }
            
        if(\Settings::Get('most_popular_table') == 'true')
        {
            $data['most_popular'] = Model_Url::query()
                ->order_by('hits','desc')
                ->order_by('hits','desc')
                ->limit($limit)
                ->get();
        }
            
        $this->template->content = View::forge('home/index',$data);
	}
}
