<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'login';
$route['login']              = 'login';
$route['login/auth']         = 'login/auth';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
