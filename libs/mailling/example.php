<?php
    require_once('classes/class.formatmail.php');

    /*
    * FormatMail class is a simple extension of PhpMailer class.
    * This class useful when you want to send complete (the email contains all images and css links as attachments) html formatted email.
    * It containt a mini template mechanism to change data in the temlate file, to the GLOBALS array fields.
    * Importatnt: In the mail template files the path of images and css links, must be relative to the sender script (in this case these must relative to example.php).
    * In this example, we fill up user's real name, username (login), and password.
    */
    
    //In the source of email.htm you can see a css tag: <link href="css/main.css" rel="stylesheet" type="text/css"> that will attached to the mail
    //In templates/email.htm (this will the message body), you can find 3 special macros: {$NAME},{$USERNAME} and {$PASSWORD}
    //We set up these values:
    $GLOBALS['NAME']='The user name';
    $GLOBALS['USERNAME']='Login name';
    $GLOBALS['PASSWORD']='password';
    
    //You can set the email template filename at the FormatMail constructor
    //Importatnt: fill up all GLOBALS field before call this constructor
    $FM=new FormatMail('templates/email.htm');
    //PhpMailer settings
    //FormatMail use an instance of PhpMailer class to send mails
    //For details, see PhpMailer class.
    $FM->Mailer->FromName='Your Name';
    $FM->Mailer->From='your@email.com';
    $FM->Mailer->Subject='Registration';
    $FM->Mailer->AddAddress('contact@atmoner.com','atmoner');
    //And now, send the mail...
    if ($FM->Send())
	echo 'Mail sent successfully.';
    
    //Or echo the result
//    echo $FM->Message;
?>
