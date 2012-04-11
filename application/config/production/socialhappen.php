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
$config['mongodb_database'] = 'songkran';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '299915470082039';
$config['facebook_app_secret'] = '3c149c76eb9e94759cd65ddcb28fdb70';
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
$config['static_server_path'] = 'https://static.socialhappen.com/apps/songkran/';

$config['randomapp_settings'] = array(
	'app_title' => 'สงกรานต์นี้...คุณจะโดนอะไรสาด? by SocialHappen',
	'profile_image_size' => 95,
	'profile_image_x' => 33,
	'profile_image_y' => 185,
	'profile_image_type' => 'large',
	'profile_image_facebook_size' => 95,
	'default_message' => "สงกรานต์นี้...คุณจะโดนอะไรสาด?",
	'max_ramdom_number' => 25, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#fff'
);