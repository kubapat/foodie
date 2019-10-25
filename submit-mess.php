<?php
/*
  FILE: submit-mess.php
  DESCRIPTION: sends message, reply in messages area
*/
 include("src/base.php");

 if(!$_SESSION['logged']) {
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
    $_SESSION['errcolor'] = 'error';
    header('Location: login.php');
    return;
 }

 if($_GET['type'] == 'addReply' && isset($_POST['send'])) { //Add new reply
    $id    = addslashes($_GET['id']);
    $text  = $purifier->purify(addslashes($_POST['text']));

    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `messages` WHERE id='".$id."' AND `response`=`id`;"))) { //Does message we're replying to exist?
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["messNotExists"];
       $_SESSION['errcolor'] = 'error';
       header('Location: inbox.php');
       return;
    }


    if(!strlen($text)) { //Invalid text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: mess.php?id='.$id);
       return;
    }

    $data = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM messages WHERE id='".$id."';"));

    if($data[2] == $_SESSION['login']) $sender = $data[1];
    else $sender = $data[2];

    mysqli_query($conn,"INSERT INTO `messages` (`recipient`, `sender`, `subject`, `text`, `date`, `response`, `seen`) VALUES ('".addslashes($sender)."',
        '".addslashes($_SESSION['login'])."', 'RE:".addslashes($data[3])."', '".$text."', '".date('Y-m-d H:i:s')."', '".$id."', '0');");

    //Notification
    mysqli_query($conn,"INSERT INTO `notifications` (`text`, `recipient`, `seen`, `date`) VALUES ('confirm', '".addslashes($sender)."', '0', '".date('Y-m-d H:i:s')."');");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["messSent"];
    $_SESSION['errcolor'] = 'success';
    header('Location: mess.php?id='.$id);
    return;

 } else if($_GET['type'] == 'new' && isset($_POST['send'])) { //Add new message
    $title     = $purifier->purify(addslashes($_POST['title']));
    $text      = $purifier->purify(addslashes($_POST['text']));
    $recipient = addslashes($_POST['recipient']);

    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE login='".$recipient."';"))) { //Invalid recipient
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["userNotFound"];
       $_SESSION['errcolor'] = 'error';
       header('Location: inbox.php');
       return;
    }

    if(!strlen($title) || strlen($title) > 100) { //Invalid title/subject
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
       $_SESSION['errcolor'] = 'error';
       header('Location: inbox.php');
       return;
    }

    if(!strlen($text)) { //Invalid text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: inbox.php');
       return;
    }


    mysqli_query($conn,"INSERT INTO `messages` (`recipient`, `sender`, `subject`, `text`, `date`, `response`, `seen`) VALUES ('".$recipient."',
        '".addslashes($_SESSION['login'])."', '".$title."', '".$text."', '".date('Y-m-d H:i:s')."', '-1', '0');");

    mysqli_query($conn,"UPDATE messages SET response=id WHERE response='-1';"); //Updates response parameter
    mysqli_query($conn,"INSERT INTO `notifications` (`text`, `recipient`, `seen`, `date`) VALUES ('confirm', '".addslashes($recipient)."', '0', '".date('Y-m-d H:i:s')."');");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["messSent"];
    $_SESSION['errcolor'] = 'success';
    header('Location: inbox.php');
    return;
 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: forum.php');
    return;
 }

?>
