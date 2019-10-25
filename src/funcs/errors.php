<?php
/*
  FILE: src/funcs/errors.php
  DESCRIPTION: All operations related with error outputing

  Methods:
   - displayError() - displays error contained in $_SESSION array

  Variables:
    - NONE
*/


  class Errors {
     public function displayError() {
        if(!isset($_SESSION['error']) || !isset($_SESSION['errcolor'])) return;

        echo '<script>
                 swal("","'.$_SESSION['error'].'", "'.$_SESSION['errcolor'].'");
              </script>';

        unset($_SESSION['error']);
        unset($_SESSION['errcolor']);
     }

  }

?>
