<?php
 /*
   FILE: storage.php
   DESCRIPTION: My food in Foodie
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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["myFood"]; ?></li>
                                        </ul>
                                    </div>
                                    <button class="au-btn au-btn-icon au-btn--green" onclick="showNewProductModal()">
                                      <i class="zmdi zmdi-plus"></i><?php echo Langs::translations[$_SESSION['lang']]["storage"]["newFood"]; ?>
                                    </button>
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
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["myFood"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                  <?php Products::listPersonProducts($_SESSION['login'],"rest"); ?>
                                </div>

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["myOps"]; ?></h3>
                                   </div>
                                </div>


                                <div class="card">
                                  <?php Products::listUnfinishedOperations(); ?>
                                </div>

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["myUsed"]; ?></h3>
                                   </div>
                                </div>


                                <div class="card">
                                  <?php Products::listPersonProducts($_SESSION['login'],"done"); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal for new product -->

            <div id="newProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newProductModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title" id="newProductModalLabel"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["newFood"]; ?></h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="submit-product.php?type=add" id="newProductForm">

                              <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                    <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["quantity"]; ?></b><br>
                                    <input type="number" class="form-control" name="quantity" min="1" max="100" value="1" required><br>
                                </div>

                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                    <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["name"]; ?></b><br>
                                    <input type="text" class="form-control" name="name" maxlength="100" required><br>
                                </div>
                              </div>


                              <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["image"]; ?></b><br>
                              <input type="url" class="form-control" name="img" maxlength="1000"><br>

                              <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["bestBefore"]; ?></b><br>
                              <input type="text" class="form-control" name="bestBefore" maxlength="10"><br>

                              <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["status"]; ?></b><br>
                              <select class="form-control" name="status">
                                   <option value="hold"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["statusHold"]; ?></option>
                                   <option value="open"><?php echo Langs::translations[$_SESSION['lang']]["storage"]["statusOpen"]; ?></option>
                              </select>


                              <b><?php echo Langs::translations[$_SESSION['lang']]["storage"]["description"]; ?></b><br>
                              <textarea id="editor" name="description" Placeholder="<?php echo Langs::translations[$_SESSION['lang']]["storage"]["descrNote"]; ?>"></textarea><br><br><br>

                              <div align="right">
                                 <button type="submit" onclick="document.getElementById('newProductForm').submit()" name="add" class="btn btn-success"><?php echo Langs::translations[$_SESSION['lang']]["home"]["addAnn"]; ?></button>
                              </div>
                            </form>
                        </div>
                     </div>
                  </div>
             </div>


            <?php Footer::genFooter(); ?>
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php
      Scripts::printScripts();
      Errors::displayError();
    ?>

    <!-- Lists users from DB by AJAX -->
    <script>
     $("#search").keyup(function() {
         var form = $("#openFoodForm");
         var formData = new FormData(form[0]);

            $.ajax({
                url: "refreshers/showusers.php",
                type: "POST",
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                success: function(data) {
                  $("#result").html(data);
                },
                error: function (jXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

      });
    </script>


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>

</body>

</html>
