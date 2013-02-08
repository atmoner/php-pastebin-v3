<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of registration.php
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
        if ($startUp->checkMail($_POST['mail'])) {
                if (!empty($_POST['user'])) { 
                	if (!empty($_POST['pass'])) { 
                		// Check if data is save else we make an error
                        if (!$startUp->addUser($_POST['user'],$_POST['mail'],$_POST['pass'],'true','true')) {
                        	$smarty->assign("UserOrMailexist",true);                        	
                        }                        
                    } else 
                    		$smarty->assign("errorpass",true);
                } else  
                        $smarty->assign("erroruser",true);                 
        } else
        		$smarty->assign("errorMail",true); 
} else {

	$smarty->assign("errorpass",false);
	$smarty->assign("erroruser",false);
	$smarty->assign("errorMail",false);

}
 
$hook->set_title('title_registration', 'Registration'); 
$hook->add_content_registration('defaultRegistration', '',10); 

if ($hook->hook_exist('registration_page'))  
		$hook->execute_hook('registration_page');

