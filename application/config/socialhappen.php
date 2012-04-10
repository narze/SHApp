<?php
//SocialHappen
$config['app_id'] = 4;
$config['app_secret_key'] = 'cd14463efa98e6ee00fde6ccd51a9f6d';
$config['api_url'] = 'https://socialhappen.dyndns.org/socialhappen/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'shapp';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '204755022911798';
$config['facebook_app_secret'] = '9b4afe6394db990c68213c63f47c7d36';
$config['facebook_app_scope'] = 'user_about_me,publish_stream,user_likes';

//MockupHappen : Use only if you want to use app without socialhappen api calls
//This will mockup all api request and return values corresponding to api names
//See mockuphappen config file for for information
//Or you can just use :
//if($this->config->item('mockuphappen_enable')){
//	do something without calling socialhappen api request	
//} else {
//	do socialhappen api request
//}
$config['mockuphappen_enable'] = TRUE;
$config['static_server_enable'] = TRUE;
$config['static_server_path'] = 'http://static.localhost.com/apps/songkran/';

$config['randomapp_settings'] = array(
	'app_title' => 'สงกรานต์นี้...คุณจะโดนอะไรสาด? by SocialHappen',
	'profile_image_size' => 160,
	'profile_image_x' => 41,
	'profile_image_y' => 133,
	'profile_image_type' => 'large',
	'profile_image_facebook_size' => 160,
	'default_message' => "สงกรานต์นี้...คุณจะโดนอะไรสาด?",
	'max_ramdom_number' => 20, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#fff'
);