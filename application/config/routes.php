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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['translate_uri_dashes'] = FALSE;

// Frontend Routes
$route['katalog'] = 'product/catalog';
$route['katalog/(:any)'] = 'product/catalog/$1';
$route['produk/(:any)'] = 'product/detail/$1';
$route['keranjang'] = 'cart/index';
$route['checkout'] = 'checkout/index';
$route['checkout/(:any)'] = 'checkout/$1';
$route['login'] = 'auth/login';

$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['profil'] = 'profile/index';
$route['profil/pesanan/(:any)'] = 'profile/order_detail/$1';
$route['profil/ubah_ekspedisi/(:any)'] = 'profile/update_shipping/$1';
$route['profil/ubah-ekspedisi/(:any)'] = 'profile/update_shipping/$1';
$route['profil/invoice/(:any)'] = 'profile/invoice/$1';
$route['profil/pesanan/(:any)/invoice'] = 'profile/invoice/$1';
$route['cari'] = 'search/index';

// Cart AJAX Routes
$route['cart/add'] = 'cart/add';
$route['cart/update'] = 'cart/update';
$route['cart/remove'] = 'cart/remove';
$route['cart/count'] = 'cart/count';

// Newsletter & Leads Routes
$route['newsletter/subscribe'] = 'newsletter/subscribe';
$route['leads/subscribe'] = 'newsletter/subscribe';

// Admin Routes
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/products'] = 'admin/products';
$route['admin/products/(:any)'] = 'admin/products/$1';
$route['admin/categories'] = 'admin/categories';
$route['admin/categories/(:any)'] = 'admin/categories/$1';
$route['admin/orders'] = 'admin/orders';
$route['admin/orders/(:any)'] = 'admin/orders/$1';
$route['admin/users'] = 'admin/users';
$route['admin/users/(:any)'] = 'admin/users/$1';
$route['admin/banners'] = 'admin/banners';
$route['admin/banners/(:any)'] = 'admin/banners/$1';
$route['admin/couriers'] = 'admin/couriers';
$route['admin/couriers/(:any)'] = 'admin/couriers/$1';
$route['admin/pages'] = 'admin/pages';
$route['admin/pages/(:any)'] = 'admin/pages/$1';
$route['admin/settings'] = 'admin/settings';
$route['admin/settings/(:any)'] = 'admin/settings/$1';

// Static Informational Pages Routes
$route['tentang-kami'] = 'page/view/tentang-kami';
$route['cara-belanja'] = 'page/view/cara-belanja';
$route['kebijakan-privasi'] = 'page/view/kebijakan-privasi';
$route['syarat-ketentuan'] = 'page/view/syarat-ketentuan';
$route['halaman/(:any)'] = 'page/view/$1';
$route['(:any)'] = 'page/view/$1';



