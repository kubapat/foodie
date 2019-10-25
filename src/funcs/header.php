<?php
/*
  FILE: src/funcs/header.php
  DESCRIPTION: Takes all operations related with <header> section

  Methods:
   - arePendingNotifs() - returns number of pending notifications for user
   - genHeader() - generates <header> section
   - returnNotifs() - lists all/unseen notifications

  Variables:
   - NONE
*/


  class Header {

     private function arePendingNotifs() {
        global $conn;
        return mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `notifications` WHERE recipient='".addslashes($_SESSION['login'])."' AND seen='0';"));
     }

     public function returnNotifs($type) {
        global $conn;

        if($type == "unseen") $notifs = mysqli_query($conn,"SELECT * FROM `notifications` WHERE recipient='".addslashes($_SESSION['login'])."' AND seen='0' ORDER BY date DESC;");
        else if($type == "all") {
            $notifs = mysqli_query($conn,"SELECT * FROM `notifications` WHERE recipient='".addslashes($_SESSION['login'])."' ORDER BY date DESC;");
            $num = mysqli_num_rows($notifs);
            if($num) {
               echo '<div class="table-responsive">
                       <table class="table table-bordered">
                          <tbody>';
            }
        }

        while($n = mysqli_fetch_assoc($notifs)) {
            if($type == "unseen") {
               echo '<div class="notifi__item">
                       <div class="bg-c1 img-cir img-40">
                          <i class="zmdi zmdi-info-outline"></i>
                        </div>
                       <div class="content">
                         <p>'.Langs::translations[$_SESSION['lang']]["notifications"][$n['text']].'</p>
                         <span class="date">'.$n['date'].'</span>
                       </div>
                     </div>';
            } else if($type == "all") {
               echo '<tr>
                      <td>'.$n['text'].'</td>
                      <td>'.$n['date'].'</td>
                     </tr>';
            }

        }

        if($type == "all" && $num) {
            echo '</tbody>
                </table>
               </div>';
        }

     }

     public function genHeader() {
        $that = get_called_class();
        $num  = $that::arePendingNotifs();
        echo '
          <header class="header-desktop2">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap2">
                            <div class="logo d-block d-lg-none">
                                <a href="index.php">
                                    <img src="images/icon/logo-white.png" alt="Foodie" />
                                </a>
                            </div>
                            <div class="header-button2">
                                <div class="header-button-item js-item-menu '.($num ? 'has-noti' : '').'" id="notifs">
                                    <i class="zmdi zmdi-notifications" onclick="refreshNotifs()"></i>
                                    <div class="notifi-dropdown js-dropdown">
                                        <div class="notifi__title">
                                            <p>'.Langs::translations[$_SESSION['lang']]["header"]["newNotifications"].' '.$num.'</p>
                                        </div>';
                                         $that::returnNotifs("unseen");
                                        echo '
                                        <div class="notifi__footer">
                                            <a href="notifications.php">'.Langs::translations[$_SESSION['lang']]["header"]["allNotifications"].'</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-button-item mr-0 js-sidebar-btn">
                                    <i class="zmdi zmdi-menu"></i>
                                </div>
                                <div class="setting-menu js-right-sidebar d-none d-lg-block">
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="profile.php">
                                                <i class="zmdi zmdi-account"></i>'.Langs::translations[$_SESSION['lang']]["header"]["profile"].'</a>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="#LanguageModal" data-toggle="modal" data-target="#LanguageModal" onclick="$(\'#LanguageModal\').modal(\'show\');">
                                                <i class="zmdi zmdi-globe"></i>'.Langs::translations[$_SESSION['lang']]["header"]["language"].'</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
     }


  }


?>
