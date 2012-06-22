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
$config['facebook_app_scope'] = 'publish_stream,user_likes,email,user_photos';
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
$config['static_server_path'] = 'http://static.localhost.com/apps/statuspoem/';

$config['randomapp_settings'] = array(
	'app_title' => 'สุนทรผึ้ง',
	'profile_image_width' => 150,
	'profile_image_height' => 150,
	'profile_image_x' => 38,
	'profile_image_y' => 101,
	'profile_image_type' => 'square',
	'profile_image_border' => 0,
	'profile_image_border_color' => '#000',
	'profile_name_enable' => TRUE,
	'profile_name_size' => 16,
	'profile_name_angle' => 0,
	'profile_name_x' => 61,
	'profile_name_y' => 60,
	'profile_name_color' => '#AD0000',
	'default_message' => "สุนทรผึ้ง",
	'max_ramdom_number' => 13, //Total files (if $config['static_server_enable'] = TRUE)
	'random_image_extension' => 'jpg',
	'random_image_as_background' => TRUE,
	'app_bgcolor' => '#FFF',
	'maximum_times_played' => 5,
	'cooldown' => 21600
);

$config['image_scores_enable'] = FALSE;
$config['image_scores'] = array(
	// 'male_1' => array(80, 100),
	// 'male_2' => array(80, 100),
	// 'male_3' => array(80, 100),
	// 'male_4' => array(60, 79),
	// 'male_5' => array(60, 79),
	// 'male_6' => array(30, 59),
	// 'male_7' => array(30, 59),
	// 'male_8' => array(0, 29),
	// 'male_9' => array(0, 29),
	// 'male_10' => array(0, 29),
	// 'male_11' => array(555, 555),
	// 'male_12' => array(-20, -1),
	// 'female_1' => array(80, 100),
	// 'female_2' => array(80, 100),
	// 'female_3' => array(80, 100),
	// 'female_4' => array(80, 100),
	// 'female_5' => array(60, 79),
	// 'female_6' => array(60, 79),
	// 'female_7' => array(30, 59),
	// 'female_8' => array(30, 59),
	// 'female_9' => array(70, 100),
	// 'female_10' => array(0, 29),
	// 'female_11' => array(0, 29),
	// 'female_12' => array(-20, -1),
	// 'position_x' => 315,
	// 'position_y' => 115
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.localhost.com/static_app/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-13';

$config['userdata_app_url'] = 'https://apps.localhost.com/userdata/';