![Hook](http://a.fsdn.com/allura/p/php-pastebin/icon) Php-pastebin V3
=============

* * *

*   [What is Php-pastebin?](#what-is-php-pastebin "What is Php-pastebin?")
*   [How to install php-pastebin?](#how-to-install-php-pastebin "How to install php-pastebin?")
*   [How to upgrade v2 to v3?](#how-to-upgrade-v2-to-v3 "How to upgrade v2 to v3?")
*   [Demonstration](#demonstration "Demonstration")
*   [Thanks](#thanks "Thanks")


### What is Php-pastebin? ###

> Pastebin is here to help you collaborate on debugging code snippets.  
> If you're not familiar with the idea, most people use it like this:  

*   Submit a code fragment to Php-pastebin,  
*   Getting a url like http://demo.php-pastebin.com/DSNnglex,
*   paste the url into an IRC or IM conversation, someone responds by reading and perhaps submitting a modification of your code, you then view the modification, maybe using the built in diff tool to help locate the changes

### How to install php-pastebin? ###

To install php-pastebin, you do not need much!  

1.  Web server  
2.  Mysql database  
3.  mod_rewrite enabled on your apache web server (see it in your phpinfo())  

To begin, send your file on ftp and open your browser to the selected domain name, you should see the installation page.   
Follow the installation instructions.

Once the installation is complete, you must delete the install.php file, libs/db.sql and put a chmod 644 on the file libs/db.php

### How to upgrade v2 to v3? ###

To update your version of php-pastebin, you must:  

   1.  Delete old version
    * Keep preciously files *libs/db.php* (which contains your login sql)
    * Delete all files of your FTP 
   2.  Upload New version
    * Upload the v3 of php-pastebin on your FTP
    * Replace db.php with your
   3.  Download [upgrade.php](http://wiki.php-pastebin.com/_media/upgrade.php "upgrade.php") and add it at root of your script   
    * Go on your navigator and open upgrade.php
    * Click on "Upgrade v2 to v3 now!"
   4.  Remove upgrade.php and go on your website

### Demonstration ###

[Demonstration](http://demo.php-pastebin.com/ "Demonstration")

### Thanks ###

For safety tests, thanks to:

*   [@fr0gSecurity](https://twitter.com/fr0gSecurity "@fr0gSecurity")
*   [kallimero](http://hwc-crew.org/ "kallimero")
*   [@5ct34m](https://twitter.com/5ct34m "@5ct34m")
*   [@BinarySecurity ](https://twitter.com/BinarySecurity "@BinarySecurity ")

For Debug, thanks to:

*   [@QuentinF_](https://twitter.com/QuentinF_ "@QuentinF_")
