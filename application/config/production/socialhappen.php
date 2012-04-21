<?php
//SocialHappen
$config['app_id'] = 10001;
$config['app_secret_key'] = '6ec950b9cb9526f204bf14f4c97e4f2a';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'ghost';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '125984734199028';
$config['facebook_app_secret'] = '0a7b12697d16233101b6c455960207f5';
$config['facebook_app_scope'] = 'user_about_me,publish_stream,user_likes';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '125984734199028';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/ghost/';

$config['randomapp_settings'] = array(
	'app_title' => 'ล่า ท้า ผี คุณจะเจอผีแบบไหน?',
	'profile_image_size' => 65,
	'profile_image_x' => 70,
	'profile_image_y' => 48,
	'profile_image_type' => 'square',
	'profile_image_facebook_size' => 50,
	'default_message' => "ล่า ท้า ผี คุณจะเจอผีแบบไหน?",
	'max_ramdom_number' => 21, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#000'
);

$config['static_app_enable'] = FALSE;
$config['static_app_url'] = 'https://apps.socialhappen.com/static/';
$config['static_app_message'] = 'ดูภาพของคุณได้ที่';