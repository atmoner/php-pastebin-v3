<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of index.php
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
error_reporting(E_ERROR | E_PARSE);

define("IN_PASTE",true);

// This part can be removed after install
if (file_exists("install.php")) {
	if (filesize("libs/db.php") === 0) {
		header("location:install.php");		
	} 
} // This part can be removed after install

$path = dirname(__FILE__); 

require($path.'/libs/startup.php');
require($path.'/libs/lang/lang_'.$_SESSION['strLangue'].'.php');
 
 
$userId = $startUp->isLogged();
if ($userId && isset($_COOKIE['tokenAdmin'])) 
	$isAdmin = $startUp->checkAdmin($_COOKIE['tokenAdmin']);
else
	$isAdmin = false;
 
$smarty->assign("Name",$conf['title']);
$smarty->assign("getConfigs",$conf);
$smarty->assign("getUserdata",$startUp->getUserdata());
$smarty->assign("footer",$startUp->addFooter()); 
$smarty->assign("userId",$userId); 
$smarty->assign("userName",$startUp->session_username);
$smarty->assign("Admin",$isAdmin);
 
// Stats
$today = time() - (1 * 24 * 60 * 60);
$smarty->assign("getTotalpaste",$startUp->getTotalpaste());
$smarty->assign("getTotalpastetoday",$startUp->getTotalpaste($today));
$smarty->assign("getTotalusers",$startUp->getTotalusers());
$smarty->assign("lang",$lang);

$hook->add_side_block('defaultSearchbox','','',1);
$hook->add_side_block('defaultSidebar','','', 3); 


// Javascript hook
$hook->addJs('Jquery','http://code.jquery.com/jquery-latest.min.js','','1');
// Css hook
$hook->addCss('Style','style.css','themes/'.$conf['theme'].'/style/','1');
// Main menu hook
$hook->addMenu('addPaste',$lang["newPaste"], 'add.html', 'newpost.png', '3');
$hook->addMenu('viewAllpastes',$lang["viewPastes"], 'last-pastes.html', 'dashboard.png', '4');


// Lang menu hook
$hook->addMenuLang('en',$lang["english"], '?strLangue=en', 'en.png', '3');
$hook->addMenuLang('fr',$lang["french"], '?strLangue=fr', 'fr.png', '4');
$hook->addMenuLang('ru',$lang["russe"], '?strLangue=ru', 'ru.png', '5');
 
// Usermenu hook
if (!$userId) {
	$hook->addUserMenu('gestcp','','','', '4');
} else {
	if (isset($_COOKIE['tokenAdmin'])) {
		if ($isAdmin) {
			$hook->addUserMenu('admincp','','','', '4'); 
		}		
	}
	
	$hook->addUserMenu('usercp','','','', '8');
}

 

if ($hook->hook_exist('action'))  
		$hook->execute_hook('action');

 
switch (isset($_GET["page"])?$_GET["page"]:""){

        case 'admincp': 
                # admin panel
                include 'pages/admincp/admincp.index.php';
        break; 

        case 'paste': 
                # paste
                include 'pages/paste.php';
                $smarty->display('paste.html');
        break;    
 
        case 'last-pastes': 
                # laste paste
                include 'pages/last-pastes.php';
                $smarty->display('last-pastes.html');
        break; 
 
        case 'zone-login': 
                # login
                include 'pages/login.php';
                $smarty->display('login.html');
        break; 

        case 'registration': 
                # registration
                include 'pages/registration.php';
                $smarty->display('registration.html');
        break; 

        case 'account': 
                # account
                include 'pages/account.php';
                $smarty->display('account.html');
        break; 

        case 'edit-account': 
                # account
                include 'pages/edit.account.php';
                $smarty->display('edit.account.html');
        break;         

        case 'premium': 
                # account
                include 'pages/premium.php';
                $smarty->display('premium.html');
        break; 

        case 'user': 
                # account
                include 'pages/user.php';
                $smarty->display('user.html');
        break; 

        case 'logout': 
                # logout
                include 'pages/logout.php';
        break;

        case 'error': 
                # error
                $smarty->display('error.html');
        break; 
 
        case '':  
        case 'add':
        case 'index':  
                # index
                include 'pages/main.php';
                $smarty->display('index.html');
        break;

        default:
		if ($hook->hook_exist('new_page'))  
				$hook->execute_hook('new_page'); 
		else 
			$startUp->redirect($conf['baseurl'].'/');
        break;
}  


