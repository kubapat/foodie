<?php
/*
  FILE: submit-forum.php
  DESCRIPTION: removes thread/post, adds post/thread in forum area
*/
 include("src/base.php");

 if(!$_SESSION['logged']) {
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
    $_SESSION['errcolor'] = 'error';
    header('Location: login.php');
    return;
 }

 if($_GET['type'] == 'del') { //Delete thread/post
    $id = $_GET['id'];
    $forum = mysqli_query($conn,"SELECT thread FROM `forum` WHERE id='".addslashes($id)."';");
    if(!mysqli_num_rows($forum)) { //Does post/thread exist?
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["thrNotExists"];
       $_SESSION['errcolor'] = 'error';
       header('Location: forum.php');
       return;
    }

    $threadId = mysqli_fetch_row($forum)[0];

    //Find return page
    if($threadId != $id) $retPage = 'thread.php?id='.$threadId;
    else $retPage = 'forum.php';


    mysqli_query($conn,"DELETE FROM `forum` WHERE id='".addslashes($id)."' OR thread='".addslashes($id)."';");
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["threadRemoved"];
    $_SESSION['errcolor'] = 'success';
    header('Location: '.$retPage);
    return;
 } else if($_GET['type'] == 'addReply' && isset($_POST['add'])) { //Add reply to thread
    $id    = addslashes($_GET['id']);
    $text  = $purifier->purify(addslashes($_POST['text']));

    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `forum` WHERE id='".$id."' AND `thread`=`id`;"))) { //Does thread exist?
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["thrNotExists"];
       $_SESSION['errcolor'] = 'error';
       header('Location: forum.php');
       return;
    }


    if(!strlen($text)) { //Invalid text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: thread.php?id='.$id);
       return;
    }

    mysqli_query($conn,"INSERT INTO forum (`title`,`text`,`author`,`date`,`thread`,`seen`) VALUES ('NONE', '".$text."', '".addslashes($_SESSION['login'])."', '".date('Y-m-d H:i:s')."', '".$id."', '0');");

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["threadAdded"];
    $_SESSION['errcolor'] = 'success';
    header('Location: thread.php?id='.$id);
    return;

 } else if($_GET['type'] == 'newThread' && isset($_POST['add'])) { //New Thread
    $title = $purifier->purify(addslashes($_POST['title']));
    $text  = $purifier->purify(addslashes($_POST['text']));

    if(!strlen($title) || strlen($title) > 100) { //Invalid title
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
       $_SESSION['errcolor'] = 'error';
       header('Location: forum.php');
       return;
    }

    if(!strlen($text)) { //Invalid text
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnText"];
       $_SESSION['errcolor'] = 'error';
       header('Location: forum.php');
       return;
    }

    mysqli_query($conn,"INSERT INTO forum (`title`,`text`,`author`,`date`,`thread`,`seen`) VALUES ('".$title."', '".$text."', '".addslashes($_SESSION['login'])."', '".date('Y-m-d H:i:s')."', '-1','0');");
    mysqli_query($conn,"UPDATE forum SET thread=id WHERE thread='-1';"); //Updates thread parameter

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["threadAdded"];
    $_SESSION['errcolor'] = 'success';
    header('Location: forum.php');
    return;

 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: forum.php');
    return;
 }

?>
