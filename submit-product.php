<?php
/*
  FILE: submit-product.php
  DESCRIPTION: all operations with products management and operations
*/
 include("src/base.php");

 if(!$_SESSION['logged']) {
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
    $_SESSION['errcolor'] = 'error';
    header('Location: login.php');
    return;
 }

 if($_GET['type'] == 'add' && isset($_POST['add'])) { //Add new product
    $quantity = (int)addslashes($_POST['quantity']);
    $name     = $purifier->purify(addslashes($_POST['name']));
    $img      = addslashes(trim($_POST['img']));
    $bestDate = addslashes($_POST['bestBefore']);
    $status   = addslashes($_POST['status']);
    $descr    = $purifier->purify(addslashes($_POST['description']));

    if($quantity<1 || $quantity>100) { //Invalid quantity
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidQuant"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    if(!strlen($name) || strlen($name) > 100) { //Invalid title
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    if(!strlen(trim($descr))) $desc='NONE';

    if(!strlen($img) || $img == 'NONE') $img='NONE'; //Validate image
    else if(!Utilities::verifyImage($img)) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidImage"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    if(!checkdate(substr($bestDate,6,2),substr($bestDate,9,2),substr($bestDate,0,4)) && strlen(trim($bestDate))) { //Invalid date
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidDate"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    } else if(!strlen(trim($bestDate))) $bestDate = 'NONE';

    if($status != 'hold' && $status != 'open') { //Invalid status
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidStatus"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    for($i=0; $i<$quantity; $i++) { //INSERT INTO DB
       mysqli_query($conn,"INSERT INTO `products` (`name`,`description`,`image`,`owner`,`bestBefore`,`status`) VALUES 
          ('".$name."', '".$descr."', '".$img."', '".addslashes($_SESSION['login'])."', '".$bestDate."', '".$status."');");
    }


    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["foodAdded"];
    $_SESSION['errcolor'] = 'success';
    header('Location: storage.php');
    return;

 } else if($_GET['type'] == 'edit' && isset($_POST['forward'])) { //Giving product to another user
    $recipient = addslashes($_POST['recipient']);
    if(!mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE login='".$recipient."';"))) {
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["userNotFound"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    if($recipient == $_SESSION['login']) { //Cannot give product to myself
       $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["sameRecipient"];
       $_SESSION['errcolor'] = 'error';
       header('Location: storage.php');
       return;
    }

    $products = mysqli_query($conn,"SELECT * FROM products WHERE owner='".addslashes($_SESSION['login'])."' AND status='open';");
    while($p = mysqli_fetch_assoc($products)) {
       if(!isset($_POST['productCheck'.$p['id']])) continue; //If product uncheck continue
       if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `operations` WHERE product='".addslashes($p['id'])."' AND confirmed='0';"))) continue;

       mysqli_query($conn,"INSERT INTO `operations` (`product`, `from`, `to`, `date`, `confirmed`) VALUES ('".addslashes($p['id'])."',
                     '".addslashes($_SESSION['login'])."', '".addslashes($recipient)."', '".date('Y-m-d H:i:s')."', '0');");
    }

    //Notify user
    mysqli_query($conn,"INSERT INTO `notifications` (`text`, `recipient`, `seen`, `date`) VALUES ('confirm', '".addslashes($recipient)."', '0', '".date('Y-m-d H:i:s')."');");


    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["forwarded"];
    $_SESSION['errcolor'] = 'success';
    header('Location: storage.php');
    return;
 } else if($_GET['type'] == 'edit' && isset($_POST['edit'])) { //Edit product
    $products = mysqli_query($conn,"SELECT * FROM products WHERE owner='".addslashes($_SESSION['login'])."';");

    while($p = mysqli_fetch_assoc($products)) {
       if(!isset($_POST['name'.$p['id']])) continue;
       if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM operations WHERE product='".addslashes($p['id'])."' AND `from`='".addslashes($_SESSION['login'])."' AND confirmed='0';"))) continue;
       //If wasn't in edited group or was given to someone and is awaiting confirmation continue;

       $name     = $purifier->purify(addslashes($_POST['name'.$p['id']]));
       $img      = addslashes(trim($_POST['image'.$p['id']]));
       $bestDate = addslashes($_POST['bestBefore'.$p['id']]);
       $status   = addslashes($_POST['status'.$p['id']]);
       $descr    = $purifier->purify(addslashes($_POST['text'.$p['id']]));

       if(!strlen($name) || strlen($name) > 100) { //Invalid name
          $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["rangeAnnTitle"];
          $_SESSION['errcolor'] = 'error';
          header('Location: storage.php');
          return;
       }

       if(!strlen(trim($descr))) $desc='NONE';

       if(!strlen($img) || $img == 'NONE') $img='NONE'; //Validate image
       else if(!Utilities::verifyImage($img)) {
          $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidImage"];
          $_SESSION['errcolor'] = 'error';
          header('Location: storage.php');
          return;
       }

       if(!checkdate(substr($bestDate,6,2),substr($bestDate,9,2),substr($bestDate,0,4)) && strlen(trim($bestDate))) { //Invalid date
         $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidDate"];
         $_SESSION['errcolor'] = 'error';
         header('Location: storage.php');
         return;
       } else if(!strlen(trim($bestDate))) $bestDate = 'NONE';

       if($status != 'hold' && $status != 'open' && $status != "done") { //Invalid status
         $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["invalidStatus"];
         $_SESSION['errcolor'] = 'error';
         header('Location: storage.php');
         return;
       }

       mysqli_query($conn,"UPDATE `products` SET `name`='".$name."',`description`='".$descr."',`image`='".$img."',`bestBefore`='".$bestDate."',
                `status`='".$status."' WHERE id='".addslashes($p['id'])."';");
    }

    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["storage"]["foodEdited"];
    $_SESSION['errcolor'] = 'success';
    header('Location: storage.php');
    return;
 } else if($_GET['type'] == 'confirm' && isset($_POST['confirm'])) { //Confirm or reject operation
    $me  = addslashes($_SESSION['login']);
    $ops = mysqli_query($conn,"SELECT * FROM operations WHERE (`from`='".$me."' OR `to`='".$me."') AND confirmed='0';");

    $confirmed = array();

    while($o = mysqli_fetch_assoc($ops)) {
       if(!isset($_POST['confirmCheck'.$o['id']])) continue;

       if($o['from'] == $me) mysqli_query($conn,"DELETE FROM operations WHERE id='".addslashes($o['id'])."';");
       else if($o['to'] == $me) {
         mysqli_query($conn,"UPDATE products   SET owner='".$me."', status='hold'  WHERE id='".addslashes($o['product'])."';");
         mysqli_query($conn,"UPDATE operations SET confirmed='1'   WHERE id='".addslashes($o['id'])."';");
         if(!in_array($o['from'],$confirmed)) {
             array_push($confirmed,$o['from']);
             mysqli_query($conn,"INSERT INTO `notifications` (`text`, `recipient`, `seen`, `date`) VALUES ('confirmed', '".addslashes($o['from'])."', '0', '".date('Y-m-d H:i:s')."');");
         }
       }
    }


    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["opsEdited"];
    $_SESSION['errcolor'] = 'success';
    header('Location: storage.php');
    return;
 } else { //Bad Request
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
    $_SESSION['errcolor'] = 'error';
    header('Location: storage.php');
    return;
 }

?>
