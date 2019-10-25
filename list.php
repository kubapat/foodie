<?php
 /*
   FILE: list.php
   DESCRIPTION: Foodie available food list
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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["list"]; ?></li>
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
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["list"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                  <div class="card-body">
                                       <div class="table-responsive">
                                           <table class="dataTable stripe hover order-column row-border" id="products" width="100%">
                                             <thead>
                                                <tr>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["storage"]["name"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["storage"]["bestBefore"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["profile"]["user"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["storage"]["description"]; ?></th>
                                                </tr>
                                             </thead>
                                           </table>
                                       </div>
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


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>


    <!-- Download products by AJAX -->
    <script>
      $(document).ready(function() {
         $('#products').DataTable( {
            "ajax": 'ajaxData.php'
         } );
      } );
    </script>

</body>

</html>
