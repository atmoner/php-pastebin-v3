<?php
/*
__
_____ _/ |_ _____ ____ ____ ___________
\__ \ __\/ \ / _ \ / \_/ __ \_ __ \
/ __ \| | | Y Y ( <_> ) | \ ___/| | \/
(____ /__| |__|_| /\____/|___| /\___ >__|
\/ \/ \/ \/

Website: http://atmoner.com/
Contact: contact@atmoner.com
*/

session_start(); 
$path = dirname(__FILE__);

/* if (filesize($path."/libs/db.php") != 0) {
	header("location:index.php");		
}  */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - Php-pastebin V3</title>
<meta name="keywords" content="php,hook,plugin" />
<link rel='stylesheet' href='themes/bootstrap/style/install.css' type='text/css' />
</head>
<body>
<div id="dcpage">
<div id="header">
<a href="install.php"> <h1>Php-pastebin V3 </h1></a>
</div>
<br /><br />
<?php

if (empty($_GET["step"])) {
	if (empty($_GET["update"])) {
		echo "<div class='section_box'>Welcome to the installation of Php-pastebin V3.<br />
		To install this scirpt, you must have:<br /><br />
		- server Web (<a href='http://en.wikipedia.org/wiki/Web_server' target='_BLANK'>more info</a>)<br />
		- server Mysql (<a href='http://en.wikipedia.org/wiki/MySQL' target='_BLANK'>more info</a>)</div><br /><br />
		<a href=\"install.php?step=1\" class=\"button\">Start installation</a> <a href=\"install.php?update=1\" class=\"button\">Start update</a><br />
		";
	}
}
 
function executeQueryFile($filesql) {
$query = file_get_contents($filesql);
$array = explode(";\n", $query);
$b = true;
for ($i=0; $i < count($array) ; $i++) {
$str = $array[$i];
if ($str != '') {
$str .= ';';
$b &= mysql_query($str);	
}	
}	
return $b;
}
 
 
function is__writable($path) {
$i = 0;
$error = "";
while($i < count($path)){
if (is_writable($path[$i]))
echo '- '.$path[$i].' -> <font color="green">The file/folder is writable</font><br />';
else {
echo '- '.$path[$i].' -> <font color="red">The file/folder is not writable</font><br />';
$error .= "1";
}
$i++;
}
if(empty($error))
return true;
else
return false;
}	
function redirect($location){
header("location:".$location);
}	
 
if ($_GET["update"] == "1") {

    require($path.'/libs/startup.php');
				$query = "SELECT u.id, u.name, u.mail, u.level, u.signature, u.seemail, u.location, u.website, statuts.id, statuts.level, statuts.maxlines FROM ".$startUp->prefix_db."users AS u";
				$query .= " INNER JOIN statuts ON u.level=statuts.id";
				$query .= " WHERE u.id='1' LIMIT 1";
    		$user = $db->get_row($query,OBJECT); // get result in objet (OBJECT) 
 
    echo " <div class='section_box'><b><u>Update token admin account:</u></b><br /><br />";
    echo "User: ".$user->name;
    echo "<br />Mail: ".$user->mail;
    echo "<br /><br /><a href=\"install.php?update=2\" class=\"button\">Update account</a> ";
    
 
} 

if ($_GET["update"] == "2") {

    require($path.'/libs/startup.php');
				
    $db->get_results("SELECT testt FROM users");
	if (!$db->col_info) {
		$db->query("ALTER TABLE `users` ADD `token` VARCHAR( 100 ) NOT NULL");
	
	}	
	if ($db->query("UPDATE `users` SET `token` = '".$startUp->generateToken()."' WHERE `id` =1")) {
		echo " <div class='section_box'><font color=\"green\"><u>Your update is finish, <a href='/'>Go on your pastebin</a></u></font><br /><br /></div>";		
	} else 
 		echo " <div class='section_box'><font color=\"green\"><u>There is a probleme :/ please retry it!</u></font><br /><br />";
    
 
}

if ($_GET["step"] == "1") {
    
    echo " <div class='section_box'>Check chmod:<br /><br />";
    $error = "1";
    $path = 'upload';
    echo "<b><u>Folders:</u></b><br />\n";
    $path_check = array('cache', 'cache/compile_tpl','libs/db.php' );
    if (is__writable($path_check)){
      echo "</div><br /><a href=\"install.php?step=2\" class=\"button\">Step 2</a>\n";
      $_SESSION['step_one'] = 'ok';
    }
    else
      echo "</div><br /><a href=\"install.php?step=1\" class=\"button\">Permissions test</a>\n";
     
}

if ($_GET["step"] == "2") {
 
if (empty($_GET["action"])) { ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=2&action=test" id="mail" method="post">

<table cellpadding="2">
<tr>
<td>Hostname</td>
<td><input type="text" name="hostname" id="hostname" value="" size="30" tabindex="1" /></td>
<td>(usually "localhost")</td>
</tr>
<tr>
<td>Username</td>
<td><input type="text" name="username" id="username" value="" size="30" tabindex="2" /></td>
<td></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" name="password" id="password" value="" size="30" tabindex="3" /></td>
<td></td>
</tr>
<tr>
<td>Database</td>
<td><input type="text" name="database" id="database" value="" size="30" tabindex="4" /></td>
<td></td>
</tr>
<tr>
<td></td>
<td><input type="submit" id="submit" value="Test Connection" tabindex="5" class="button"/></td>
<td></td>
</tr>
</table>

</form>

<?php }
if (!empty($_GET["action"]) and $_GET["action"] == "test") {
$hostname = trim(htmlentities($_POST['hostname']));
$username = trim(htmlentities($_POST['username']));
$password = trim(htmlentities($_POST['password']));
$database = trim(htmlentities($_POST['database']));

$link = mysql_connect($hostname,$username,$password);
if (!$link || empty($hostname)) {
echo " Could not connect to the server \n";
         echo mysql_error();
} else
echo " Successfully connected to the server <br />\n";
 
if ($link && !$database) {
echo "<br /><br /> No database name was given. <br />Available databases:</p>\n";
$db_list = mysql_list_dbs($link);
echo "<pre>\n";
while ($row = mysql_fetch_array($db_list)) {
      echo $row['Database'] . "\n";
}
echo "</pre>\n";
}
if ($database) {
    $dbcheck = mysql_select_db("$database");
if (!$dbcheck) {
         echo "<img src=\"lib/icons/no.png\"> ".mysql_error();
}else{
echo "<img src=\"themes/img/ok.png\"> Successfully connected to the database '" . $database . "' \n<br /><br />";
echo "<form action=\"install.php?step=2&action=w\" id=\"mail\" method=\"post\">\n";
echo "<input type=\"hidden\" name=\"hostname\" value=\"".$hostname."\">\n";
echo "<input type=\"hidden\" name=\"username\" value=\"".$username."\">\n";
echo "<input type=\"hidden\" name=\"password\" value=\"".$password."\">\n";
echo "<input type=\"hidden\" name=\"database\" value=\"".$database."\">\n";
echo "<input type=\"submit\" id=\"submit\" value=\"Install database !\" tabindex=\"5\" />\n";
echo "</form>\n";
}
}

}

    if (!empty($_GET["action"]) and $_GET["action"] == "w") {
      if (is_writable($path."/libs/db.php"))
      
         {
         $fd = fopen($path."/libs/db.php", "w+");
         $foutput = "<?php\n";
         $foutput.= "// Generate the ".date("j F, Y, h:i:s")."\n";
         $foutput.= "// For Php-pastebin\n";
         $foutput.= "\$host = \"".$_POST["hostname"]."\";\n";
         $foutput.= "\$user = \"".$_POST["username"]."\";\n";
         $foutput.= "\$pass = \"".$_POST["password"]."\";\n";
         $foutput.= "\$db = \"".$_POST["database"]."\";\n";
         $foutput.= "// Please ! manipulate this file if you know what you made​​!\n";
         $foutput.= "";
         fwrite($fd,$foutput);
         fclose($fd);
         }
         
     require($path.'/libs/startup.php');
	 executeQueryFile($path."/libs/db.sql");

        // Install OK :)
$_SESSION['step_two'] = 'ok';
redirect("install.php?step=3");
       }
}

if ($_GET["step"] == "3") {
if(empty($_SESSION['step_two']))
header('Location: install.php?step=2');
else {
if (isset($_GET['action']) && $_GET['action'] === 'update') {
	if (!empty($_POST['username'])) {
		if (!empty($_POST['pass'])) {
			if (!empty($_POST['mail'])){
				include $path.'/libs/startup.php';
				$db->query("UPDATE settings SET value = '".$_POST['path']."' WHERE `settings`.`key` = 'baseurl';");
				$db->query("UPDATE settings SET value = '".$_POST['sitetitle']."' WHERE `settings`.`key` = 'title';");
				$startUp->addUser($_POST['username'],$_POST['mail'],$_POST['pass'],'NULL','NULL','TRUE');
				
				$_SESSION['step_tree'] = 'ok';
				redirect("install.php?step=4");	

			} else 
				echo 'Mail can not be empty!';	
		} else 
			echo 'Password can not be empty!';		
	} else 
		echo 'User name can not be empty!';

		
} else {

$var = parse_url($_SERVER["SCRIPT_NAME"]);
$path = substr($var['path'], 0, -12);
$hote = $_SERVER['HTTP_HOST'];
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=3&action=update" id="mail" method="post">

<table cellpadding="2">
<tr>
	<td><h3>Admin config</h3></td>
</tr>
<tr>
<td>Admin username</td>
<td><input type="text" name="username" size="30" tabindex="1" /></td>
</tr>
<tr>
<td>Admin password</td>
<td><input type="password" name="pass" size="30" tabindex="2" /></td>
<td></td>
</tr>
<tr>
<td>Admin mail</td>
<td><input type="text" name="mail" size="30" tabindex="3" /></td>
</tr>
<tr>
	<td><h3>Web site config</h3></td>
</tr>
<tr>
<td>Web site path</td>
<td><input type="text" name="path" size="30" tabindex="3" value="<?php echo 'http://'.$hote.$path; ?>" /></td>
</tr>
<tr>
<td>Web site title</td>
<td><input type="text" name="sitetitle" size="30" tabindex="3" value="Php-pastebin" /></td>
</tr>
<tr>
<td><br /><input type="submit" id="submit" value="Update config" tabindex="5" class="button"/></td>
</tr>
</table>

</form>
<?php   
	}
  }
}

if ($_GET["step"] == "4") {
if(empty($_SESSION['step_tree']))
header('Location: install.php?step=3');
else {
echo "Your install is ok ! <br />";
echo "Thinks to: <br /><br />";
echo "- remove install.php of your ftp <br />";
echo "- remove libs/db.sql <br />";	
echo "- chmod 644 libs/db.php";
echo "<br /><br /><a href=\"http://".$hote.$path."\" target=\"_blank\" class=\"button\">Go to your website</a>";
        }
}

?>
<br /><br /><br />
</div>
</body>
</html>
