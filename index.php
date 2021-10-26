<?php
// 
// .htaccess or some kind of php configuration is needed
//  link to public.php should bring up .php/public.php
//  without showing the user the .php/ directory

include(".php/index.php");
//   rename .php folder to the directory  to access the code
//   for example, rename to projectManager
//   then access as [server-htdocs]/projectManager/index.php

//   the current code assumes all php scripts are in this directory
//   if working code is in htdocs/dev/.php
//   use [server-htdocs]/dev/.php/index.php for testing and development.
//   then all links will work without any redirects

?>
