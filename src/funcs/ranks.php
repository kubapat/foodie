<?php
/*
  FILE: src/funcs/ranks.php
  DESCRIPTION: This file contains functions and variables dealing with ranks.php file, so with Ranks mechanism

  Methods:
    - generateRank($type) - generates Ranks for $type = givers (those who create products in system and give it to others)
                                                      = delivery (those who can be between final goods receiver and giver, e.g. DHL is in chain between LIDL and charity organisation

  Variables:
    - NONE
*/

  class Ranks {

      public function generateRank($type) {
          global $conn;
          $people = mysqli_query($conn,"SELECT DISTINCT `from` FROM operations WHERE confirmed='1';");

          while($p = mysqli_fetch_assoc($people)) {
                 $scores[$p['from']] = 0;
                 $products = mysqli_query($conn,"SELECT DISTINCT `product` FROM `operations` WHERE `from`='".addslashes($p['from'])."' ORDER BY id ASC;");
                 while($p = mysqli_fetch_assoc($products)) {
                    $isUsers  = mysqli_query($conn,"SELECT * FROM products WHERE id='".addslashes($p['product'])."' AND `owner`='".addslashes($p['from'])."';");
                    if(mysqli_num_rows($isUsers)) continue;
                    $first    = mysqli_fetch_row(mysqli_query($conn,"SELECT `from` FROM operations WHERE product='".addslashes($p['product'])."' ORDER BY date ASC, id ASC LIMIT 1;"))[0];
                    if(($first == $p['from'] && $type == "givers") || ($first != $p['from'] && $type == "delivery")) $score[$p['from']]++;
                 }
          }

          asort($scores);

          $i=1;
          foreach($scores as $user => $score) {
              echo '<tr>
                      <td>'.$i.'.</td>
                      <td><a href="user.php?id='.Utilities::getId($user).'"><span style="color:black;">'.Utilities::getName($user).'</span></a></td>
                    </tr>';
              if($i == 50) break;
              $i++;
          }

      } //FUNCTION END


  }

?>
