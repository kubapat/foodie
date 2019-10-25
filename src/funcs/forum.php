<?php
/*
  FILE: src/funcs/forum.php
  DESCRIPTION: Takes all actions with forum

  Methods:
   - listThreads() - list all Threads at forum main page
   - displayThread($id) - displays thread with ID=$id
   - listReplies($id) - lists all replies to thread with ID=$id
   - increaseSeenCounter($id) - increments seen field by one for thread with ID=$id

  Variables:
   - NONE
*/

  class Forum {


     public function listThreads() {
         global $conn;

         $i=1;
         $proc = array("-1");

         $threads = mysqli_query($conn,"SELECT DISTINCT thread FROM forum ORDER BY date DESC;");

         if(!mysqli_num_rows($threads)) {
             echo '<div class="card-header">
                     <h5 class="card-title">'.Langs::translations[$_SESSION['lang']]["forum"]["noThreads"].'</h5>
                   </div>';
             return;
         }

         echo '<div class="table-responsive">
                 <table class="table table-hover">
                   <thead>
                      <tr>
                        <th>ID</th>
                        <th>'.Langs::translations[$_SESSION['lang']]["forum"]["tableTitle"].'</th>
                        <th>'.Langs::translations[$_SESSION['lang']]["forum"]["tableLast"].'</th>
                        <th>'.Langs::translations[$_SESSION['lang']]["forum"]["tableAuthor"].'</th>
                        <th>'.Langs::translations[$_SESSION['lang']]["forum"]["tableReply"].'</th>
                     </tr>
                   </thead>
                   <tbody>';

         while($t = mysqli_fetch_assoc($threads)) {
            $thread = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM `forum` WHERE id='".addslashes($t['thread'])."';"));
            $last   = mysqli_fetch_row(mysqli_query($conn,"SELECT `date` FROM `forum` WHERE thread='".addslashes($t['thread'])."' ORDER BY date DESC LIMIT 1;"))[0];
            $reply  = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `forum` WHERE thread='".addslashes($t['thread'])."' AND id!=thread;"));

            echo '<tr>
                    <td>'.$t['thread'].'</td>
                    <td><a href="thread.php?id='.$thread[0].'"><span style="color:black;">'.$thread[1].'</span></a></td>
                    <td>'.$last.'</td>
                    <td><a href="user.php?id='.Utilities::getId($thread[3]).'"><span style="color:black;">'.Utilities::getName($thread[3]).'</span></a></td>
                    <td>'.$reply.'</td>
                  </tr>';
         }

         echo     '</tbody>
                </table>
             </div>';


     }


     public function displayThread($id) {
        global $conn;
        $data = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM `forum` WHERE id='".addslashes($id)."';"));

        echo '<div class="card">
                <div class="card-body">
                   <div align="right">
                     '.Langs::translations[$_SESSION['lang']]["forum"]["threadBy"].' '.$data[4].' '.Langs::translations[$_SESSION['lang']]["forum"]["byWord"].'
                     <a href="user.php?id='.Utilities::getId($data[3]).'"><span style="color:black;">'.Utilities::getName($data[3]).'</span></a>
                     <span class="badge badge-success">'.Langs::translations[$_SESSION['lang']]["forum"]["lastActive"].': '.Utilities::getLastActivity($data[3]).'</span>';

                       if($_SESSION['type'] == 'admin' || $data[3] == $_SESSION['login']) {
                          echo '<br>
                                 <a href="submit-forum.php?id='.$id.'&type=del"><button type="button" class="btn btn-danger">'.Langs::translations[$_SESSION['lang']]["home"]["delAnn"].'</button></a>';
                       }

                       echo '
                   </div>

                   '.$data[2].'
               </div>
             </div>';
     }

     public function listReplies($id) {
        global $conn;
        $that = get_called_class();
        $replies = mysqli_query($conn,"SELECT * FROM forum WHERE id!=thread AND thread='".addslashes($id)."' ORDER BY date ASC;");

        if(!mysqli_num_rows($replies)) {
            echo '<div class="card">
                    <div class="card-header">
                      <h5 class="card-title">'.Langs::translations[$_SESSION['lang']]["forum"]["beFirst"].'</h5>
                    </div>
                  </div>';
            return;
        }

        while($r = mysqli_fetch_assoc($replies)) {
            echo '<br>';
            $that::displayThread($r['id']);
            echo '<br>';
        }
     }

     public function increaseSeenCounter($id) {
         global $conn;
         mysqli_query($conn,"UPDATE forum SET seen=seen+1 WHERE id='".addslashes($id)."';");
     }



  }

?>
