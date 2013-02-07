<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of account.php
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

$startUp->isLoggedAcount(); 
 
if (isset($_GET['delPaste']) && is_numeric($_GET['delPaste'])) {
	$startUp->deleteMypaste($_GET['delPaste']);
} 
$getUserdata = $startUp->getUserdata();
$smarty->assign("getUserdata",$getUserdata);
$smarty->assign("countUserpastes",$startUp->getUserpastes($userId)); 
$smarty->assign("getMyPastes",$startUp->getMyPastes());
$smarty->assign("getGravatar",$startUp->get_gravatar($getUserdata->mail));

$hook->set_title('title_account', $lang["titleacount"]); 
$hook->add_block('infoUser', '', '','450-left',10); 
$hook->add_block('getPrenium', '', '','270-right',11); 
$hook->add_block('getMypaste', '', '','270-right',12);
$hook->addJs('dataTables','jquery.dataTables.js','themes/bootstrap/js/','2');
$hook->addJs('datatablesjs','datatables.js','themes/bootstrap/js/','3');
 
if ($hook->hook_exist('account_page'))  
		$hook->execute_hook('account_page');

