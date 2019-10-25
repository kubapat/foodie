<?php
/*
  FILE: src/funcs/mainpage.php
  DESCRIPTION: All operations related with HOME(index.php)

  Methods:
   - listAnnouncements() - lists all recent announcements
   - genNewAnnouncementModal() - generates modal placed at the bottom of index.php triggered when adding new announcement
   - getStats($type) - return numeric value of specified Statistics by parameter $type

  Variables:
   - NONE
*/

  class MainPage {

      public function genNewAnnouncementModal() {
        return '<div id="newAnnModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newAnnModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title" id="newAnnModalLabel">'.Langs::translations[$_SESSION['lang']]["home"]["newAnn"].'</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="submit-ann.php?type=add" id="newAnnForm">

                              <b>'.Langs::translations[$_SESSION['lang']]["home"]["title"].'</b><br>
                              <input type="text" class="form-control" name="title" required><br>
                              <b>'.Langs::translations[$_SESSION['lang']]["home"]["pubDate"].'</b><br>
                              <input type="text" class="form-control" name="date" value="'.date('Y-m-d H:i:s').'" required><br>
                              <b>'.Langs::translations[$_SESSION['lang']]["home"]["image"].'</b><br>
                              <input type="url" class="form-control" name="photo"><br>
                              <b>'.Langs::translations[$_SESSION['lang']]["home"]["text"].'</b><br>
                              <textarea id="editor" name="text" required></textarea><br><br><br>

                              <div align="right">
                                 <button type="submit" onclick="document.getElementById(\'newAnnForm\').submit()" name="add" class="btn btn-success">'.Langs::translations[$_SESSION['lang']]["home"]["addAnn"].'</button>
                              </div>
                            </form>
                        </div>
                     </div>
                  </div>
             </div>';
      }

      public function listAnnouncements() {
          global $conn;
          if($_SESSION['type'] == 'admin') $query = mysqli_query($conn,"SELECT * FROM `announcements` ORDER BY `date` DESC LIMIT 20;");
          else $query = mysqli_query($conn,"SELECT * FROM `announcements` WHERE `date`<='".date('Y-m-d H:i:s')."' ORDER BY `date` DESC LIMIT 20;");


          if(!mysqli_num_rows($query)) {
             echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card">
                        <div class="card-header">
                          <h4 class="card-title">'.Langs::translations[$_SESSION['lang']]["home"]["noAnns"].'</h4>
                        </div>
                      </div>
                   </div>';

             return;
          }

          while($p = mysqli_fetch_assoc($query)) {
              echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                      <div class="card">';
                        if(!Utilities::strContains($p['photo'],'NONE')) echo '<img  class="card-img-top" src="'.$p['photo'].'" alt="'.Langs::translations[$_SESSION['lang']]["home"]["AnnImgAlt"].'"/>';
                        echo '
                        <div class="card-header">
                          <h4 class="card-title">'.$p['title'].'</h4>';
                          if($_SESSION['type'] == 'admin') {
                            echo '<br><div align="right">
                                        <button onclick="showAnnEditMenu(\''.$p['id'].'\')" class="btn btn-info">'.Langs::translations[$_SESSION['lang']]["home"]["editAnn"].'</button>&nbsp;
                                        <a href="submit-ann.php?id='.$p['id'].'&type=del" class="confirmation"><button type="button" class="btn btn-danger">'.Langs::translations[$_SESSION['lang']]["home"]["delAnn"].'</button></a>
                                      </div>';
                          } echo '
                        </div>
                        <div class="card-body" id="ann'.$p['id'].'">
                           <div align="right">
                              <b>'.Langs::translations[$_SESSION['lang']]["home"]["AnnBy"].'</b> <a href="user.php?id='.Utilities::getId($p['author']).'"><span style="color:black;">'.Utilities::getName($p['author']).'</span></a><br>
                              '.substr($p['date'],0,16).'
                           </div><br>
                         '.$p['text'].'
                        </div>
                      </div>
                   </div>';
          }

      }


      public function getStats($type) {
          global $conn;

          if($type == "owned") return mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `products` WHERE owner='".addslashes($_SESSION['login'])."';"));
          else if($type == "listed") return mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `operations` WHERE `from`='".addslashes($_SESSION['login'])."';"));
          else if($type == "toRec") return mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `operations` WHERE `to`='".addslashes($_SESSION['login'])."';"));
          else return Langs::translations[$_SESSION['lang']]["errors"]["badRequest"];
      }

  }

?>
