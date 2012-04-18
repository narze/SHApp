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
$config['mongodb_database'] = 'postit';

//Facebook : Fill these if you want to use facebook library
$config['facebook_app_id'] = '178273098960535';
$config['facebook_app_secret'] = '4fe404f3a509a667a676b91a310026c4';
$config['facebook_app_scope'] = 'user_about_me,user_photos,publish_stream,user_likes';
$config['facebook_force_like'] = TRUE;
//Set force like app_id if you want to use other app to force page liking
$config['facebook_force_like_app_id'] = '178273098960535';

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
$config['static_server_path'] = 'http://static.socialhappen.com/apps/postit/';

$config['randomapp_settings'] = array(
	'app_title' => 'แปะรักให้เธอรู้ (Post-it love)',
	'profile_image_size' => 50,
	'profile_image_x' => 18,
	'profile_image_y' => 316,
	'profile_image_type' => 'square',
	'profile_image_facebook_size' => 50,
	'default_message' => "แปะรักให้เธอรู้ (Post-it love) ",
	'max_ramdom_number' => 30, //Total files (if $config['static_server_enable'] = TRUE)
	'app_bgcolor' => '#fff',
	'text_1_x' => 83,
	'text_1_y' => 311,
	'text_2_x' => 83,
	'text_2_y' => 350,
);

$config['static_app_url'] = 'https://apps.socialhappen.com/static/';