<?php
/////////////////////////////////////////////////////////////////////////
//
//    This file is part of paste.php
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

// Parse code in geshi lib
include_once('libs/geshi/geshi.php');
$id = (isset($_GET["id"])?$_GET["id"]:"");
$p = $startUp->getPaste($id);

if (isset($_GET['download'])) { 
	
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=".$p['title']."");
header("Content-Description: ".$p['lang']." Generated Data");
echo html_entity_decode(htmlspecialchars_decode($p['paste']));
	exit;
}

if (!empty($p)) {
$geshi = new GeSHi(html_entity_decode(htmlspecialchars_decode($p['paste'])), $p['lang']);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
$geshi->set_header_type(GESHI_HEADER_DIV); 
$geshi->set_case_keywords(GESHI_CAPS_LOWER);
$geshi->set_footer_content('Parsed in <TIME> seconds');
 
$smarty->assign("result",$geshi->parse_code());
$smarty->assign("getPaste",$p);
// Update hits
$startUp->updateHits($id);
} 

$hook->set_title('title_paste','['.strtoupper($p['lang']).'] '.$p['title']); 
$hook->addcontentPaste('defaultpaste','',3);

if ($hook->hook_exist('paste_page'))  
		$hook->execute_hook('paste_page');

