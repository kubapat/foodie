<?php
 /*
   FILE: admin.php
   DESCRIPTION: Foodie Admin (currently only basic user managment)
 */
 include("src/base.php");
 include("src/funcs/admin.php");

 if(!$_SESSION['logged']) { //isUserLogged
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

 if($_SESSION['type'] != 'admin') { //isUserAdmin
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["noPrivs"];
    $_SESSION['errcolor'] = 'error';
    header('Location: index.php');
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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["admin"]; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->


            <!-- User managment section -->
            <section>
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12"><br>

                                <div class="card">
                                   <div class="card-header">
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["admin"]; ?></h3>
                                   </div>
                                </div>

                                <div class="card">
                                    <div class="card-body table-responsive">
                                         <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["profile"]["name"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["profile"]["email"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["forum"]["lastActive"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["admin"]["registered"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["header"]["language"]; ?></th>
                                                  <th>IP</th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["admin"]["privs"]; ?></th>
                                                  <th><?php echo Langs::translations[$_SESSION['lang']]["admin"]["ops"]; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php Admin::genUserList(); ?>
                                            </tbody>
                                         </table>
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

</body>

</html>
