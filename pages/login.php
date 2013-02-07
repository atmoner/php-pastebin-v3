<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of login.php
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

if ($startUp->isLogged())
        $startUp->redirect($conf['baseurl'].'/account.html');

if (isset($_POST['submit'])) {
	if (!empty($_POST['user']) && !empty($_POST['pass'])){	 
		if ($startUp->checkCredentials($_POST['user'], $_POST['pass'])){
					$startUp->setSession($_POST['user'],$_POST['pass'],'on');
					$startUp->redirect($conf['baseurl'].'/index.html');
				} else 
						$smarty->assign('errorLogin',true);         
	} else
			$smarty->assign('errorLogin',true); 
} else
		$smarty->assign('errorLogin',false);  	

$hook->set_title('title_login', 'Login'); 
$hook->add_content_login('defaultLogin', '',10); 

if ($hook->hook_exist('login_page'))  
		$hook->execute_hook('login_page');

