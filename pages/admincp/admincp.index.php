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

if (!isset($_COOKIE['tokenAdmin']) 
	|| empty($_COOKIE['tokenAdmin']) 
	|| !isset($_GET['tokenAdmin']) 
	|| empty($_GET['tokenAdmin']) 
	|| $_GET['tokenAdmin'] != $_COOKIE['tokenAdmin']
) 
        $startUp->redirect('/');

if ($startUp->checkAdmin() === false)
        $startUp->redirect('/');
    
$do = (isset($_GET["act"])?$_GET["act"]:"");

if ($hook->hook_exist('admin_action'))  
		$hook->execute_hook('admin_action');

	
switch ($do){

    case 'pastes':
    include("admincp.pastes.php");
    $smarty->display('admincp.pastes.html');
    break;

    case 'users':
    include("admincp.users.php");
    $smarty->display('admincp.users.html');
    break;

    case 'plugins':
    include("admincp.plugins.php");
    $smarty->display('admincp.plugins.html');
    break;

    case 'settings':
    case '':
    // default:
    include("admincp.settings.php");
    $smarty->display('admincp.settings.html');
    break;

    default:
	if ($hook->hook_exist('new_admin_page'))  
			$hook->execute_hook('new_admin_page'); 
    break;
}

 
