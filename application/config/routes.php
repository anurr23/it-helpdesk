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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Custom Routes - Auth
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['daftar'] = 'auth/register';
$route['lupa-password'] = 'auth/lupa_password';
$route['lupa-password/cek-username'] = 'auth/check_username_reset';
$route['reset-password/(:any)'] = 'auth/reset_password/$1';
$route['daftar/cek-username'] = 'auth/check_username';

// Custom Routes - Profile
$route['akun'] = 'profile/index';
$route['akun/update'] = 'profile/update';

// Custom Routes - Ticket (User)
$route['buat-tiket'] = 'ticket/create';
$route['riwayat-tiket'] = 'ticket/history';
$route['status-tiket'] = 'ticket/status';
$route['detail-tiket/(:any)'] = 'ticket/detail/$1';

// Custom Routes - Approvals
$route['persetujuan'] = 'ticket/approval_list';
$route['persetujuan/guest/(:any)'] = 'ticket/approve_guest/$1';
$route['persetujuan/guest/proses/(:any)'] = 'ticket/process_approval/$1';
$route['persetujuan/it/(:any)'] = 'ticket/approve_it/$1';
$route['persetujuan/it/proses/(:any)'] = 'ticket/process_it_approval/$1';

// Custom Routes - Admin
$route['admin/beranda'] = 'admin/overview';
$route['admin/tiket'] = 'admin/dashboard';
$route['admin/riwayat'] = 'admin/history';
$route['admin/persetujuan'] = 'admin/approval_it';
$route['admin/pengguna'] = 'admin/users';
$route['admin/tambah-pengguna'] = 'admin/add_user_page';
$route['admin/edit-pengguna/(:any)'] = 'admin/edit_user_page/$1';
$route['admin/departemen'] = 'admin/departments';
$route['admin/profil'] = 'admin/profile';
$route['admin/detail-tiket/(:any)'] = 'admin/ticket_detail/$1';
$route['admin/tangani-tiket/(:any)'] = 'admin/resolve_ticket_page/$1';
$route['admin/simpan-pengguna'] = 'admin/add_user';
$route['admin/update-pengguna/(:any)'] = 'admin/edit_user/$1';
$route['admin/hapus-pengguna/(:any)'] = 'admin/delete_user/$1';
$route['admin/simpan-departemen'] = 'admin/add_dept';
$route['admin/update-departemen/(:any)'] = 'admin/edit_dept/$1';
$route['admin/hapus-departemen/(:any)'] = 'admin/delete_dept/$1';
$route['admin/proses-tiket/(:any)'] = 'admin/resolve_ticket/$1';
$route['admin/kirim-ulang-persetujuan/(:any)'] = 'admin/resend_approval/$1';
$route['monitoring-tiket'] = 'ticket/monitoring';
