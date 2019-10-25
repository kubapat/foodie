<?php
  include("src/base.php");

  if(!$_SESSION['logged']) { //isUserLogged
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

 if($_SESSION['type'] != 'admin') { //isUserAdmin
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["noPrivs"];
    $_SESSION['errcolor'] = 'error';
    header('Location: index.php');
    return;
 }

 $user = addslashes($_GET['user']);
 $userdata = mysqli_query($conn,"SELECT * FROM users WHERE id='".$user."';");

 if(!mysqli_num_rows($userdata)) { //DoesUserExist
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["userNotFound"];
    $_SESSION['errcolor'] = 'error';
    header('Location: admin.php');
    return;
 }

 $userdata = mysqli_fetch_row($userdata);

 if($_GET['type'] == 'privs') { //Privs change operation
    $current = $userdata[5];
    if($current == "admin") $newPrivs = "user";
    else $newPrivs = "admin";

    mysqli_query($conn,"UPDATE users SET `privs`='".$newPrivs."' WHERE id='".addslashes($user)."';");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["privsEdited"];
    $_SESSION['errcolor'] = 'success';
    header('Location: admin.php');
    return;
 } else if($_GET['type'] == 'ban') { //Ban operation
    $current = $userdata[6];
    if($current== "1") $banVal = "0";
    else $banVal = "1";

    mysqli_query($conn,"UPDATE users SET `banned`='".$banVal."' WHERE id='".addslashes($user)."';");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["banEdited"];
    $_SESSION['errcolor'] = 'success';
    header('Location: admin.php');
    return;
 } else { //Bad request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: admin.php');
    return;
 }



?>
