<?php
 /*
   FILE: index.php
   DESCRIPTION: Foodie mainpage when signed in
 */
 include("src/base.php");
 include("src/funcs/mainpage.php");

 if($_GET['logout'] == 'true') { //Logout user
     unset($_SESSION['name']);
     unset($_SESSION['login']);
     unset($_SESSION['type']);
     unset($_SESSION['logged']);
     header('Location: login.php');
     return;
 }

 if(!$_SESSION['logged']) { //isUserLogged
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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["home"]; ?></li>
                                        </ul>
                                    </div>
                                    <?php //New announcement modal
                                      if($_SESSION['type'] == 'admin') {
                                        echo '<button class="au-btn au-btn-icon au-btn--green" onclick="showNewAnnModal()">
                                                 <i class="zmdi zmdi-plus"></i>'.Langs::translations[$_SESSION['lang']]["home"]["newAnn"].'
                                              </button>';
                                      }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->

            <!-- STATISTICS-->
            <section class="statistic">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="statistic__item">
                                    <h2 class="number"><?php echo MainPage::getStats("owned"); ?></h2>
                                    <span class="desc"><?php echo Langs::translations[$_SESSION['lang']]["home"]["statsOne"]; ?></span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-account-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="statistic__item">
                                    <h2 class="number"><?php echo MainPage::getStats("listed"); ?></h2>
                                    <span class="desc"><?php echo Langs::translations[$_SESSION['lang']]["home"]["statsTwo"]; ?></span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="statistic__item">
                                    <h2 class="number"><?php echo MainPage::getStats("toRec"); ?></h2>
                                    <span class="desc"><?php echo Langs::translations[$_SESSION['lang']]["home"]["statsThr"]; ?></span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-assignment-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END STATISTIC-->

            <section>
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["home"]["annTitle"]; ?></h3>
                                   </div>
                                </div>

                            </div> <!-- Announcements list -->
                            <?php MainPage::listAnnouncements(); ?>
                        </div>
                    </div>
                </div>
            </section>


            <?php Footer::genFooter(); //Modal for announcement adding
                  echo MainPage::genNewAnnouncementModal(); ?>
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php
      Scripts::printScripts();
      Errors::displayError();
    ?>


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>

</body>

</html>
