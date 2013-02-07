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
For: 	  http://php-pastebin.com
          
*/

if (isset($_GET['json'])) {
	// Set the JSON header
	header("Content-type: text/json");

	function getStatsPastes() {
 
		global $startUp, $db;
		$sql = "SELECT p.id FROM ".$startUp->prefix_db."pastes AS p ";
		$sql .= "WHERE p.date = '".time()."' ";
 
		$items = $db->get_results($sql);
 		//$db->debug();
		if ($items) {
			foreach ($items as $obj) {
		    		$array[$obj->id]['id'] = $obj->id;
			}
	        return count($array);			
		} else
			return 0; 	
	}


	// The x value is the current JavaScript time, which is the Unix time multiplied by 1000.
	$x = time() * 1000;
	// The y value is a random number
	//$y = rand(0, 100);
	$y = getStatsPastes();

	// Create a PHP array and echo it as JSON
	$ret = array($x, $y);
		echo json_encode($ret);
	exit;
}

