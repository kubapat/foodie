<?php
/*
  FILE: register.php
  DESCRIPTION: Register/Sign up page
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
    <title><?php echo Langs::translations[$_SESSION['lang']]["login"]["register"]; ?></title>
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
                        <div class="login-form"  style="overflow:auto;">
                            <form action="sign-up.php" method="POST">
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["register"]["login"]; ?></label>
                                    <input class="au-input au-input--full" type="text" name="login" maxlength="100" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["profile"]["email"]; ?></label>
                                    <input class="au-input au-input--full" type="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["profile"]["name"]; ?></label>
                                    <input class="au-input au-input--full" type="text" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["login"]["PasswdTitle"]; ?></label>
                                    <input class="au-input au-input--full" type="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["profile"]["repeat"]; ?></label>
                                    <input class="au-input au-input--full" type="password" name="repeat" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo Langs::translations[$_SESSION['lang']]["profile"]["location"]; ?></label>
                                    <div id="map" style="width:100%; height:300px;"></div><br>
                                    <input type="hidden" id="location" name="location" value="52.84254657285949, 18.714219774433218"/>
                                </div>
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="RECAPTCHAPUBLICKEY"></div>
                                </div>

                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="agree" required><?php echo Langs::translations[$_SESSION['lang']]["register"]["agree"]; ?>
                                    </label>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" name="register" type="submit"><?php echo Langs::translations[$_SESSION['lang']]["login"]["register"]; ?></button>
                            </form>
                            <div class="register-link">
                                <p>
                                    <?php echo Langs::translations[$_SESSION['lang']]["register"]["havAcc"]; ?>
                                    <a href="login.php"><?php echo Langs::translations[$_SESSION['lang']]["login"]["LoginBtn"]; ?></a>
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

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script>
      function changeToSelected() {
          var value = document.getElementById('selectLang').value;
          window.location.href = 'register.php?lang='+value;
      }

     var map;

     function initMap() {
       var centerOfMap = new google.maps.LatLng(52.84254657285949, 18.714219774433218);

       var options = {
         center: centerOfMap,
         zoom: 6
       };

       map = new google.maps.Map(document.getElementById('map'), options);

       google.maps.event.addListener(map, 'click', function(event) {
          var clickedLocation = event.latLng;
          marker.setPosition(clickedLocation);
          markerLocation();
       });


       marker = new google.maps.Marker({
                position: {lat: 52.84254657285949, lng: 18.714219774433218},
                map: map,
                draggable: true //make it draggable
            });
            //Listen for drag events!
            google.maps.event.addListener(marker, 'dragend', function(event){
                markerLocation();
            });
     }

     function markerLocation(){
        var currentLocation = marker.getPosition();
        document.getElementById('location').value = currentLocation.lat()+", "+currentLocation.lng();
     }


     google.maps.event.addDomListener(window, 'load', initMap);
    </script>

</body>

</html>
