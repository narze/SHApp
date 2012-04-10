<?php
//SocialHappen
$config['app_id'] = 1;
$config['app_secret_key'] = '11111111111111111111111111111111';
$config['api_url'] = 'https://socialhappen.com/api/';

//Mongodb
$config['mongodb_username'] = 'sohap'; 
$config['mongodb_password'] = 'figyfigy';
$config['mongodb_host'] = 'localhost';
$config['mongodb_port'] = 27017;
$config['mongodb_database'] = 'ghost';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '214547751984695';
$config['facebook_app_secret'] = 'd3a1fcba7100ef80a42ce754e831cf7a';
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