<?php
/*
  FILE: ajaxData.php
  DESCRIPTION: displays generated JSON encoded array containing data for ajax Based table in list.php
*/
  include("src/base.php");
  include("src/funcs/products.php");

  if(!$_SESSION['logged']) return;

  Products::generateAjaxData();
?>
