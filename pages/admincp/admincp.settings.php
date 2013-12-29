<?php
/////////////////////////////////////////////////////////////////////////
//
//    Ce programme est un logiciel libre : vous pouvez le redistribuer ou
//    le modifier selon les termes de la GNU General Public Licence tels
//    que publiés par la Free Software Foundation : à votre choix, soit la
//    version 3 de la licence, soit une version ultérieure quelle qu'elle
//    soit.
//
//    Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS
//    AUCUNE GARANTIE ; sans même la garantie implicite de QUALITÉ
//    MARCHANDE ou D'ADÉQUATION À UNE UTILISATION PARTICULIÈRE. Pour
//    plus de détails, reportez-vous à la GNU General Public License.
//
//    Vous devez avoir reçu une copie de la GNU General Public License
//    avec ce programme. Si ce n'est pas le cas, consultez
//    <http://www.gnu.org/licenses/>
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
// 
/////////////////////////////////////////////////////////////////////////

if (!defined("IN_PASTE"))
      die("Access denied!");
      
if (!empty($_POST["submit"])) {
 
$settings["baseurl"] = $_POST["baseurl"]; // Base url
$settings["mail"] = $_POST["mail"]; // Email
$settings["title"] = $_POST["title"]; // Title website
if ($_POST["timecache"] === '0'){
$settings["cache"] = false; // Cache 
$settings["timecache"] = $_POST["timecache"]; // Cache lifetime
} else {
$settings["cache"] = true; // Cache 
$settings["timecache"] = $_POST["timecache"]; // Cache lifetime
}
$settings["lang"] = $_POST["lang"]; // Lang default
$settings["theme"] = $_POST["theme"]; // Theme default
$settings["metad"] = $_POST["metad"]; // Meta description
$settings["metak"] = $_POST["metak"]; // Meta keyword
$settings["paypalmail"] = $_POST["paypalmail"]; // Meta keyword
$settings["amout"] = $_POST["amout"]; // Meta keyword
$settings["use_captcha"] = $_POST["use_captcha"]; // Use captcha


        foreach($_POST as $key=>$value) {
              if (is_bool($value))
               $value==true ? $value='true' : $value='false';
            $values[]="(".$startUp->sqlesc($key).",".$startUp->sqlesc($value).")";
        }
        $db->query("DELETE FROM ".$startUp->prefix_db."settings");
        $db->query("INSERT INTO ".$startUp->prefix_db."settings (`key`,`value`) VALUES ".implode(",",$values).";");
		// $db->debug();
		
        $startUp->editmaxPaste($_POST["maxline_1"],1); 
        $startUp->editmaxPaste($_POST["maxline_2"],2);
        $startUp->editmaxPaste($_POST["maxline_3"],3);   
        $startUp->editmaxPaste($_POST["maxline_4"],4);      
}
$smarty->assign("getConfigs",$startUp->getConfigs()); 
$smarty->assign("getThemes",$startUp->getThemes('themes')); 
$smarty->assign("getStatuts",$startUp->getStatuts()); 

if ($hook->hook_exist('admin_settings_page'))  
		$hook->execute_hook('admin_settings_page');

