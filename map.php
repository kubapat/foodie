<?php
 /*
   FILE: map.php
   DESCRIPTION: Foodie Map view of owners
 */
 include("src/base.php");
 include("src/funcs/products.php");

 if(!$_SESSION['logged']) {
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["mapAvail"]; ?></li>
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
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["mapAvail"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-body">
                                        <div id="map" style="width:100%; height:500px;"></div><br>
                                        <input type="hidden" id="location" name="location" value=""/>
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
       var centerOfMap = new google.maps.LatLng(52.84254657285949, 18.714219774433218);
       var infowindow = new google.maps.InfoWindow();

       var options = {
         center: centerOfMap,
         zoom: 6
       };

       <?php Products::genMarkersArray(); ?>

       map = new google.maps.Map(document.getElementById('map'), options);
       var marker;

       for(var i=0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: {lat: locations[i][1], lng: locations[i][2]},
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
               return function() {
                 infowindow.setContent('<b>'+locations[i][0]+'</b><br><a href="'+locations[i][3]+'"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["clickToSee"]; ?></a>');
                 infowindow.open(map, marker);
               }
            })(marker, i));
       }
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
