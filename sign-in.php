<?php
/*
  FILE: sign-in.php
  DESCRIPTION: This page logs user in by data from form on login.php
*/
  include("src/base.php");

  if($_SESSION['logged']) {
      header('Location: index.php');
      return;
  }


  if(!isset($_POST['loginBtn'])) {
     header('Location: login.php');
     return;
  }

  $login  = $_POST['login'];
  $passwd = hash("sha256", $_POST['password'], false);

  $query = mysqli_query($conn,"SELECT * FROM users WHERE (`login`='".addslashes($login)."' OR `email`='".addslashes($login)."') AND `passwd`='".$passwd."';");

  if(!mysqli_num_rows($query)) { //Incorrect data
      $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["wrongLogin"];
      $_SESSION['errcolor'] = 'error';
      header('Location: login.php');
      return;
  }

  $query = mysqli_fetch_row($query);

  if($query[6] == '1') { //Account banned
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["accBanned"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
  }

  if($query[7] == '0') { //Account not active
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["notActive"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
  }

  //Set up session parameters
  $_SESSION['logged'] = true;
  $_SESSION['login']  = $query[1];
  $_SESSION['name']   = $query[2];
  $_SESSION['email']  = $query[3];
  $_SESSION['type']   = $query[5];
  $_SESSION['lang']   = $query[10];

  mysqli_query($conn,"UPDATE users SET `ip`='".addslashes(Utilities::getUserIP())."' WHERE login='".addslashes($query[1])."';");
  $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loggedIn"];
  $_SESSION['errcolor'] = "success";

  header('Location: index.php');
  return;

?>
