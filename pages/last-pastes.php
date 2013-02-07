<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of last-pastes.php
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
 
if (!defined("IN_PASTE"))
      die("Access denied!");
      
 if (isset($_GET['searchToken'])) {
 	$startUp->redirect($conf['baseurl'].'/search/'.urlencode($_GET['searchPaste']));	 	
 } 
  
$smarty->assign("getPastespaginate",$startUp->getlastPastes());

$hook->set_title('laste_paste', $lang["titlelastpaste"]); 
$hook->add_block('defaultLastpates', '', '',740,10); 
$hook->addJs('dataTables','jquery.dataTables.js','themes/bootstrap/js/','2');
$hook->addJs('datatablesjs','datatables.js','themes/bootstrap/js/','3');

if ($hook->hook_exist('lastpaste_page'))  
		$hook->execute_hook('lastpaste_page');

