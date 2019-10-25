<?php
 /*
   FILE: forum.php
   DESCRIPTION: Foodie forum
 */
 include("src/base.php");
 include("src/funcs/forum.php");

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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["forum"]; ?></li>
                                        </ul>
                                    </div>
                                    <button class="au-btn au-btn-icon au-btn--green" onclick="showNewThreadModal()">
                                      <i class="zmdi zmdi-plus"></i><?php echo Langs::translations[$_SESSION['lang']]["forum"]["newThread"]; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->


            <!-- List all threads -->
            <section>
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12"><br>

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["forum"]["threadsList"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                   <?php Forum::listThreads(); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal for adding new thread -->
            <div id="newThreadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newThreadModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title" id="newThreadModalLabel"><?php echo Langs::translations[$_SESSION['lang']]["forum"]["newThread"]; ?></h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="submit-forum.php?type=newThread" id="newThreadForm">

                              <b><?php echo Langs::translations[$_SESSION['lang']]["forum"]["tableTitle"]; ?></b><br>
                              <input type="text" class="form-control" name="title" required><br>
                              <b><?php echo Langs::translations[$_SESSION['lang']]["forum"]["threadText"]; ?></b><br>
                              <textarea id="editor" name="text" required></textarea><br><br><br>

                              <div align="right">
                                 <button type="submit" onclick="document.getElementById('newThreadForm').submit()" name="add" class="btn btn-success"><?php echo Langs::translations[$_SESSION['lang']]["home"]["addAnn"]; ?></button>
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


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>

</body>

</html>
