<?php
/*
  FILE: src/content/connect.php
  DESCRIPTION: Contains DB connection data

  Methods:
   - NONE

  Variables:
   - dbHost   - Host address for DB
   - dbPort   - Port number for DB
   - dbName   - Name of DB attempted to be connected
   - dbUser   - Username for DB
   - dbPasswd - Password for DB
   - conn     - mysqli_connect type data (connection to DB using above vars)
*/

 $dbHost = "localhost";
 $dbPort = 3306;
 $dbName = "foodie";

 $dbUser   = "user";
 $dbPasswd = "Password";

 $conn = mysqli_connect($dbHost,$dbUser,$dbPasswd,$dbName);
?>
