<?php
/*
  FILE: src/base.php
  DESCRIPTION: contains class-inits & "includes" for commonly used classes

  Methods:
    - NONE

  Variables:
    - NONE
*/


  session_start(); //Session init
  require_once 'content/connect.php'; //DB Connect data
  require_once 'HTMLPurifier.auto.php'; //Purfies text

  //Content area files
  include("content/langs.php"); //Langs class
  include("content/head.php"); //Head class
  include("content/scripts.php"); //Scripts class

  //Funcs area files
  include("funcs/utils.php"); //Utilities class
  include("funcs/sidebar.php"); //Sidebar class
  include("funcs/footer.php"); //Footer class
  include("funcs/header.php"); //Header class
  include("funcs/errors.php"); //Errors class

  //Update language depending on browser language & user selection + last_active
  if(isset($_GET['lang']) && strlen($_GET['lang']) == 2) $_SESSION['lang'] = Langs::updateLanguage();
  $_SESSION['lang']  = Langs::detectLanguage();

  Utilities::updateActivity(); //Updates last_active value

  $config = HTMLPurifier_Config::createDefault(); //Configs for text purifier
  $config->set('Core', 'Encoding', 'UTF-8');
  $config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
  $purifier = new HTMLPurifier($config);

?>
