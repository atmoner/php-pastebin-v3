<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of startup.php
//
//    Foobar is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    Foobar is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
//
/////////////////////////////////////////////////////////////////////////
//
// Website : http://php-pastebin.com/
// Contact : contact@php-pastebin.com
// 
/////////////////////////////////////////////////////////////////////////
//
// Dev     : Atmoner
// Website : http://atmoner.com
// Contact : contact@atmoner.com
// Twitter : @atmon3r 
// 
/////////////////////////////////////////////////////////////////////////

require($path.'/libs/Smarty.class.php');
require($path.'/libs/db.php');
require($path.'/libs/database/ez_sql_core.php');
require($path.'/libs/database/ez_sql_mysql.php');
require($path.'/libs/default.class.php'); 
require($path.'/libs/Hooks.class.php'); 

$db 	 = new ezSQL_mysql($user,$pass,$db,$host);
 
$smarty  = new Smarty;
$startUp = new pasteUsers; 
$hook    = new phphooks();
$conf 	 = $startUp->getConfigs();
$startUp->I18n();

// Smarty config
$smarty->addPluginsDir($path.'/libs/plugins/');
// $smarty->template_dir = $path.'/themes/v2/';
$smarty->template_dir = $path.'/themes/'.$conf['theme'].'/';
$smarty->compile_dir = $path.'/cache/compile_tpl/';
$smarty->cache_dir = $path.'/cache/';
$smarty->debugging = true;
$smarty->caching = $conf['timecache'];
$smarty->cache_lifetime = $conf['timecache'];
$smarty->config_dir = $path.'/libs/lang/';
 
$sql = "SELECT filename FROM plugins WHERE action = '".$db->escape(1)."'";
$items = $db->get_results($sql,ARRAY_A);
if($items){
	foreach ($items as $result_rows)   
		$plugins[] = $result_rows['filename'];
	} else
		$plugins ='';	

$hook->active_plugins = $plugins;
$hook->set_hooks(array(
			'action',
			'home_page',
			'new_page',
			'paste_page',
			'lastpaste_page',
			'registration_page',
			'login_page',
			'account_page',
			'admin_action',
			'new_admin_page',
			'admin_settings_page'
));
$hook->load_plugins();

function add_hook($tag, $function, $priority = 10) {
	global $hook;
	$hook->add_hook ( $tag, $function, $priority );
}

//same as above
function register_plugin($plugin_id, $data) {
	global $hook;
	$hook->register_plugin ( $plugin_id, $data );
}

$smarty->assign('hooks',$hook); // !! do not remove....
$smarty->assign("getPastes",$startUp->getPastes());


