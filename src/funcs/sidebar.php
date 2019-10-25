<?php
/*
  FILE: src/funcs/sidebar.php
  DESCRIPTION: Generates sidebar and all its derivatives

  Methods:
   - notSeenMess() - return number of not seen messages
   - genSidebar() - prints generated sidebar

  Variables:
   - global usage of src/content/connect.php $conn variable
*/

  class Sidebar {

     public function notSeenMess() {
        global $conn;
        return mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT response FROM messages WHERE recipient='".addslashes($_SESSION['login'])."' AND seen='0';"));
     }

     public function genSidebar($type) {
        $that = get_called_class();
        echo '<aside class="menu-sidebar2'.($type == "right" ? ' js-right-sidebar d-block d-lg-none' : '').'">
                <div class="logo">
                    <a href="index.php">
                      <img src="images/icon/logo-white.png" alt="Foodie Logo" />
                    </a>
                </div>
                <div class="menu-sidebar2__content js-scrollbar'.($type == "right" ? '2' : '1').'">
                  <div class="account2">
                    <div class="image img-cir img-120">
                        <img src="'.Utilities::getUserPhoto().'" alt="User Photo" />
                    </div>
                    <h4 class="name">'.$_SESSION['name'].'</h4>
                    <a href="index.php?logout=true">'.Langs::translations[$_SESSION['lang']]["menu"]["signOut"].'</a>
                  </div>
                  <nav class="navbar-sidebar2">
                      <ul class="list-unstyled navbar__list">
                          <li>
                              <a href="index.php">
                                 <i class="fas fa-home"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["home"].'
                              </a>
                          </li>

                          <li>
                              <a href="inbox.php">
                                  <i class="fas fa-envelope"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["inbox"].'
                                  <span class="inbox-num">'.$that::notSeenMess().'</span>
                              </a>
                          </li>

                          <li class="has-sub">
                              <a class="js-arrow" href="#">
                                <i class="fas fa-handshake-o"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["getFood"].'
                                <span class="arrow">
                                   <i class="fas fa-angle-down"></i>
                                </span>
                              </a>
                              <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                   <a href="map.php">
                                     <i class="fas fa-map-marker-alt"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["map"].'
                                   </a>
                                </li>
                                <li>
                                   <a href="list.php">
                                     <i class="fas fa-list-alt"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["list"].'
                                   </a>
                                </li>
                              </ul>
                          </li>

                          <li>
                              <a href="storage.php">
                                  <i class="fas fa-archive"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["myFood"].'
                              </a>
                          </li>

                          <li>
                              <a href="ranks.php">
                                  <i class="fas fa-trophy"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["ranks"].'
                              </a>
                          </li>

                          <li>
                              <a href="forum.php">
                                  <i class="fas fa-comments"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["forum"].'
                              </a>
                          </li>

                          <li>
                              <a href="info.php">
                                  <i class="fas fa-info-circle"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["info"].'
                              </a>
                          </li>';

                          if($_SESSION['type'] == 'admin') {
                              echo '<li>
                                      <a href="admin.php">
                                         <i class="fas fa-user"></i>'.Langs::translations[$_SESSION['lang']]["menu"]["admin"].'
                                      </a>
                                    </li>';
                          }

                          echo '

                      </ul>
                  </nav>
              </div>
          </aside>';
      }

  }

?>

