<?php
/*
  FILE: src/funcs/mess.php
  DESCRIPTION: Takes all actions with messages system

  Methods:
   - listMess() - list all Messages at forum main page
   - displayThread($id) - displays messages with ID=$id
   - listReplies($id) - lists all replies to message with ID=$id
   - makeSeen($id) - makes seen parameter equal to '1' for current message

  Variables:
   - NONE
*/

  class Messages {


     public function listMess($type) {
         global $conn;

         $i=1;
         $proc = array("-1");

         if($type == "inbox") $mess = mysqli_query($conn,"SELECT DISTINCT response FROM messages WHERE recipient='".addslashes($_SESSION['login'])."' ORDER BY date DESC;");
         else if($type == "sent") $mess = mysqli_query($conn,"SELECT DISTINCT response, seen FROM messages WHERE sender='".addslashes($_SESSION['login'])."' ORDER BY date DESC;");

         if(!mysqli_num_rows($mess)) {
             echo '<div class="card-header">
                     <h5 class="card-title">'.Langs::translations[$_SESSION['lang']]["mess"]["noMess"].'</h5>
                   </div>';
             return;
         }

         echo '<div class="table-responsive">
                 <table class="table table-hover">
                   <thead>
                      <tr>
                        <th>'.Langs::translations[$_SESSION['lang']]["mess"]["subject"].'</th>
                        <th>'.Langs::translations[$_SESSION['lang']]["mess"]["lastMess"].'</th>';
                        if($type == "inbox") {
                          echo '<th>'.Langs::translations[$_SESSION['lang']]["mess"]["sender"].'</th>
                                <th>'.Langs::translations[$_SESSION['lang']]["mess"]["messIn"].'</th>';
                        } else {
                          echo '<th>'.Langs::translations[$_SESSION['lang']]["mess"]["recipient"].'</th>
                                <th>'.Langs::translations[$_SESSION['lang']]["mess"]["read"].'</th>';
                        }
                        echo '
                     </tr>
                   </thead>
                   <tbody>';

         while($t = mysqli_fetch_assoc($mess)) {
            $thread = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM `messages` WHERE id='".addslashes($t['response'])."';"));
            $last   = mysqli_fetch_row(mysqli_query($conn,"SELECT `date` FROM `messages` WHERE response='".addslashes($t['response'])."' ORDER BY date DESC LIMIT 1;"))[0];
            $reply  = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `messages` WHERE response='".addslashes($t['response'])."' AND id!=response;"));

            if($thread[1] == $_SESSION['login']) $other = $thread[2];
            else $other = $thread[1];

            if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM messages WHERE seen='0' AND response='".addslashes($thread[0])."' AND recipient='".addslashes($_SESSION['login'])."';"))) $unseen = true;
            else $unseen = false;

            echo '<tr>
                    <td><a href="mess.php?id='.$thread[0].'">';
                    if($unseen && $type == 'inbox') echo '<b>';
                    echo '<span style="color:black;">'.$thread[3].'</span>';
                    if($unseen && $type == 'inbox') echo '</b>';
                    echo '</a></td>
                    <td>'.$last.'</td>
                    <td><a href="user.php?id='.Utilities::getId($other).'"><span style="color:black;">'.Utilities::getName($other).'</span></a></td>
                    <td>'.($type == "inbox" ? $reply : ($t['seen'] ? Langs::translations[$_SESSION['lang']]["mess"]["yes"] : Langs::translations[$_SESSION['lang']]["mess"]["no"])).'</td>
                  </tr>';
         }

         echo     '</tbody>
                </table>
             </div>';


     }


     public function displayThread($id) {
        global $conn;
        $data = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM `messages` WHERE id='".addslashes($id)."';"));

        echo '<div class="card">
                <div class="card-body">
                   <div align="right">
                     '.$data[5].' '.Langs::translations[$_SESSION['lang']]["forum"]["byWord"].'
                     <a href="user.php?id='.Utilities::getId($data[2]).'"><span style="color:black;">'.Utilities::getName($data[2]).'</span></a>
                     <span class="badge badge-success">'.Langs::translations[$_SESSION['lang']]["forum"]["lastActive"].': '.Utilities::getLastActivity($data[2]).'</span>
                   </div>

                   '.$data[4].'
               </div>
             </div>';
     }

     public function listReplies($id) {
        global $conn;
        $that = get_called_class();
        $replies = mysqli_query($conn,"SELECT * FROM messages WHERE id!=response AND response='".addslashes($id)."' ORDER BY date ASC;");

        while($r = mysqli_fetch_assoc($replies)) {
            echo '<br>';
            $that::displayThread($r['id']);
            echo '<br>';
        }
     }

     public function makeSeen($id) {
         global $conn;
         mysqli_query($conn,"UPDATE messages SET seen='1' WHERE response='".addslashes($id)."' AND recipient='".addslashes($_SESSION['login'])."';");
     }



  }

?>
