<?php
 /*
   FILE: profile.php
   DESCRIPTION: Foodie Profile page
 */
 include("src/base.php");

 if(!$_SESSION['logged']) {
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

 $userdata = mysqli_fetch_row(mysqli_query($conn,"SELECT * FROM `users` WHERE login='".addslashes($_SESSION['login'])."';"));
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">

<head>
    <?php echo Head::headContent; ?>

    <title>Foodie - Wasteless & Helpful foodsharing</title>
</head>

<body class="animsition">
    <div class="page-wrapper">

        <!-- MENU SIDEBAR-->
        <?php Sidebar::genSidebar("left"); ?>

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <!-- HEADER DESKTOP-->
            <?php Header::genHeader();
                  Sidebar::genSidebar("right"); ?>

            <!-- BREADCRUMB-->
            <section class="au-breadcrumb m-t-75">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <ul class="list-unstyled list-inline au-breadcrumb__list">
                                            <li class="list-inline-item active">
                                                <a href="index.php">Foodie</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["header"]["profile"]; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->


            <section>
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12"><br>

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["header"]["profile"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-body">
                                      <form method="POST" action="update-profile.php">
                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["name"]; ?></b><br>
                                        <input type="text" class="form-control" name="name" value="<?php echo $userdata[2]; ?>" rmaxlength="100" required><br>

                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["email"]; ?></b><br>
                                        <input type="email" class="form-control" name="email" value="<?php echo $userdata[3]; ?>" rmaxlength="100" required><br>

                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["photo"]; ?></b><br>
                                        <input type="url" class="form-control" name="img" value="<?php echo $userdata[13]; ?>" maxlength="1000"><br>

                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["location"]; ?></b><br>

                                        <div id="map" style="width:100%; height:300px;"></div><br>
                                        <input type="hidden" id="location" name="location" value="<?php echo $userdata[11]; ?>"/>

                                        <div align="right">
                                           <button type="submit" class="btn btn-info" name="edit"><?php echo Langs::translations[$_SESSION['lang']]["home"]["editAnn"]; ?></button>
                                        </div>
                                      </form>
                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-header">
                                      <h4 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["profile"]["changeIt"]; ?></h4>
                                   </div>
                                   <div class="card-body">
                                      <form method="POST" action="update-profile.php?type=passwd">
                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["oldpass"]; ?></b><br>
                                        <input type="password" class="form-control" name="oldpass" required><br>

                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["newpass"]; ?></b><br>
                                        <input type="password" class="form-control" name="newpass" required><br>

                                        <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["repeat"]; ?></b><br>
                                        <input type="password" class="form-control" name="repeat" required><br>

                                        <div align="right">
                                           <button type="submit" class="btn btn-info" name="change"><?php echo Langs::translations[$_SESSION['lang']]["profile"]["change"]; ?></button>
                                        </div>
                                      </form>
                                   </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php Footer::genFooter(); ?>
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php
      Scripts::printScripts();
      Errors::displayError();
    ?>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <script>
     var map;

     function initMap() {
       var centerOfMap = new google.maps.LatLng(<?php echo $userdata[11]; ?>);

       var options = {
         center: centerOfMap,
         zoom: 15
       };

       map = new google.maps.Map(document.getElementById('map'), options);

       google.maps.event.addListener(map, 'click', function(event) {
          var clickedLocation = event.latLng;
          marker.setPosition(clickedLocation);
          markerLocation();
       });


       marker = new google.maps.Marker({
                position: {lat: <?php  echo Utilities::getLatLng($userdata[11],"lat"); ?>, lng: <?php  echo Utilities::getLatLng($userdata[11],"lng"); ?>},
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


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>

</body>

</html>
