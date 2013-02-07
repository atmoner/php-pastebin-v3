<?php
/*
        __                        ________        
_____ _/  |_  _____   ____   ____ \_____  \______ 
\__  \    __\/     \ /  _ \ /    \  _(__  <_  __ \
 / __ \|  | |  Y Y  (  <_> )   |  \/       \  | \/
(____  /__| |__|_|  /\____/|___|  /______  /__|   
     \/           \/            \/       \/    
Website:  http://atmoner.com/
Contact:  contact@atmoner.com     
          
*/



 
if (!defined("IN_PASTE"))
      die("Access denied!"); 
 
$sub=(isset($_GET["sub"])?$_GET["sub"]:"");

if (!empty($sub)) {

	
} else {


	function getPlugins($where=NULL,$value=NULL){
		global $db;	
		$sql = "SELECT filename, action FROM plugins";
		if($where === 'action')
		$sql .= " WHERE action = '$value' ";
		if($where === 'filename')
		$sql .= " WHERE filename = '$value' ";
		$items = $db->get_results($sql);
 
		foreach ( $items as $obj ){
        		$array[$obj->filename]['filename'] = $obj->filename;
        		$array[$obj->filename]['action'] = $obj->action;
	        }
 
		return $array;
	}

$action = (isset($_GET["action"])?$_GET["action"]:"");

switch ($action) {

	case "deactivate" :
		$db->query("UPDATE plugins SET action='0' WHERE filename= '".$_GET ['filename']."'");
		$startUp->redirect('/admincp/plugins/');
		break;
	case "activate" :
		$count = count (getPlugins('filename',$_GET ['filename']));
			var_dump($count);
		if ($count < 1) {
			// do_sqlquery("INSERT INTO plugins (filename, action) VALUES ('".$_GET ['filename']."',1)",true);
			$db->query("INSERT INTO plugins (filename, action) VALUES ('".$_GET ['filename']."',1)");
		} else {
			// do_sqlquery("UPDATE plugins SET action='1' WHERE filename= '".$_GET ['filename']."'",true);
			$db->query("UPDATE plugins SET action='1' WHERE filename= '".$_GET ['filename']."'");
		}
		$startUp->redirect('/admincp/plugins/');		
		break;
}
 
 
$plugin_list = new phphooks();
$plugin_headers = $plugin_list->get_plugins_header();

$api=array();
$i=0;

 
  foreach ($plugin_headers as $tid=>$plugin_header) { 
			$action = false;		
	foreach ( getPlugins() as $result_row )  
		if ($plugin_header['filename'] == $result_row['filename'] && $result_row['action'] == 1)
			$action = true;
 
		if ($action)
			$api[$i]["active"]="class='active'";
			else
			$api[$i]["active"]="";
		// Name
		$api[$i]["Name"]=$plugin_header['Name'];
		$api[$i]["Version"]=$plugin_header['Version'];
		$api[$i]["Description"]=$plugin_header['Description'];
		$api[$i]["AuthorURI"]=$plugin_header['AuthorURI'];
		$api[$i]["Author"]=$plugin_header['Author'];
		if ($action) {
			$api[$i]["linkAdd"]='<i class="icon-minus-sign"></i> <a href="?action=deactivate&filename=' . $plugin_header['filename'] . '" title="DESACTIVATE">Desactivate</a>';
			$api[$i]["Use"]='Use it !';
			} else {
			$api[$i]["linkAdd"]='<i class="icon-ok-sign"></i> <a href="?action=activate&filename=' . $plugin_header['filename'] . '" title="ACTIVATE">Activate</a>';
			$api[$i]["Use"]='';			 
   		}
   
  $i++;   
  } 
$smarty->assign("api",$api);  
 }
 
$hook->addJs('dataTables','jquery.dataTables.js','themes/bootstrap/js/','2');
$hook->addJs('datatablesjs','datatables.js','themes/bootstrap/js/','3'); 
