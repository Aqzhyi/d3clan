<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']           = "home/index";
$route['404_override']                 = '';

$route['admin/live-channels']          = 'admin/live_channels';
$route['admin/news-bot']               = 'admin/news_bot';
$route['admin/news-bot/(:any)']        = 'admin/news_bot/$1';

$route['game/(:any)/(:any)']           = 'game/index/$1/$2';
$route['game/(:any)/(:any)/(:any)']    = 'game/index/$1/$2/$3';

$route['trade/assist-sell']            = 'trade/assist_sell';

$route['vod/(:any)/(:any)']            = 'vod/index/$1/$2';

// D-Girls選拔
$route['event/2012-girls-vote']         = 'event/girls_vote_2012';
$route['event/2012-girls-vote/(:any)']  = 'event/girls_vote_2012/$1';

// MSI盃
$route['event/2012-msi']         = 'event/msi_game_2012';
$route['event/2012-msi/(:any)']  = 'event/msi_game_2012/index/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */