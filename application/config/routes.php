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

$route['default_controller'] = 'home';

//Admin
$route['admincp'] = 'admincp/index';

//HOME 
$route['facebook'] = 'home/fb';
$route['twitter'] = 'home/tw';
$route['twitter/:any'] = 'home/tw_callback';
$route['signin'] = 'home/signin';
$route['register/:any'] = 'home/register';
$route['register'] = 'home/register';
$route['forgotpassword/:any'] = 'home/forgotpassword';
$route['forgotpassword'] = 'home/forgotpassword';
$route['resendemail'] = 'home/resendemail';
$route['announcements'] = 'user/announcements';
$route['announcements/([0-9]+)'] = 'user/announcements/$1';
$route['new/(:any)'] = 'user/new_info/$1';
$route['wall'] = 'user/wall';
$route['(:any)/wall'] = 'user/wall/$1';
$route['(:any)/photo/([0-9]+)'] = 'user/photo/$2'; 
$route['logout'] = 'user/logout';
//LOOKBOOK
$route['mystuff'] = 'mystuff/main';
$route[':any/stuff'] = 'mystuff/main';
$route[':any/stuff/(:any)'] = 'mystuff/$1';
//

//USER 
$route['editprofile'] = 'user/editprofile';
$route['pms'] = 'user/pms';
$route['pms/([a-z]+)'] = 'user/pms/$1';
$route['pms/([0-9]+)'] = 'user/pms/reply/$1';

//DRESSUP
$route['dressup'] = 'dressup/index';
$route['inventory'] = 'dressup/inventory';
$route['dressup/dress/(:any)'] = 'dressup/dress/$1';
$route['dressup/regenerate_images/([0-9]+)'] = 'dressup/regenerate_images/$1';
$route['dressup/(dress|ajax|edit_dressup|outfits)'] = 'dressup/$1';
$route['dressup/(:any)'] = 'dressup/index/$1';
$route['dressup/dress/([0-9]+)'] = 'dressup/dress/$1';


//UPLOAD PHOTO
$route['upload'] = 'upload/index';
$route['upload/photo_upload/(:num)'] = 'upload/photo_upload/$1';
$route['upload/photo_upload/(:num)/edit'] = 'upload/photo_upload/$1';

//EXPLORE
$route['explore'] = 'explore/index';


$route['users_shop'] = 'explore/users_shop';
$route['users_shop/(:any)'] = 'explore/users_shop/$1';
$route['users_shop/(:any)/:any'] = 'explore/users_shop/$1';

$route['shops'] = 'explore/shops';
$route['shops/:any'] = 'explore/shops';

$route['userauction'] = 'explore/userauction';
$route['userauction/(:any)'] = 'explore/userauction/$1';
$route['userauction/(:any)/:any'] = 'explore/userauction/$1';

$route['auction'] = 'explore/auction';
$route['auction/:any'] = 'explore/auction';

$route['buy_jewels'] = 'explore/buy_jewels';
$route['buy_jewels/:any'] = 'explore/buy_jewels';

$route['bank'] = 'explore/bank';

$route['brands'] = 'explore/brands';
$route['brands/(:any)'] = 'explore/brands/$1';
$route['brands/(:any)/:any'] = 'explore/brands/$1';

$route['find_friends'] = 'explore/find_friends';
$route['find_friends/:any'] = 'explore/find_friends';
$route['myfriends'] = 'explore/myfriends';
$route['invite_friends'] = 'explore/invite_friends';
$route['item/(:any)'] = 'explore/item/$1';
$route['send_free_gifts'] = 'explore/send_free_gifts';
$route['my_gifts'] = 'explore/my_gifts';
$route['latest_photos'] = 'explore/latest_photos';
$route['latest_dressups'] = 'explore/latest_dressups';
$route['featured_dressups'] = 'explore/featured_dressups';
$route['featured_photos'] = 'explore/featured_photos';
$route['top_dressups'] = 'explore/top_dressups';
$route['top_photos'] = 'explore/top_photos';
$route['bugwall'] = 'explore/bugwall';


$route['(:any)/friends'] = 'user/friends/$1';
$route['(:any)/dressup_calendar'] = 'user/dressup_calendar/$1';
$route['(:any)/dressup/([0-9]+)'] = 'user/dressup/$2';
$route['([a-z]+)/([a-z_]+)'] = '$1/$2';
$route['([a-z]+)/([a-z_]+)/([a-z_0-9-]+)'] = '$1/$2/$3';

$route[':any'] = 'user/index';
//Page 404
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */