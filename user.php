<?php
 /*
   FILE: user.php
   DESCRIPTION: Foodie User page
 */
 include("src/base.php");
 include("src/funcs/products.php");

 if(!$_SESSION['logged']) {
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

 $id = addslashes($_GET['id']);
 $userdata = mysqli_query($conn,"SELECT * FROM users WHERE id='".$id."';");

 if(!mysqli_num_rows($userdata)) { //Does user exist?
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["userNotFound"];
    $_SESSION['errcolor'] = 'error';
    header('Location: index.php');
    return;
 }

 $userdata = mysqli_fetch_row($userdata);
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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["profile"]["user"]; ?></li>
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
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["profile"]["user"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-body">
                                        <div class="row">
                                           <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                               <img src="<?php echo $userdata[13]; ?>" alt="">
                                           </div>
                                           <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                               <b><?php echo Langs::translations[$_SESSION['lang']]["profile"]["name"]; ?>:</b>
                                               <?php echo $userdata[2]; ?><br>
                                               <span class="badge badge-success"><?php echo Langs::translations[$_SESSION['lang']]["forum"]["lastActive"].': '.$userdata[8]; ?></span><br>
                                               <b><?php echo Langs::translations[$_SESSION['lang']]["header"]["language"].'</b>: '.Langs::languages[$userdata[10]]; ?><br>
                                               <div id="map" style="width:100%; height:300px;"></div><br>
                                           </div>
                                        </div>

                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-header">
                                       <h5 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["sendReq"]; ?></h5>
                                   </div>
                                   <div class="card-body">
                                       <form method="POST" action="submit-mess.php?type=new">
                                          <input type="hidden" name="recipient" value="<?php echo $userdata[1]; ?>"/>
                                          <input type="hidden" name="title" value="<?php echo Langs::translations[$_SESSION['lang']]["mess"]["foodInq"]; ?>"/>
                                          <b><?php echo Langs::translations[$_SESSION['lang']]["forum"]["threadText"]; ?></b><br>
                                          <textarea id="editor" name="text"></textarea><br>

                                          <div align="right">
                                             <button type="submit" class="btn btn-success" name="send"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["send"]; ?></button>
                                          </div>

                                       </form>
                                   </div>
                                </div>

                                <div class="card">
                                   <div class="card-header">
                                      <h4 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["statusOpen"]; ?></h4>
                                   </div>
                                   <div class="card-body">
                                      <?php Products::listProductsToGive($userdata[1]); ?>
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
                map: map
            });
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
