<?php

//the .php directory should be considered a library path
//executing from here creates
// pretty but invalid links that dont show the resource path
// like sysLogin.php, public.php
// 
// .htaccess or some kind of php configuration is needed
//  link to public.php should bring up .php/public.php
//  without showing the user the .php/ directory

include(".php/index.php");

//   current code assumes all php scripts are in docroot
//   if working code is in htdocs/dev/sustainability
//   use [server]/dev/sustainability/.php/index.php
//   then all links will work without any redirects


?>
