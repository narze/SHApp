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
$config['facebook_app_scope'] = 'user_about_me,publish_stream,user_likes,email';
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
$config['static_server_path'] = 'http://static.localhost.com/apps/ghost/';

$config['randomapp_settings'] = array(
	'app_title' => 'ล่า ท้า ผี คุณจะเจอผีแบบไหน?',
	'profile_image_size' => 65,
	'profile_image_x' => 70,
	'profile_image_y' => 48,
	'profile_image_type' => 'square',
	'profile_image_border' => 3,
	'profile_image_border_color' => '#fff',
	'profile_name_enable' => FALSE,
	'profile_name_size' => 13,
	'profile_name_angle' => 0,
	'profile_name_x' => 0,
	'profile_name_y' => 0,
	'profile_name_color' => '#000',
	'default_message' => "ล่า ท้า ผี คุณจะเจอผีแบบไหน? เข้าไปเจอได้ที่นี่",
	'max_ramdom_number' => 36, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#FFF',
	'maximum_times_played' => 3,
	'cooldown' => 21600
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.localhost.com/static_app/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-2';

$config['userdata_app_url'] = 'https://apps.localhost.com/userdata/';