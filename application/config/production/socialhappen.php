<?php
//SocialHappen
$config['app_id'] = 1;
$config['app_secret_key'] = '11111111111111111111111111111111';
$config['api_url'] = 'https://socialhappen.com/apiv2/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'static';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '212677235501808';
$config['facebook_app_secret'] = '79284c8a972b17e25708ea3755425d85';
$config['facebook_app_scope'] = 'user_about_me,email';

//MockupHappen : Use only if you want to use app without socialhappen api calls
//This will mockup all api request and return values corresponding to api names
//See mockuphappen config file for for information
//Or you can just use :
//if($this->config->item('mockuphappen_enable')){
//	do something without calling socialhappen api request	
//} else {
//	do socialhappen api request
//}
$config['mockuphappen_enable'] = FALSE;

$config['google_analytics_tracking_id'] = 'UA-18943856-6';