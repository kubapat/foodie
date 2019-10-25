<?php
/*
  FILE: update-profile.php
  DESCRIPTION: Updates profile data and changes password
*/
 include("src/base.php");

 if(!$_SESSION['logged']) {
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
    $_SESSION['errcolor'] = 'error';
    header('Location: login.php');
    return;
 }

 if(isset($_POST['edit'])) { //Edit profile data
    $name  = $purifier->purify(addslashes($_POST['name']));
    $email = addslashes($_POST['email']);
    $img   = addslashes(trim($_POST['img']));
    $loc   = addslashes($_POST['location']);

    if(!strlen($name) || strlen($name)>100) { //Invalid name
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidName"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Invalid e-mail
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidEmail"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    }

    if(!strlen($img) || $img == 'NONE') $img="https://media.istockphoto.com/photos/red-apple-with-leaf-picture-id683494078?k=6&m=683494078&s=612x612&w=0&h=aVyDhOiTwUZI0NeF_ysdLZkSvDD4JxaJMdWSx2p3pp4=";
    else if(!Utilities::verifyImage($img)) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidImage"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    } //Set up image

    $lat = (double)Utilities::getLatLng($loc,"lat");
    $lng = (double)Utilities::getLatLng($loc,"lng");

    if($lat<0 || $lng<0 || $lat>90 || $lng>90 || !Utilities::strContains($loc,",")) { //Invalid location
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidLoc"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    }

    mysqli_query($conn,"UPDATE users SET name='".$name."', email='".$email."', address='".$loc."', image='".$img."' WHERE
                          login='".addslashes($_SESSION['login'])."';");

    $_SESSION['name'] = $name;


    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["dataUpdated"];
    $_SESSION['errcolor'] = 'success';
    header('Location: profile.php');
    return;
 } else if(isset($_POST['change'])) { //Change password
    $oldpass = hash("sha256",$_POST['oldpass'],false);
    $newpass = hash("sha256",$_POST['newpass'],false);
    $repeat  = hash("sha256",$_POST['repeat'], false);

    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE passwd='".$oldpass."' AND login='".addslashes($_SESSION['login'])."';"))) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["passInvalid"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    } //Does old password match?

    if($newpass != $repeat) { //Repeated password incorrectly
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["passNotSame"];
       $_SESSION['errcolor'] = 'error';
       header('Location: profile.php');
       return;
    }

    mysqli_query($conn,"UPDATE users SET passwd='".$newpass."' WHERE login='".addslashes($_SESSION['login'])."';");
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["passChanged"];
    $_SESSION['errcolor'] = 'success';
    header('Location: index.php?logout=true');
    return;
 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: profile.php');
    return;
 }

?>
