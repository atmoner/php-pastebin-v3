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

    require_once ("class.phpmailer.php");
    require_once ("class.smtp.php");

    class FormatMail {
        var $TemplateFile;
        var $Mailer;
        var $Message;
        var $ReplacedArr=array('/<img[^<>]*src="[^<>]*"[^<>]*>/i' => '/src="[^<>"]*"/i',
                                '/<img[^<>]*src=\'[^<>]*\'[^<>]*>/i' => '/src=\'[^<>\']*\'/i',
                                '/<script[^<>]*src="[^<>]*"[^<>]*>/i' => '/src="[^<>"]*"/i',
                                '/<script[^<>]*src=\'[^<>]*\'[^<>]*>/i' => '/src=\'[^<>\']*\'/i',
                                '/<link[^<>]*href="[^<>]*"[^<>]*>/i' => '/href="[^<>"]*"/i',
                                '/<link[^<>]*href=\'[^<>]*\'[^<>]*>/i' => '/href=\'[^<>\']*\'/i',
                                '/<[^<>]*background="[^<>]*"[^<>]*>/i' => '/background="[^<>"]*"/i',
                                '/<[^<>]*background=\'[^<>]*\'[^<>]*>/i' => '/background=\'[^<>\']*\'/i');
                                
        
        
        function FormatMail($TemplateFile) {
            $this->TemplateFile=$TemplateFile;
            $this->Mailer=new PHPMailer();
            $this->Mailer->IsHTML(true);
            $this->Message=$this->GetTemplate();
        }

    
        function Send() {
            $this->Mailer->Body=$this->Message;
            return $this->Mailer->Send();
        }
        
        function GetTemplate() {
            $tfile=fopen($this->TemplateFile,'r');
            $tcontent=fread($tfile,filesize($this->TemplateFile));
            fclose($tfile);
            while (!(strpos($tcontent, '{$')===false)) {
                $start=strpos($tcontent, '{$');
                $end=strpos($tcontent, '}', $start);
                $name=substr($tcontent,$start+2,$end-$start-2);
                $thalf1=substr($tcontent,0,$start);
                $thalf2=substr($tcontent,$end+1);
                if (isset($GLOBALS[$name]))
                  $tcontent=$thalf1.$GLOBALS[$name].$thalf2;
                else 
                  $tcontent=$thalf1.$thalf2;
            }
			$tcontent=preg_replace("/\r/","",$tcontent);
			$tcontent=preg_replace("/\n/","",$tcontent);
            $TagArr=array();
            $Idx=0;
            foreach ($this->ReplacedArr as $ReplacedTag => $ReplacedAttribute) {
                if (preg_match_all($ReplacedTag,$tcontent,$TagArr)) {
                    foreach ($TagArr as $ValArr) {
                        foreach ($ValArr as $Found) {
                            $AttributeArr=array();
                            if (preg_match($ReplacedAttribute,$Found,$AttributeArr)) {
                                $CID=md5("ATTACH_".$Idx++);
                                $Subject=substr($AttributeArr[0],strpos($AttributeArr[0],'=')+2,-1);
                                $this->Mailer->AddEmbeddedImage($Subject,$CID, "");
                                $Pattern="'$Subject'i";
                                $tcontent=preg_replace($Pattern,"cid:".$CID,$tcontent);
                            }
                        }
                    }
                }
            }    
            return $tcontent;
        }
    }  
?>
