<?php

/*
Plugin Name: Live charts statistic
Plugin URI: http://php-pastebin.com/
Description: View your paste in live
Version: 1.0
Author: Atmoner
Author URI: http://www.atmoner.com/
*/
 
//set plugin id as file name of plugin
$plugin_id = basename(__FILE__);

//some plugin data
$data['name'] = "Live charts statistic";
$data['author'] = "atmoner";
$data['url'] = "http://php-pastebin.com/";

//register plugin data
register_plugin($plugin_id, $data);

function create_new_page() {
     global $hook; 
	 $hook->add_page('live-charts','live-charts');  
}

function addnewMenu() {
        global $hook; 
	$hook->addMenu('live-charts', 'Live charts', 'live-charts.html','graph.png',10);
}

add_hook('new_page','create_new_page');
add_hook('action','addnewMenu');
