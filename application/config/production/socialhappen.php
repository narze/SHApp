<?php
//SocialHappen
$config['app_id'] = 10014; //motherday
$config['app_secret_key'] = '41ee728424904e654348ea66949db013';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap';
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'shappdb';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '350771478331017';
$config['facebook_app_secret'] = '59b632c04dc24bae06d81316b8c1e294';
$config['facebook_app_scope'] = 'publish_stream,user_likes,email,user_photos';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '350771478331017';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/motherday/';

$config['randomapp_settings'] = array(
	'app_title' => 'บอกรักแม่...ขอแค่ 3 คำ',
	'profile_image_width' => 50,
	'profile_image_height' => 50,
	'profile_image_x' => 17,
	'profile_image_y' => 17,
	'profile_image_type' => 'square',
	'profile_image_border' => 2,
	'profile_image_border_color' => '#FFFFFF',
	'profile_name_enable' => TRUE,
	'profile_name_size' => 24,
	'profile_name_angle' => 0,
	'profile_name_x' => 80,
	'profile_name_y' => 33,
	'profile_name_color' => '#FFFFFF',
	'default_message' => "บอกรักแม่ของคุณใน 3 คำ ที่แอพนี้",
	'max_ramdom_number' => 14, //Total files (if $config['static_server_enable'] = TRUE)
	'random_image_extension' => 'jpg',
	'random_image_as_background' => TRUE,
	'app_bgcolor' => '#abd8d1',
	'maximum_times_played' => 5,
	'cooldown' => 21600,
	'gender_separate' => FALSE
);

$config['image_scores_enable'] = FALSE;
$config['image_scores'] = array(
	// 'male_1' => array(80, 100),
	// 'female_1' => array(80, 100),
	// 'position_x' => 315,
	// 'position_y' => 115
);

$config['static_app_enable'] = TRUE;
$config['static_app_url'] = 'https://apps.socialhappen.com/static/';
$config['static_app_message'] = 'อัพโหลดเรียบร้อย คลิกเพื่อดูภาพ';

$config['google_analytics_tracking_id'] = 'UA-18943856-16';

$config['userdata_app_url'] = 'https://app2.socialhappen.com/userdata/';