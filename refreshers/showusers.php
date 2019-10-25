<?php
/*
  FILE: refreshers/showusers.php
  DESCRIPTION: lists users matching searching criteria
*/
  include("../src/base.php");
  if(!$_SESSION['logged']) return;

  $search = addslashes($_POST['search']);
  if(!strlen(trim($search))) return;

  $users = mysqli_query($conn,"SELECT * FROM users WHERE `name` LIKE '%".$search."%' OR `login` LIKE '%".$search."%' ORDER BY name LIMIT 10;");
  while($u = mysqli_fetch_assoc($users)) {
     echo '<input type="radio" name="recipient" value="'.$u['login'].'"> '.$u['name'].'<br>';
  }

?>
