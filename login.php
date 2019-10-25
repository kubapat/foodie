<?php
/*
  FILE: login.php
  DESCRIPTION: Login Page
*/
  include("src/base.php");

  if($_SESSION['logged']) {
      header('Location: index.php');
      return;
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">

<head>
    <?php echo Head::headContent; ?>
    <title><?php echo Langs::translations[$_SESSION['lang']]["login"]["PageTitle"]; ?></title>
</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5" style="overflow:auto;">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div align="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-6 offset-lg-8 offset-md-8 offset-sm-6 offset-6">
                               <select class="form-control" onchange="changeToSelected();" id="selectLang">
                                 <?php
                                   foreach(Langs::languages as $key => $name) {
                                     echo '<option value="'.$key.'"';
                                     if($key == $_SESSION['lang']) echo ' selected="selected"';
                                     echo '>'.$name.'</option>';
                                   }
                                 ?>
                               </select>
                            </div>
                        </div>
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo-white.png" alt="Foodie logo">
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="sign-in.php" method="POST">
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["login"]["loginTitle"]; ?></label>
                                    <input class="au-input au-input--full" type="text" name="login" maxlength="100" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["login"]["PasswdTitle"]; ?></label>
                                    <input class="au-input au-input--full" type="password" maxlength="100" name="password" required>
                                </div>
                                <button name="loginBtn" class="au-btn au-btn--block au-btn--green m-b-20" type="submit"><?php echo Langs::translations[$_SESSION['lang']]["login"]["LoginBtn"]; ?></button>
                            </form>
                            <div class="register-link">
                                <p>
                                    <?php echo Langs::translations[$_SESSION['lang']]["login"]["notAccount"]; ?>
                                    <a href="register.php"><?php echo Langs::translations[$_SESSION['lang']]["login"]["register"]; ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php Scripts::printScripts();
          Errors::displayError(); ?>

    <script>
      function changeToSelected() {
          var value = document.getElementById('selectLang').value;
          window.location.href = 'login.php?lang='+value;
      }
    </script>

</body>

</html>
