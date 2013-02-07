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
// Website : http://atmoner.com/
// Contact : contact@atmoner.com
// 
/////////////////////////////////////////////////////////////////////////

if (!defined("IN_PASTE"))
      die("Access denied!");

if(!empty($_GET['del'])) {
    $startUp->delPaste($_GET['del']);
}
    
if(!empty($_GET['edit'])) {
        if (!empty($_POST['editPaste'])){
                $startUp->editPaste($_GET['edit'],$_POST['title'],$_POST['date'],$_POST['lang'],$_POST['paste']);
        }
        $paste = $startUp->getPaste($_GET['edit']);
        $smarty->assign("getPaste",$paste);
        $smarty->assign("getLangs",$startUp->getLangs());
        $smarty->assign("paste",html_entity_decode($paste['paste']));
} else

        $smarty->assign("getPastes",$startUp->getPastes(1000));
 
$hook->addJs('dataTables','jquery.dataTables.js','themes/bootstrap/js/','2');
$hook->addJs('datatablesjs','datatables.js','themes/bootstrap/js/','3'); 
$hook->addJs('datepicker','bootstrap-datepicker.js','themes/bootstrap/js/','4');

