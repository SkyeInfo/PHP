<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

$route['aboutMe.html'] = "aboutMe/index";

$route['article/(:num)'] = "article/index/$1";
$route['article.html/(:num)'] = "article/index/$1";

$route['arttag.html/(:num)/(:num)'] = "article/getArticlesByTag/$1/$2";
$route['arttag.html/(:num)'] = "article/getArticlesByTag/$1/0";

$route['archive/(:num)'] = "article/archive/$1";

$route['tech/(:num)'] = "article/cate/$1";
$route['tech'] = "article/cate/0";
$route['tech.html/(:num)'] = "article/cate/$1";
$route['tech.html'] = "article/cate/0";

$route['life/(:num)'] = "article/cate/$1";
$route['life'] = "article/cate/0";
$route['life.html/(:num)'] = "article/cate/$1";
$route['life.html'] = "article/cate/0";

$route['tags'] = "tags/index";
$route['tags.html'] = "tags/index";

$route['links/(:num)'] = "links/index/$1";
$route['links'] = "links/index/0";
$route['links.html/(:num)'] = "links/index/$1";
$route['links.html'] = "links/index/0";




