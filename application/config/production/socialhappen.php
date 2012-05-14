<?php
//SocialHappen
$config['app_id'] = 10008;
$config['app_secret_key'] = '41ee728424904e654348ea66949db013';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'hiddenprofile';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '239890886111529';
$config['facebook_app_secret'] = '22ec36c53ce49f0c9dec01a1bd156ac7';
$config['facebook_app_scope'] = 'publish_stream,user_likes,email,user_photos';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '239890886111529';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/hiddenprofile/';

$config['randomapp_settings'] = array(
	'app_title' => 'มีอะไรแฝงในรูปโปรไฟล์ของคุณ?',
	'profile_image_width' => 376,
	'profile_image_height' => 286,
	'profile_image_x' => 13,
	'profile_image_y' => 60,
	'profile_image_type' => 'square',
	'profile_image_border' => 0,
	'profile_image_border_color' => '#000',
	'profile_name_enable' => TRUE,
	'profile_name_size' => 13,
	'profile_name_angle' => 0,
	'profile_name_x' => 95,
	'profile_name_y' => 278,
	'profile_name_color' => '#000',
	'default_message' => "เล่นแอพได้ที่นี่",
	'max_ramdom_number' => 14, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#FFF',
	'maximum_times_played' => 3,
	'cooldown' => 21600
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.socialhappen.com/static/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-10';

$config['userdata_app_url'] = 'https://app2.socialhappen.com/userdata/';