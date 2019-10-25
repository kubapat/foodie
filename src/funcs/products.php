<?php
/*
  FILE: src/funcs/products.php
  DESCRIPTION: Takes operations with products listing and exchange

  Methods:
   - listPersonProducsts($arg1,$type) - lists products where owner = $arg1 and $type = {"used" - lists only with status="done", "rest" - lists all except with status="done"}
   - genMarkersArray() - generates JS array of owners with items available for pick-up (map.php)
   - listProductsToGive($user) - lists products of userLogin = $user available for pick-up
   - generateAjaxData() - generates simple JSON for list.php to make fast & efficient dataTable
   - listUnfinishedOperations() - lists all pending products forwardment operations that needs confimration

  Variables:
   - status - contains product statuses:
                - "hold" - dont publish for available pickup
                - "open" - available for pickup
                - "done" - used and no longer available for pickup (kinda archived)
*/


  class Products {

      public const status = array(
             "hold" => "statusHold",
             "open" => "statusOpen",
             "done" => "statusDone"
      );

      public function listPersonProducts($owner,$type) {
          global $conn;
          $that = get_called_class();

          if($type == "rest") $products = mysqli_query($conn,"SELECT * FROM products WHERE owner='".addslashes($owner)."' AND status='open';");
          else $products = mysqli_query($conn,"SELECT * FROM products WHERE owner='".addslashes($owner)."' AND (status='done' OR status='hold');");

          if(!mysqli_num_rows($products)) {
             echo '<div class="card-header">
                     <h4 class="card-title">'.Langs::translations[$_SESSION['lang']]["storage"]["noFood"].'</h4>
                   </div>';
             return;
          }


          echo '<div class="card-body">
                  <div class="table-responsive  table-responsive-data">
                    <form method="POST" action="submit-product.php?type=edit" id="openFoodForm">
                      <table class="table table-bordered table-condensed">
                         <thead>
                            <tr>';
                              if($type == "rest") {
                                echo '<th><input type="checkbox" id="all" name="all" onchange="matchAll(\'all\', \'productCheck\')"></th>';
                              }
                              echo '
                              <th>'.Langs::translations[$_SESSION['lang']]["storage"]["name"].'</th>
                              <th>'.Langs::translations[$_SESSION['lang']]["storage"]["bestBefore"].'</th>
                              <th>'.Langs::translations[$_SESSION['lang']]["storage"]["description"].'</th>
                              <th>'.Langs::translations[$_SESSION['lang']]["storage"]["status"].'</th>
                            </tr>
                         </thead>
                         <tbody>';

                         while($p = mysqli_fetch_assoc($products)) {
                           echo '<tr>';
                                    if($type == "rest") {
                                       echo '<td><input type="checkbox" name="productCheck'.$p['id'].'" id="productCheck'.$p['id'].'"></td>';
                                       $ops = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM operations WHERE product='".addslashes($p['id'])."' AND `from`='".addslashes($owner)."' AND confirmed='0';"));
                                    }
                                    echo '
                                    <td><input type="text" class="form-control" name="name'.$p['id'].'" maxlength="100" value="'.$p['name'].'" required></td>
                                    <td><input type="text" class="form-control" name="bestBefore'.$p['id'].'" maxlength="100" value="'.$p['bestBefore'].'"></td>
                                    <td>';
                                    if($p['image'] != 'NONE') echo '<img src="'.$p['image'].'" width="100px" alt=""/><br>';
                                    echo '<b>'.Langs::translations[$_SESSION['lang']]["home"]["image"].'</b><br>
                                          <input type="url" class="form-control" name="image'.$p['id'].'" value="'.($p['image'] == 'NONE' ? '' : $p['image']).'"><br><textarea class="editor" name="text'.$p['id'].'">'.$p['description'].'</textarea></td>
                                    <td><select class="form-control" name="status'.$p['id'].'">';

                                    foreach($that::status as $status => $key) { //List statuses
                                        echo '<option value="'.$status.'"';
                                        if($status == $p['status']) echo ' selected="selected"';
                                        echo '>'.Langs::translations[$_SESSION['lang']]["storage"][$key].'</option>';
                                    }
                                    echo '</select>';

                                    if($type == "rest" && $ops) {
                                        echo '<br><span style="color:red;">'.Langs::translations[$_SESSION['lang']]["storage"]["notEdited"].'</span><br>';
                                    }

                                    echo '</td>
                                 </tr>';

                         }

                         echo '</tbody>
                      </table><br>

                      <div align="right">
                         <button type="submit" name="edit" class="btn btn-success">'.Langs::translations[$_SESSION['lang']]["home"]["editAnn"].'</button>
                      </div>';

                      if($type == "rest") {
                        echo '<h4>'.Langs::translations[$_SESSION['lang']]["storage"]["giveFood"].'</h4>
                              <p>'.Langs::translations[$_SESSION['lang']]["storage"]["giveInstr"].'</p>
                              <b>'.Langs::translations[$_SESSION['lang']]["mess"]["recipient"].'</b><br>
                              <input type="text" class="form-control" id="search" name="search" Placeholder="">

                              <div id="result">
                              </div><br>

                              <button type="submit" name="forward" class="btn btn-info">'.Langs::translations[$_SESSION['lang']]["storage"]["giveBtn"].'</button>';
                      }
                      echo '
                     </form>
                  </div>
                </div>';

      }

      public function genMarkersArray() {
          global $conn;

          $people = mysqli_query($conn,"SELECT DISTINCT `owner` FROM `products` WHERE status='open';");
          echo 'var locations = [';
          $i=0;

          while($p = mysqli_fetch_assoc($people)) {
               $data = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM users WHERE login='".addslashes($p['owner'])."';"));
               if($i) echo ', ';
               echo '["'.$data[2].'", '.$data[11].', "user.php?id='.$data[0].'"]';
               $i++;
         }

         echo '];';

     }

     public function listProductsToGive($user) {
         global $conn;
         $products = mysqli_query($conn,"SELECT * FROM products WHERE owner='".addslashes($user)."' AND status='open' ORDER BY name ASC;");

         if(!mysqli_num_rows($products)) {
            echo '<h5>'.Langs::translations[$_SESSION['lang']]["storage"]["noFood"].'</h5>';
            return;
         }

         echo '<div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                     <tr>
                       <th>'.Langs::translations[$_SESSION['lang']]["storage"]["name"].'</th>
                       <th>'.Langs::translations[$_SESSION['lang']]["storage"]["bestBefore"].'</th>
                       <th>'.Langs::translations[$_SESSION['lang']]["storage"]["description"].'</th>
                     </tr>
                  </thead>
                  <tbody>';


         while($f = mysqli_fetch_assoc($products)) {
              echo '<tr id="product'.$f['id'].'">
                      <td>'.$f['name'].'</td>
                      <td>'.($f['bestBefore'] != "NONE" ? $f['bestBefore'] : '-').'</td>
                      <td>';
                      if($f['image'] != 'NONE') echo '<img src="'.$f['image'].'" alt="" width="200px"><br>';
                      echo $f['description'].'</td>
                    </tr>';

         }

         echo    '</tbody>
              </table>
            </div>';
     }

     public function generateAjaxData() {
         global $conn;
         $products = mysqli_query($conn,"SELECT * FROM products WHERE status='open' ORDER BY name ASC;");

         $json["data"] = array();
         while($f = mysqli_fetch_assoc($products)) {
              array_push($json["data"], array('<a href="user.php?id='.Utilities::getId($f['owner']).'#product'.$f['id'].'"><span style="color:black;">'.addslashes($f['name']).'</span></a>',
                                             ($f['bestBefore'] != "NONE" ? $f['bestBefore'] : '-'),
                                              '<a href="user.php?id='.Utilities::getId($f['owner']).'"><span style="color:black;">'.Utilities::getName($f['owner']).'</span></a>',
                                              addslashes($f['description'])));
         }

         echo json_encode($json);
     }

     public function listUnfinishedOperations() {
        global $conn;
        $ops = mysqli_query($conn,"SELECT * FROM `operations` WHERE (`from`='".addslashes($_SESSION['login'])."' OR `to`='".addslashes($_SESSION['login'])."') AND
                 confirmed='0' ORDER BY date DESC;");

        if(!mysqli_num_rows($ops)) {
            echo '<div class="card-header"><h5 class="card-title">'.Langs::translations[$_SESSION['lang']]["storage"]["noOps"].'</h5></div>';
            return;
        }

        echo '<div class="card-body">
              <form method="POST" action="submit-product.php?type=confirm">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th><input type="checkbox" name="all2" id="all2" onchange="matchAll(\'all2\',\'confirmCheck\')"></th>
                      <th>'.Langs::translations[$_SESSION['lang']]["storage"]["name"].'</th>
                      <th>'.Langs::translations[$_SESSION['lang']]["storage"]["relation"].'</th>
                    </tr>
                  </thead>
                  <tbody>';


         while($f = mysqli_fetch_assoc($ops)) {
              $data = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM products WHERE id='".addslashes($f['product'])."';"));
              echo '<tr>
                    <td><input type="checkbox" name="confirmCheck'.$f['id'].'" id="confirmCheck'.$f['id'].'"></td>
                    <td>'.$data[1].'</td>
                    <td>';
                    if($_SESSION['login'] == $f['to']) echo '<b>'.Langs::translations[$_SESSION['lang']]["storage"]["from"].'</b> <a href="user.php?id='.Utilities::getId($f['from']).'"><span style="color:black;">'.Utilities::getName($f['from']).'</span></a>';
                    else echo '<b>'.Langs::translations[$_SESSION['lang']]["storage"]["for"].'</b> <a href="user.php?id='.Utilities::getId($f['to']).'><span style="color:black;">'.Utilities::getName($f['to']).'</span></a>';
                    echo '</td>
                    </tr>';

         }

         echo    '</tbody>
              </table>
            </div>
            <p>'.Langs::translations[$_SESSION['lang']]["storage"]["checkOps"].'</p>
            <div align="right">
               <button type="submit" class="btn btn-success" name="confirm">'.Langs::translations[$_SESSION['lang']]["storage"]["execute"].'</button>
            </div>
            </form>
            </div>';
     }
  }

?>
