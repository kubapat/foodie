<?php
/*
 FILE: refreshers/getAnnEditForm.php
 DESCRIPTION: returns edition form/announcement content for JQuery request at index.php

 Error codes:
   - "code-1" Insufficient privilages (non-admin user)
   - "code-2" No announcement with this ID
   - "code-3" Wrong request param (different than "edit" && "cancel")
*/
  include("../src/base.php");

  if(!$_SESSION['logged']) return;
  if($_SESSION['type'] != 'admin') {
      echo "code-1";
      return;
  }

  $id   = addslashes($_GET['id']);
  $type = addslashes($_GET['type']);
  $query = mysqli_query($conn,"SELECT * FROM announcements WHERE id='".$id."';");

  if(!mysqli_num_rows($query)) {
     echo "code-2";
     return;
  }

  $query = mysqli_fetch_row($query);

  if($type == "edit") {
     echo '<form method="POST" action="submit-ann.php?type=edit&id='.$id.'">
          <b>'.Langs::translations[$_SESSION['lang']]["home"]["title"].'</b><br>
          <input type="text" class="form-control" name="title" value="'.$query[1].'" required><br>
          <b>'.Langs::translations[$_SESSION['lang']]["home"]["pubDate"].'</b><br>
          <input type="text" class="form-control" name="date" value="'.$query[3].'" required><br>
          <b>'.Langs::translations[$_SESSION['lang']]["home"]["image"].'</b><br>
          <input type="url" class="form-control" name="photo" value="'.$query[5].'"><br>
          <b>'.Langs::translations[$_SESSION['lang']]["home"]["text"].'</b><br>
          <textarea id="editor" name="text" required>'.$query[2].'</textarea><br><br><br>

          <div align="right">
            <button type="submit" name="edit" class="btn btn-info">'.Langs::translations[$_SESSION['lang']]["home"]["editAnn"].'</button>
          </div>
        </form>';
   } else if($type == "cancel") {
      echo '<div align="right">
             <b>'.Langs::translations[$_SESSION['lang']]["home"]["AnnBy"].'</b> <a href="user.php?id='.Utilities::getId($query[4]).'"><span style="color:black;">'.Utilities::getName($query[4]).'</span></a><br>
             '.substr($query[3],0,16).'
            </div><br>
            '.$query[2];
   } else echo "code-3";

?>
