<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of main.php
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
      
if (isset($_POST['submit'])){
if (($_POST["txtCaptcha"] == $_SESSION["security_code"]) && (!empty($_POST["txtCaptcha"]) && !empty($_SESSION["security_code"])) ) {
        if (!empty($_POST['paste'])){

                if(empty($_POST['title'])) {
                $title = 'Untitled';
                } else {
                $title = $_POST['title'];
                }
        $startUp->addPaste($userId,$title,$_POST['syntax'],$_POST['paste'],$_POST['expiration'],$_POST['exposure']); 

        } else {
        $smarty->assign("errorPaste",'1');               
            }
        } else {
        $smarty->assign("errorCaptcha",'1');
        }
}  

$smarty->assign("getLangs",$startUp->getLangs()); 
 
$hook->set_title('home_title', $lang["titlehome"]); 
$hook->add_block('pasteForm', '', '',740,10); 
$hook->add_block('pasteOptions', '', '',740,11);  
$hook->add_block('pasteName', '', '',740,12);   
$hook->addJs('Captcha','ajax_captcha.js','libs/captcha/','5'); 

if ($hook->hook_exist('home_page'))  
	$hook->execute_hook('home_page');
 

