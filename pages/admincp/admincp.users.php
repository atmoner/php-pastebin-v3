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
     
if(!empty($_GET['del']) && is_numeric($_GET['del'])) {
	if ($_GET['del'] != '1') {
		$startUp->delUser($_GET['del']);		
	} else
		$smarty->assign('forbidden',true);	
} else
	$smarty->assign('forbidden',false);	

if(isset($_POST['submitadd'])) {
        if (!empty($_POST['name']) && !empty($_POST['mail']) && !empty($_POST['pass'])){    
        	if ($_POST['sendmail']) {
        		$sendMail = $_POST['sendmail'];
        	} else
        		 $sendMail = 'NULL';          
                $startUp->addUser($_POST['name'],$_POST['mail'],$_POST['pass'],'NULL',$sendMail);
        }
}

if(!empty($_GET['edit'])) {
        if (!empty($_POST['editUser'])){
                $startUp->adminEditUser($_GET['edit'],$_POST['pass'],$_POST['name'],$_POST['mail'],$_POST['level'],$_POST['location'],$_POST['website'],$_POST['signature']);
        }
        $smarty->assign("getUserdata",$startUp->getUserdata($_GET['edit']));
        $smarty->assign("getStatuts",$startUp->getStatuts());
} else 
        $smarty->assign("getUsers",$startUp->getUsers());
 
$hook->addJs('dataTables','jquery.dataTables.js','themes/bootstrap/js/','2');
$hook->addJs('datatablesjs','datatables.js','themes/bootstrap/js/','3'); 
