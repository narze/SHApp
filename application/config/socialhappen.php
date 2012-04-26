<?php
//SocialHappen
$config['app_id'] = 5;
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
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '204755022911798';

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
$config['static_server_path'] = 'http://static.localhost.com/apps/weddingplace/';

$config['randomapp_settings'] = array(
	'app_title' => 'คุณจะได้แต่งงานที่ไหน',
	'profile_image_size' => 50,
	'profile_image_x' => 155,
	'profile_image_y' => 51,
	'profile_image_type' => 'square',
	'profile_image_facebook_size' => 50,
	'default_message' => "คุณจะได้แต่งงานที่ไหน เข้าไปดูได้ที่",
	'max_ramdom_number' => 23, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#000',
	'maximum_times_played' => 1,
	'cooldown' => 14400
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.localhost.com/static_app/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-7';