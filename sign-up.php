<?php
/*
  FILE: sign-up.php
  DESCRIPTION: Executes user sign-up process with data from register.php
*/
 include("src/base.php");

 if($_SESSION['logged']) {
    header('Location: index.php');
    return;
 }

 if(isset($_POST['register'])) {
    $login  = $purifier->purify(addslashes($_POST['login']));
    $name   = $purifier->purify(addslashes($_POST['name']));
    $email  = addslashes($_POST['email']);
    $loc    = addslashes($_POST['location']);
    $pass   = hash("sha256",$_POST['password'],false);
    $repeat = hash("sha256",$_POST['repeat'],false);
   
    if(!isset($_POST['g-recaptcha-response']) && empty($_POST['g-recaptcha-response'])) {
      $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
      $_SESSION['errcolor'] = 'error';
      header('Location: register.php');
      return;
    }

    $secret = "SOMEVALUE";
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);

    if(!$responseData->success) {
      $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
      $_SESSION['errcolor'] = 'error';
      header('Location: register.php');
      return;
    }

    if(!strlen($name) || strlen($name)>100) { //Incorrect name
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidName"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    if(!strlen($login) || strlen($login)>100) { //Incorrect login
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidLogin"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE login='".$login."';"))) { //Login already in use
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginUsed"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE email='".$email."';"))) { //E-mail already in user
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["emailUsed"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Invalid e-mail
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidEmail"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    $lat = (double)Utilities::getLatLng($loc,"lat");
    $lng = (double)Utilities::getLatLng($loc,"lng");

    if($lat<0 || $lng<0 || $lat>90 || $lng>90 || !Utilities::strContains($loc,",")) { //Invalid location
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidLoc"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    if($pass != $repeat) { //Passwords aren't the same
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["passNotSame"];
       $_SESSION['errcolor'] = 'error';
       header('Location: register.php');
       return;
    }

    mysqli_query($conn,"INSERT INTO users (login,name,email,passwd,privs,banned,active,last_active,registered,language,
                          address,ip,image,privkey) VALUES ('".$login."', '".$name."', '".$email."', '".$pass."',
                          'user', '0', '1', '2002-09-13 20:50:00', '".date('Y-m-d H:i:s')."', '".$_SESSION['lang']."',
                          '".$loc."', '127.0.0.1', 'https://media.istockphoto.com/photos/red-apple-with-leaf-picture-id683494078?k=6&m=683494078&s=612x612&w=0&h=aVyDhOiTwUZI0NeF_ysdLZkSvDD4JxaJMdWSx2p3pp4=', '".Utilities::genKey()."');");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["registered"];
    $_SESSION['errcolor'] = 'success';
    header('Location: login.php');
    return;
 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: register.php');
    return;
 }

?>
