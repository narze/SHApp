<?php
//SocialHappen
$config['app_id'] = 10004;
$config['app_secret_key'] = '41ee728424904e654348ea66949db013';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'posetonight';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = 'CHANGETHIS';
$config['facebook_app_secret'] = 'CHANGETHIS';
$config['facebook_app_scope'] = 'user_about_me,publish_stream,user_likes';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = 'CHANGETHIS';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/posetonight/';

$config['randomapp_settings'] = array(
	'app_title' => 'ท่ายากของคุณในคืนนี้',
	'profile_image_size' => 65,
	'profile_image_x' => 70,
	'profile_image_y' => 48,
	'profile_image_type' => 'square',
	'profile_image_facebook_size' => 50,
	'default_message' => "ท่ายากของคุณในคืนนี้",
	'max_ramdom_number' => 21, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#000'
);

$config['static_app_enable'] = FALSE;
$config['static_app_url'] = 'https://apps.socialhappen.com/static/';
$config['static_app_message'] = 'ดูภาพของคุณได้ที่';