<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function mongodb_load($collection = NULL) {
	if(!$collection){
		show_error('Collection not specified');
		exit();
	}

	$CI =& get_instance();

	$CI->config->load('socialhappen');
	$mongodb_username = $CI->config->item('mongodb_username');
	$mongodb_password = $CI->config->item('mongodb_password');
	$mongodb_host = $CI->config->item('mongodb_host');
	$mongodb_port = $CI->config->item('mongodb_port');
	$mongodb_database = $CI->config->item('mongodb_database');
	
	try{
		// connect to database
		$CI->connection = new Mongo("mongodb://".$mongodb_username.":"
		.$mongodb_password
		."@".$mongodb_host.":".$mongodb_port);
		
		// select database
		$CI->mongodb = $CI->connection->{$mongodb_database};

		// return collection
		return $CI->mongodb->{$collection};
		
	} catch (Exception $e){
		show_error('Cannot connect to database');
		return FALSE;
	}
}

