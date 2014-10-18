<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'core/404',    // The main 404 route
        
	'signup'   => 'login/signup',    
	'logout'   => 'login/logout',

	'admin'				=> 'dashboard/view/all',
	'admin/settings'	=> 'admin',
	'admin/users'		=> 'users',
	'admin/urls'		=> 'url/list/all',
	'admin/urls/preview'=> 'url/list/true/false/true',

	'user'				=> 'dashboard/view',
	'user/settings'		=> 'users/profile',
	'user/urls'			=> 'url/list',
	'user/images'			=> 'url/list/false/true',
	'stats/(:semgent)' => 'url/stats/$1',

);
