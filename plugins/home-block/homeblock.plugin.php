<?php

/*
Plugin Name: Home block
Plugin URI: http://atmoner.com/
Description: This is home page block
Version: 1.0
Author: Atmoner
Author URI: http://www.atmoner.com/
*/


//set plugin id as file name of plugin
$plugin_id = basename(__FILE__);

//some plugin data
$data['name'] = "First plugin";
$data['author'] = "eric wang";
$data['url'] = "http://www.ericbess.com/";

//register plugin data
register_plugin($plugin_id, $data);

//plugin function
function homeblock() {
	global $hook;
	// $hook->remove_block('pasteForm');
	$hook->add_block('test_home_block', 'Title home block', 'This is content of block',740,2);
	
}
 
//add hook, where to execute a function
add_hook('home_page','homeblock');
 
