<?php
/*
  FILE: src/funcs/admin.php
  DESCRIPTION: Manages all visual operations with admin.php

  Methods:
    - genUserList() - generates user list

  Variables:
    - NONE
*/


  class Admin {

     public function genUserList() {
        global $conn;
        $users  = mysqli_query($conn,"SELECT * FROM users ORDER BY name ASC;");
        $admins = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `users` WHERE `privs`='admin';"));

        while($u = mysqli_fetch_assoc($users)) { //List Users
            echo '<tr>
                    <td>'.$u['name'].' ('.$u['login'].')</td>
                    <td><a href="mailto:'.$u['email'].'"><span style="color:black;">'.$u['email'].'</span></a></td>
                    <td><span class="badge badge-success">'.$u['last_active'].'</span></td>
                    <td>'.$u['registered'].'</td>
                    <td>'.Langs::languages[$u['language']].'</td>
                    <td>'.$u['ip'].'</td>
                    <td>'.$u['privs'].'</td>
                    <td>';
                      if($u['privs'] == 'user' || ($u['privs'] == 'admin' && $admin != 1)) { //To omit no admins situation
                        echo '<a href="change-user-status.php?type=privs&user='.$u['id'].'">
                                <button type="button" class="btn btn-info">'.Langs::translations[$_SESSION['lang']]["admin"]["changePrivs"].'</button>
                              </a>&nbsp;

                              <a href="change-user-status.php?type=ban&user='.$u['id'].'">';
                                 if($u['banned'] == '1') echo '<button class="btn btn-success" type="button">'.Langs::translations[$_SESSION['lang']]["admin"]["unban"].'</button>';
                                 else echo '<button class="btn btn-danger" type="button">'.Langs::translations[$_SESSION['lang']]["admin"]["ban"].'</button>';
                        echo '</a>';
                      } else echo '<span style="color:red;">'.Langs::translations[$_SESSION['lang']]["admin"]["onlyAdmin"].'</span>';

                     echo '
                    </td>

                  </tr>';
        }

     } // ->



  }

?>
