<?php
/*
  FILE: refreshers/clearNotifications.php
  DESCRIPTION: Makes all pending notifications seen
*/
  include("../src/base.php");

  if(!$_SESSION['logged']) return;

  mysqli_query($conn,"UPDATE `notifications` SET seen='1' WHERE recipient='".addslashes($_SESSION['login'])."' AND seen='0';");
?>
