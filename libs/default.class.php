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
// Twitter : @atmon3r
// 
/////////////////////////////////////////////////////////////////////////
 
class StartUp {

	private $prefix_db = ''; // Prefix db (for security)
	private $charset = 'utf-8'; // Chraset
	private $get = '';
	public $version = '3'; // Version of php-pastebin
	public $rev = '0'; // Revision of php-pastebin
	public $langAutorises; // Languages list
 		
	###
	function __construct() {
		session_start();
		header("Content-type:text/html; charset=".$this->charset."");
		$this->checkInstallFile();		
		$this->cleandata();
	}
	###
	function checkInstallFile() {
		global $smarty,$path;
		if (file_exists($path."/install.php")) {
			if (filesize($path."/libs/db.php") != 0) {
				$smarty->assign('errorInstallFile',true);
			} 
		} else
			$smarty->assign('errorInstallFile',false);	
	}
	###
	function cGet($get) {
	// var_dump($get);
		$this->get = $get;
		if(is_numeric($this->get)) {
			$get=(int)$this->get;
		} else {
			$get=htmlspecialchars($this->get);
		}
		// return $get;
	}
	###
	function getConfigs(){
		global $db;
		$sql = "SELECT `key`,`value` FROM ".$this->prefix_db."settings";
		$array = $db->get_results($sql,ARRAY_A);
		// $db->debug();
		foreach ($array as $key => $value) {
			$array[$value['key']] = $this->Fuckxss($value['value']);
		}		 
    	return $array;
 	}	
	###
	function cleandata() {
		  $now = time();
 		  foreach ($this->getTimestamp() as $key => $value) {		  	     	
				if ($value['last_time'] + $value['time'] < $now) { 
				  $this->updateTimestamp($now,$key,$value['last_time']); 
			    	  $this->do_sanity($key);
		    	  	}
			}
	}
	###
	function getTimestamp() {
		global $db;
		$sql = "SELECT id, time, last_time FROM ".$this->prefix_db."tasks";		
    	$items = $db->get_results($sql);
 
		foreach ($items as $item) {
				$array[$item->id]['id'] = $item->id;
             	$array[$item->id]['time'] = $item->time;
            	$array[$item->id]['last_time'] = $item->last_time;
 
		}   		
 
		return $array;
 
	}
	###
	function updateTimestamp($now,$id,$lt) {
		global $db;
			$db->query("UPDATE ".$this->prefix_db."tasks SET last_time=$now WHERE id='$id' AND last_time = '$lt'");	
		return true;
	}
	###
	function do_sanity($key){
		global $db;
		$db->query("DELETE FROM pastes WHERE expire = '$key'");		
	}
	###
	function redirect($location='index.php'){
		header("location:".$location);
	}		
	###
	function getLangs() {
		global $db;
    		$items = $db->get_results("SELECT id, code, description FROM ".$this->prefix_db."lang");
 
		foreach ( $items as $obj ){
				$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['code'] = $obj->code;
            	$array[$obj->id]['description'] = $obj->description;
	        } 
		return $array;
 
	}
	### 	
	function addPaste($userid,$title,$lang,$paste,$expire,$exposure) {
		global $db,$conf;
		$date = time();
		$query = "INSERT INTO ".$this->prefix_db."pastes (id,userid,uniqueid,title,lang,paste,date,expire,exposure) 
	          VALUES (
			  'NULL',
			  '".$db->escape($userid)."',
			  '".$this->makeId()."',
			  '".$db->escape($title)."',
			  '".$db->escape($lang)."',
			  '".htmlspecialchars(mysql_escape_string($paste))."',
			  '$date',
			  '".$db->escape($expire)."',
			  '".$db->escape($exposure)."'
			  )";
    		$db->query($query);
    		$id = $db->insert_id;
 
    		$paste = $db->get_row("SELECT uniqueid FROM ".$this->prefix_db."pastes WHERE id='$id'");
     		$this->redirect($conf['baseurl'].'/'.$paste->uniqueid);
	}
	###
	function editPaste($uniqueid,$title,$date,$lang,$paste){
		global $db;
		$date = $this->makeTimestamp($date);
		$query = "UPDATE ".$this->prefix_db."pastes SET 
						title='".mysql_real_escape_string($title)."',
						lang='$lang',
						date='$date',
						paste='".htmlspecialchars(mysql_escape_string($paste))."'
			 WHERE uniqueid = '$uniqueid'";
		$db->query($query);
 
    	return true;	
	}
    function delPaste($key){
    	global $db,$conf;
    		$db->query("DELETE FROM pastes WHERE uniqueid = '".$db->escape($key)."'");
            $this->redirect($conf['baseurl'].'/admincp/pastes/?tokenAdmin='.$_COOKIE['tokenAdmin']);           
    }	
	###
	function getStatuts(){	
		global $db;
		$sql = "SELECT id,level,maxlines FROM ".$this->prefix_db."statuts "; 
 		$items = $db->get_results($sql);	
 
		foreach ($items as $obj) { 
        		$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['level'] = $obj->level;
            	$array[$obj->id]['maxlines'] = $obj->maxlines;
	        } 
		return $array;
	}
	###
	function editmaxPaste($value,$statut){
		global $db;
		$db->query("UPDATE ".$this->prefix_db."statuts SET maxlines='".$db->escape($value)."' WHERE id = '".$db->escape($statut)."' ");
    	return true;		
	}	
    ###
    function delUser($key){
   		 global $db,$conf;
    		$db->query("DELETE FROM ".$this->prefix_db."users WHERE id = '".$db->escape($key)."'");
    		$db->query("DELETE FROM ".$this->prefix_db."pastes WHERE userid = '".$db->escape($key)."'");        
        $this->redirect($conf['baseurl'].'/admincp/users/?tokenAdmin='.$_COOKIE['tokenAdmin']);             
        }
	###
	function getPastes($limit=10){	
		global $db,$smarty;
		$sql = "SELECT p.id,p.uniqueid,p.title,p.lang,p.paste,p.date,p.expire,p.exposure,p.hits,users.name FROM ".$this->prefix_db."pastes AS p ";
		$sql .= "INNER JOIN ".$this->prefix_db."users ON p.userid=users.id ";		
		$sql .= "WHERE exposure = 'public' ";
		$sql .= "ORDER BY p.date DESC LIMIT 0,$limit ";
 
		$items = $db->get_results($sql);
	 	if ($items) { 
		foreach ($items as $obj) {
        		$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['uniqueid'] = $obj->uniqueid;
            	$array[$obj->id]['title'] = $this->Fuckxss($obj->title);
				$array[$obj->id]['lang'] = $obj->lang;  
				$array[$obj->id]['paste'] = $obj->paste; 
           		$array[$obj->id]['date'] = $this->ago($obj->date);
           		$array[$obj->id]['expire'] = $obj->expire; 
           		$array[$obj->id]['exposure'] = $obj->exposure; 
           		$array[$obj->id]['hits'] = $obj->hits; 
           		$array[$obj->id]['name'] = $obj->name; 
	        }
			$smarty->assign('getPastes',$array);	
			return $array;
	 	} else
	 		return false;	   		
	}
	function getMyPastes() {
		global $db;
		$sql = "SELECT p.id,p.userid,p.uniqueid,p.title,p.lang,p.paste,p.date,p.expire,p.exposure,p.hits FROM ".$this->prefix_db."pastes AS p ";
		$sql .= "WHERE p.userid = '".$this->uid."' ";
		$sql .= "ORDER BY p.date DESC";
 
		$items = $db->get_results($sql);
	 	if ($items) {
		foreach ($items as $obj) {
        		$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['uniqueid'] = $obj->uniqueid;
            	$array[$obj->id]['title'] = $this->Fuckxss($obj->title);
				$array[$obj->id]['lang'] = $obj->lang;  
				$array[$obj->id]['paste'] = $obj->paste; 
           		$array[$obj->id]['date'] = $obj->date;
           		$array[$obj->id]['expire'] = $obj->expire; 
           		$array[$obj->id]['exposure'] = $obj->exposure; 
           		$array[$obj->id]['hits'] = $obj->hits; 
	        }
			return $array;	 		
	 	} else
	 		return false;
		
	}
	function getPasteByUser($name) {
		global $db;
		$user = $db->get_row("SELECT id FROM users WHERE name = '$name'"); 
		
		$sql = "SELECT p.id,p.userid,p.uniqueid,p.title,p.lang,p.paste,p.date,p.expire,p.exposure,p.hits FROM ".$this->prefix_db."pastes AS p ";
		$sql .= "WHERE p.userid = '".$user->id."' AND p.userid != '0' ";
		$sql .= "ORDER BY p.date DESC";
 
		$items = $db->get_results($sql);
 		//$db->debug();
		foreach ($items as $obj) {
        		$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['uniqueid'] = $obj->uniqueid;
            	$array[$obj->id]['title'] = $this->Fuckxss($obj->title);
				$array[$obj->id]['lang'] = $obj->lang;  
				$array[$obj->id]['paste'] = $obj->paste; 
           		$array[$obj->id]['date'] = $obj->date;
           		$array[$obj->id]['expire'] = $obj->expire; 
           		$array[$obj->id]['exposure'] = $obj->exposure; 
           		$array[$obj->id]['hits'] = $obj->hits; 
	        }

		return $array;		
	}
	function deleteMypaste($id) {
		global $db;
			$db->query("DELETE FROM ".$this->prefix_db."pastes WHERE id='".$db->escape($id)."'");
		return true;
	}
	###
	function getlastPastes(){
		global $db;		
		$sql = "SELECT p.id,p.uniqueid,p.title,p.lang,p.paste,p.date,p.expire,p.exposure,p.hits,users.name FROM ".$this->prefix_db."pastes AS p ";
		$sql .= "INNER JOIN ".$this->prefix_db."users ON p.userid=users.id ";
		$sql .= "WHERE exposure = 'public' ";
		$sql .= "ORDER BY p.date DESC LIMIT 0,200 ";
 
 		$items = $db->get_results($sql);
		foreach ($items as $obj) {
        		$array[$obj->id]['id'] = $obj->id;
        		$array[$obj->id]['uniqueid'] = $obj->uniqueid;
            	$array[$obj->id]['title'] = $this->Fuckxss($obj->title);
				$array[$obj->id]['lang'] = $obj->lang;  
				$array[$obj->id]['paste'] = $obj->paste; 
           		$array[$obj->id]['date'] = $obj->date;
           		$array[$obj->id]['expire'] = $obj->expire; 
           		$array[$obj->id]['exposure'] = $obj->exposure; 
           		$array[$obj->id]['hits'] = $obj->hits;
           		$array[$obj->id]['name'] = $obj->name;
	        }  
		return $array; 
	}
	### 
	function getTotalpaste($where=FALSE){
		global $db;
		$sql = "SELECT COUNT(id) AS id FROM ".$this->prefix_db."pastes ";
		if($where!==FALSE){
			$sql .= "WHERE date > $where";
		} 
		$count = $db->get_row($sql);
		
	return $count->id;
	}
	### 
	function getTotalusers(){
		global $db;
		$sql = "SELECT COUNT(id) AS id FROM ".$this->prefix_db."users "; 
		$count = $db->get_row($sql);
		
	return $count->id;
	}
	###
	function getPaste($id){ 
		global $db;
		$sql = "SELECT p.id,p.title,p.lang,p.paste,p.date,p.expire,p.exposure,p.hits FROM ".$this->prefix_db."pastes AS p ";
		$sql .= "WHERE p.uniqueid = '".$db->escape($id)."' ";
 
 		$items = $db->get_results($sql);
		foreach ($items as $obj) {
         		$array['id'] = $obj->id;
            	$array['title'] = $this->Fuckxss($obj->title);
				$array['lang'] = $obj->lang;  
				$array['paste'] = stripslashes($this->Fuckxss($obj->paste)); 
           		$array['date'] = $obj->date;
           		$array['expire'] = $obj->expire; 
           		$array['exposure'] = $obj->exposure; 
           		$array['hits'] = $obj->hits;
	        } 		 
		return $array;
	}
	###
	function updateHits($id) {
		global $db;
			$sql = "UPDATE ".$this->prefix_db."pastes SET hits=(hits + 1) WHERE uniqueid='".$db->escape($id)."'";		
	    		$db->query($sql);
		return true;
	}
	###
	function makeId($car=8) {			      
		$string = "";
		$chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnpqrstuvwxy1234567890";
		srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
		}
	return $string;
	}
	###
	function I18n(){
 
 		global $conf;
		if(isset($_GET['strLangue'])) {
	
			$chaine = $_SERVER['REQUEST_URI'];
			$nbr = 13;
			$url = substr($chaine, 0, -$nbr);
	
			$this->langAutorises = array('fr','en','ru');
			if (in_array($_GET['strLangue'],$this->langAutorises))
			$_SESSION['strLangue']=$_GET['strLangue'];
			$this->redirect($url);
		} else {
			if (empty($_SESSION['strLangue'])) {
			$_SESSION['strLangue'] = $conf['lang'];		
			}  
		}
	}
	### 	
	function addUser($name,$mail,$pass,$redirect='NULL',$sendMail='NULL',$isadmin="NULL") {
		global $db,$conf;

		$db->query("SELECT id FROM users WHERE id != '".$db->escape(0)."' AND mail='".$db->escape($mail)."' OR name='".$db->escape($name)."' ");
		$user_details = $db->get_row();
		
		if (!$user_details) {
		
		$hash = $this->makeId(15);
		if ($isadmin!="NULL")
			$level = "4";
			else
			$level = "2";
			
		$query = "INSERT INTO ".$this->prefix_db."users (id,name,pass,mail,level,token) 
	          VALUES (
			  'NULL',
			  '".$db->escape($name)."',
			  '".$this->obscure($pass)."',
			  '".$db->escape($mail)."',
			  '$level',
			  '".$this->generateToken()."'
			  )";
    		$db->query($query);
    		if ($sendMail!='NULL')  
    		$this->sendMail($name,$mail,$pass,$hash);
     		if ($redirect!='NULL') 
     			$this->redirect($conf['baseurl'].'/zone-login.html');    				
			
		} else
			return false; 
	}
	###
	function EditUserInfo($pass='',$mail,$seemail,$location,$website,$sign) {
		global $db;
		if (empty($pass)) {
			$pass = '';
		} else {
			$pass = "pass='".$this->obscure($pass)."',";
		}
		$query = "UPDATE ".$this->prefix_db."users SET $pass 
					mail='".$db->escape($mail)."',
					seemail='".$db->escape($seemail)."',
					location='".$db->escape($location)."',
					website='".$db->escape($website)."',
					signature ='".$db->escape($sign)."' 
					WHERE id = '".$this->uid."'";
    		$db->query($query);
    		$this->redirect($conf['baseurl'].'/account.html');  
	return true;
	} 
	###
	function checkMail($mail){
		# code...
		$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
		$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
				               
		$regex = '/^'.$atom.'+'.'(\.'.$atom.'+)*'.'@'.'('.$domain.'{1,63}\.)+'.$domain.'{2,63}$/i';          

		// test de l'adresse e-mail
		if (preg_match($regex, $mail)) {
		    return true;
		} else {
		    return false;
		}
	}
	###
	function sendMail($user,$mail,$pass,$hash) {
    		require_once('mailling/classes/class.formatmail.php');
    		    $GLOBALS['NAME'] = $user;
		    $GLOBALS['USERNAME'] = $user;
		    $GLOBALS['PASSWORD'] = $pass;
		    //Importatnt: fill up all GLOBALS field before call this constructor
		    $FM = new FormatMail(dirname(__FILE__).'/mailling/templates/registration-'.$_SESSION['strLangue'].'.htm');
		    $FM->Mailer->FromName = $user;
		    // $FM->Mailer->From = $this->admin_mail;
		    $FM->Mailer->Subject = 'Registration';
		    $FM->Mailer->AddAddress($mail,$user);
		    //And now, send the mail...
		    if ($FM->Send()) 
	return true;	
	}	
	### 
	function getThemes($dir,$mode='folders'){	 
	 $items = array();	 
	 if( !preg_match( "/^.*\/$/", $dir ) ) $dir .= '/';	 
         $handle = opendir( $dir );
	 if( $handle != false ){
	  while($item=readdir($handle))
	  {
	   if($item != '.' && $item != '..')
	   {
	    // selon le mode choisi
	    switch($mode)
	    {
	     case 'folders' :
	      if(is_dir($dir.$item))
	       $items[] = $item;
	      break;
	     
	     case 'files' :
	      if(!is_dir($dir.$item))
	       $items[] = $item;
	      break;
	     
	     case  'all' :
	      $items[] = $item;	    
	    }
	   }
	  }	  
	  closedir($handle);	   
	  return $items;	  
	 }
	 else return false;	  
	}
	###
	function makeTimestamp($date){
		$date = str_replace(array(' ', ':'), '-', $date);
		$c    = explode('-', $date);
		$c    = array_pad($c, 6, 0);
		array_walk($c, 'intval'); 
	return mktime($c[3], $c[4], $c[5], $c[1], $c[2], $c[0]);
	}
	###	
	function addFooter(){
		# code...
		$c = "Php-Pastebin V.".$this->version." Rev: ".$this->rev." By: <a href=\"http://atmoner.com\" target=\"_blank\">Atmoner</a>";
	return $c;
	}
	###	
	function Fuckxss($var) {
		return htmlspecialchars(strip_tags($var), ENT_NOQUOTES);
	}	
	###
	function ago($time) {
	
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	   $now = time();

		   $difference     = $now - $time;
		   $tense         = "ago";

		   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			   $difference /= $lengths[$j];
		   }
		   $difference = round($difference);
		   if($difference != 1) {
			   $periods[$j].= "s";
		   }
	   return "$difference $periods[$j] ago";
	}
}
class pasteUsers extends startUp {
	
	var $session_name = 'sessionUser';	
	var $hash = '0900124461779baebd4e030b813535ac';	
	var $session_username = "";
	var $session_password = "";
	var $uid = "";

	###
	function isLogged(){
		if($this->checkUser()){
			return $this->uid;
		} else {
			return false;
		}
	}
	###
	function isLoggedAcount(){
		if($this->checkUser()){
			return $this->uid;
		} else {
			$this->redirect();
			$this->killAll();

		}
	}
	###
	function checkUser(){
 
		global $db;
		if($this->checkCookie()){
			$uid = $this->uid;
			$username = $this->session_username;
			$password = $this->session_password;
			$query = "SELECT id FROM ".$this->prefix_db."users WHERE name = '$username' AND pass = '$password' AND id = '$uid' AND level > '0' LIMIT 1;";
			$user = $db->get_row($query); 
 			// $db->debug();
				 if ($user->id)  
					return true;					
				 else  
					return false;					 	
			} else 
				return false;		
	}
	###
	function checkAdmin($token){
		global $db;
		if($this->checkCookie()){
			$uid = $this->uid;
			$query = "SELECT id FROM ".$this->prefix_db."users WHERE id = '".$db->escape($uid)."' AND token = '".$db->escape($token)."' AND level = '4' LIMIT 1;";
			$user = $db->get_row($query); 
 
				 if ($user->id)  
					return true;					
				 else  
					return false;					 	
			} else 
				return false;			 
	}
	###
	function checkCredentials($username, $password){	
		global $db; 
		$password = $this->obscure($password);				
		$query = "SELECT id FROM ".$this->prefix_db."users WHERE name = '".$db->escape($username)."' AND pass = '".$db->escape($password)."' AND level > '0' LIMIT 1;";
    		$user = $db->get_row($query); 
		
		if ($user->id) {
			return true;
		} else {
			return false;
		}
	}
	###
	function getUserdata($uid){
		global $db;
 
		 
				$query = "SELECT u.id, u.name, u.mail, u.level, u.signature, u.seemail, u.location, u.website, statuts.id, statuts.level, statuts.maxlines FROM ".$this->prefix_db."users AS u";
				$query .= " INNER JOIN statuts ON u.level=statuts.id";
				$query .= " WHERE u.name = '".$db->escape($uid)."' LIMIT 1";
    		$user = $db->get_row($query,OBJECT); // get result in objet (OBJECT)
 
 			if ($user) {
			$user->id = $this->Fuckxss($user->id); 
 			$user->name = $this->Fuckxss($user->name);
 			$user->mail = $this->Fuckxss($user->mail);
 			$user->level = $this->Fuckxss($user->level);
 			$user->signature = $this->Fuckxss($user->signature);	
 			$user->location = $this->Fuckxss($user->location);	
 			$user->website = $this->Fuckxss($user->website);
 
			return $user;
 				
 			} else
 			return false;

	}
	###
	function getMydata($uid){
		global $db;
 
		 
				$query = "SELECT u.id, u.name, u.mail, u.level, u.signature, u.seemail, u.location, u.website, statuts.id, statuts.level, statuts.maxlines FROM ".$this->prefix_db."users AS u";
				$query .= " INNER JOIN statuts ON u.level=statuts.id";
				$query .= " WHERE u.id = '".$db->escape($this->uid)."' LIMIT 1";
    		$user = $db->get_row($query,OBJECT); // get result in objet (OBJECT)
 
 			if ($user) {
			$user->id = $this->Fuckxss($user->id); 
 			$user->name = $this->Fuckxss($user->name);
 			$user->mail = $this->Fuckxss($user->mail);
 			$user->level = $this->Fuckxss($user->level);
 			$user->signature = $this->Fuckxss($user->signature);	
 			$user->location = $this->Fuckxss($user->location);	
 			$user->website = $this->Fuckxss($user->website);
 
			return $user;
 				
 			} else
 			return false;

	}
	###
	function getUserpastes($where=FALSE){
		global $db;
		$sql = "SELECT COUNT(id) AS id FROM ".$this->prefix_db."pastes ";
		if($where){
			$sql .= "WHERE userid = $where";
		} 
		$count = $db->get_row($sql); 
		 
	return $count->id;
	}
	###
	function getUsers(){	 
		global $db;	
		$query = "SELECT u.id, u.name, u.mail, u.level, statuts.level FROM ".$this->prefix_db."users AS u";
		$query .= " INNER JOIN statuts ON u.level=statuts.id";
		$items = $db->get_results($query);
		foreach ( $items as $obj ) {
        			$array[$obj->id]['id'] = $obj->id;
            		$array[$obj->id]['name'] = $this->Fuckxss($obj->name);
            		$array[$obj->id]['mail'] = $this->Fuckxss($obj->mail);
            		$array[$obj->id]['level'] = $this->Fuckxss($obj->level);            		
	        }
 		return $array;
	}		 
	###
	function adminEditUser($id,$pass,$name,$mail,$level,$location,$website,$signature) {
		global $db;
		if (empty($pass)) {
			$pass = '';
		} else {
			$pass = "pass='".$this->obscure($pass)."',";
		}
		$query = "UPDATE ".$this->prefix_db."users SET $pass name='".$db->escape($name)."',mail='".$db->escape($mail)."',level='".$db->escape($level)."',location='".$db->escape($location)."',website='".$db->escape($website)."',signature='".$db->escape($signature)."' WHERE id = '$id'";
    		$db->query($query);
	return true;
	} 
	###
	function checkCookie(){
		global $db;
		if (isset($_COOKIE["$this->session_name"]) || isset($_SESSION["$this->session_name"])) {
 
			$cookie = explode(",",$_COOKIE["$this->session_name"]);
 
			$this->session_username = $db->escape($cookie['0']);
			$this->session_password = $db->escape($cookie['1']);
			$this->uid = $db->escape($cookie['2']);
			return true;
		} else {
			return false;
		}
	}
	###
	function userExists($username,$password) {
		if (($this->username==$username)&&($this->password==$password)) {
        	return true;
    	} else {
		return false;
		}
	}
	###
	function setSession($username,$password,$cookie){
		global $db;
			$query = "SELECT id, level, token FROM ".$this->prefix_db."users WHERE 
							name = '".$db->escape($username)."' AND pass = '".$this->obscure($password)."' AND level > '0' LIMIT 1;";
		$row = $db->get_row($query,ARRAY_A); // get result in array (ARRAY_A)	
				
		$values = array($username,$this->obscure($password),$row['id']);
		$session = implode(",",$values);
		if($cookie=='on'){
			setcookie("$this->session_name", $session, time()+60*60*24*100,'/');
		} else {
			$_SESSION["$this->session_name"] = $session;
		}
		// Gestion du token
		if ($row['level'] === '4'){		
			setcookie("tokenAdmin", $row['token'], time()+60*60*24*100,'/');
		}
		setcookie("token", $row['token'], time()+60*60*24*100,'/');	 		
	}
	function generateToken(){
		return uniqid(rand(), true);
	}
	###
	function sqlesc($x) {
	  return '\''.mysql_real_escape_string($x).'\'';
	}
	###
	function logout($redirect=true){
		global $conf;
		setcookie("$this->session_name", "", time()-60*60*24*100, "/");
		setcookie("tokenAdmin", "", time()-60*60*24*100, "/");
		setcookie("token", "", time()-60*60*24*100, "/");
		unset($_SESSION["$this->session_name"]);
		session_unset();
		if($redirect===true){
			$this->redirect($conf['baseurl'].'/index.html');
		}
	}
	###
	function redirect($location='index.php'){
		header("location:".$location);
		exit; // Merci fr0g!
	}
	//Obscure
	function obscure($password, $algorythm = "sha1"){
		$password = strtolower($password);
		$salt = hash($algorythm, $this->hash);
		$hash_length = strlen($salt);
		$password_length = strlen($password);
		$password_max_length = $hash_length / 2;
		if ($password_length >= $password_max_length){
			$salt = substr($salt, 0, $password_max_length);
		} else {
			$salt = substr($salt, 0, $password_length);
		}
		$salt_length = strlen($salt);
		$salted_password = hash($algorythm, $salt . $password);
		$used_chars = ($hash_length - $salt_length) * -1;
		$final_result = $salt . substr($salted_password, $used_chars);
	
		return $final_result;
	}
	###
	function get_gravatar( $email, $s = 120, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
	##
	function getPassword($email){
		global $db;
		$db->query('SELECT pass FROM '.$this->prefix_db.'users WHERE mail="'.$db->escape($email).'"');
		$result = $db->get_row();
		return $result->pass;
	}
	##
	function logPasswordChange($email) {
		global $db, $agent;
		if ($agent->isBrowser()) {
			$info = array(
				'ip'      => $agent->user_IP,
				'host'      => gethostbyaddr($agent->user_IP),
				'browser'   => $agent->browser.' v'.$agent->version,
				'os'      => $agent->platform
			);
		}
		$query = 'INSERT INTO '.$this->prefix_db.'pass_change_log (email,old_pass,date,ip,ip_host,browser,os,token) VALUES (
			"'.$db->escape($email).'",
			"'.$this->getPassword($email).'",
			"'.time().'",
			"'.$db->escape($info['ip']).'",
			"'.$db->escape($info['host']).'",
			"'.$db->escape($info['browser']).'",
			"'.$db->escape($info['os']).'",
			"'.$this->obscure(uniqid()).'"
		)';
		$db->query($query);
	}

}
?>
