<?php
 /*
   FILE: thread.php
   DESCRIPTION: Foodie forum thread
 */
 include("src/base.php");
 include("src/funcs/forum.php");

 if(!$_SESSION['logged']) {
     $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["loginToSee"];
     $_SESSION['errcolor'] = 'error';
     header('Location: login.php');
     return;
 }

 $id = $_GET['id'];
 $thread = mysqli_query($conn,"SELECT * FROM `forum` WHERE id='".addslashes($id)."';");

 if(!mysqli_num_rows($thread)) { //Does thread exist
    $_SESSION['error']    = Langs::translations[$_SESSION['lang']]["errors"]["thrNotExists"];
    $_SESSION['errcolor'] = 'error';
    header('Location: forum.php');
    return;
 }

 $thread = mysqli_fetch_row($thread);
 Forum::increaseSeenCounter($thread[0]);
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
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item"><?php echo $thread[1]; ?></li>
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
                                      <h3 class="card-title"><?php echo $thread[1]; ?></h3>
                                   </div>
                                </div>

                                <?php Forum::displayThread($thread[0]); ?>

                                <div class="card">
                                   <div class="card-header">
                                       <h5 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["forum"]["newPost"]; ?></h5>
                                   </div>
                                   <div class="card-body">
                                       <form method="POST" action="submit-forum.php?type=addReply&id=<?php echo $thread[0]; ?>">
                                          <b><?php echo Langs::translations[$_SESSION['lang']]["forum"]["threadText"]; ?></b><br>
                                          <textarea id="editor" name="text"></textarea><br>

                                          <div align="right">
                                             <button type="submit" class="btn btn-success" name="add"><?php echo Langs::translations[$_SESSION['lang']]["home"]["addAnn"]; ?></button>
                                          </div>

                                       </form>
                                   </div>
                                </div>

                                <?php Forum::listReplies($thread[0]); ?>

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

</body>

</html>
