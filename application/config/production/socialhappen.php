<?php
//SocialHappen
$config['app_id'] = 10005;
$config['app_secret_key'] = '41ee728424904e654348ea66949db013';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'weddingplace';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '356226374435146';
$config['facebook_app_secret'] = '0c2e3143c10694271b75388591ac113a';
$config['facebook_app_scope'] = 'user_about_me,publish_stream,user_likes';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '356226374435146';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/weddingplace/';

$config['randomapp_settings'] = array(
	'app_title' => 'คุณจะได้แต่งงานที่ไหน',
	'profile_image_size' => 70,
	'profile_image_x' => 42,
	'profile_image_y' => 22,
	'profile_image_type' => 'square',
	'profile_image_facebook_size' => 50,
	'default_message' => "คุณจะได้แต่งงานที่ไหน เข้าไปดูได้ที่",
	'max_ramdom_number' => 16, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#000',
	'maximum_times_played' => 1,
	'cooldown' => 14400
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.socialhappen.com/static/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-7';