<?php
/*
  FILE: submit-ann.php
  DESCRIPTION: adds, edits and removed system announcement
*/
 include("src/base.php");

 if($_SESSION['type'] != 'admin') {
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["noPrivs"];
    $_SESSION['errcolor'] = 'error';
    header('Location: index.php');
    return;
 }

 if($_GET['type'] == 'del') { //Remove announcement
    $id = $_GET['id'];
    $ann = mysqli_query($conn,"SELECT * FROM `announcements` WHERE id='".addslashes($id)."';");

    if(!mysqli_num_rows($ann)) { //Announcement doesn't exist
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["annNotExists"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    mysqli_query($conn,"DELETE FROM `announcements` WHERE id='".addslashes($id)."';");
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["annDeleted"];
    $_SESSION['errcolor'] = 'success';
    header('Location: index.php');
    return;
 } else if($_GET['type'] == 'edit' && isset($_POST['edit'])) { //Edit announcement
    $id    = addslashes($_GET['id']);
    $title = $purifier->purify(addslashes($_POST['title']));
    $date  = addslashes(substr($_POST['date'],0,19));
    $text  = $purifier->purify(addslashes($_POST['text']));
    $img   = addslashes(trim($_POST['photo']));

    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `announcements` WHERE id='".$id."';"))) { //Announcement doesn't exist
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["annNotExists"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!strlen($title) || strlen($title) > 100) { //Incorrect title
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!strlen($text)) { //Incorrect text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!Utilities::verifyDate($date)) { //Incorrect date
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidDate"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!strlen($img) || $img == "NONE") $img = "NONE"; //Validate image
    else if(strlen($img) > 1000 || !Utilities::verifyImage($img)) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidImage"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    mysqli_query($conn,"UPDATE announcements SET title='".$title."', text='".$text."', `date`='".$date."', photo='".$img."' WHERE id='".addslashes($id)."';");
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["annEdited"];
    $_SESSION['errcolor'] = 'success';
    header('Location: index.php');
    return;
 } else if($_GET['type'] == 'add' && isset($_POST['add'])) { //Add announcement
    $title = $purifier->purify(addslashes($_POST['title']));
    $date  = addslashes(substr($_POST['date'],0,19));
    $text  = $purifier->purify(addslashes($_POST['text']));
    $img   = addslashes(trim($_POST['photo']));

    if(!strlen($title) || strlen($title) > 100) { //Incorrect title
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!strlen($text)) { //Incorrect text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!Utilities::verifyDate($date)) { //Invalid ate
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidDate"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    if(!strlen($img) || $img == "NONE") $img = "NONE"; //Validate image
    else if(strlen($img) > 1000 || !Utilities::verifyImage($img)) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidImage"];
       $_SESSION['errcolor'] = 'error';
       header('Location: index.php');
       return;
    }

    mysqli_query($conn,"INSERT INTO announcements (title,text,date,author,photo) VALUES ('".$title."', '".$text."', '".$date."', '".addslashes($_SESSION['login'])."', '".$img."');");
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["annAdded"];
    $_SESSION['errcolor'] = 'success';
    header('Location: index.php');
    return;

 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: index.php');
    return;
 }

?>
