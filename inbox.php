<?php
 /*
   FILE: inbox.php
   DESCRIPTION: Foodie messaging system
 */
 include("src/base.php");
 include("src/funcs/mess.php");

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
                                            <li class="list-inline-item"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["inbox"]; ?></li>
                                        </ul>
                                    </div>
                                    <button class="au-btn au-btn-icon au-btn--green" onclick="showNewMessModal()">
                                      <i class="zmdi zmdi-plus"></i><?php echo Langs::translations[$_SESSION['lang']]["mess"]["NewMess"]; ?>
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
                                      <h3 class="card-title"><?php echo Langs::translations[$_SESSION['lang']]["menu"]["inbox"]; ?></h3>
                                   </div>
                                </div>

                                <!-- Side-menu for messages selection (Inbox or Sent) -->

                                <div class="row">
                                  <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                     <div class="card">
                                       <div class="card-body">
                                         <table class="table table-bordered">
                                           <tbody>
                                             <tr>
                                               <td><a href="#" onclick="switchTab('inbox')"><span style="color:black;"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["inbox"]; ?></span></a></td>
                                             </tr>
                                             <tr>
                                               <td><a href="#" onclick="switchTab('sent')"><span style="color:black;"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["sent"]; ?></span></a></td>
                                             </tr>
                                           </tbody>
                                         </table>
                                       </div>
                                     </div>
                                  </div>

                                  <div class="col-lg-10 col-md-9 col-sm-6 col-12">

                                     <div class="card" id="inbox"> <!-- Inbox -->
                                        <?php Messages::listMess("inbox"); ?>
                                     </div>

                                     <div class="card" id="sent" style="display:none;"> <!-- Sent -->
                                        <?php Messages::listMess("sent"); ?>
                                     </div>

                                  </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Modal for new message -->
            <div id="newMessModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newMessModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title" id="newMessModalLabel"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["NewMess"]; ?></h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="submit-mess.php?type=new" id="newMessForm">
                              <b><?php echo Langs::translations[$_SESSION['lang']]["mess"]["recipient"]; ?>:</b><br>
                              <input type="text" class="form-control" id="search" name="search" Placeholder="">

                              <div id="result">
                              </div><br>

                              <b><?php echo Langs::translations[$_SESSION['lang']]["mess"]["subject"]; ?></b><br>
                              <input type="text" class="form-control" name="title" required><br>
                              <b><?php echo Langs::translations[$_SESSION['lang']]["forum"]["threadText"]; ?></b><br>
                              <textarea id="editor" name="text" required></textarea><br><br><br>

                              <div align="right">
                                 <button type="submit" onclick="document.getElementById('newMessForm').submit()" name="send" class="btn btn-success"><?php echo Langs::translations[$_SESSION['lang']]["mess"]["send"]; ?></button>
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

    <script>

     <!-- download matching user list by AJAX -->
     $("#search").keyup(function() {
         var form = $("#newMessForm");
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

      <!-- Switch between inbox & sent tabs -->
      function switchTab(type) {
         var inbox = document.getElementById('inbox');
         var sent  = document.getElementById('sent');

         if(type == 'inbox') {
             inbox.style.display = 'block';
             sent.style.display  = 'none';
         } else if(type == 'sent') {
             inbox.style.display = 'none';
             sent.style.display  = 'block';
         }
      }

    </script>


    <?php

      if($_SESSION['type'] == 'admin') {
         echo '<script src="js/admin.js"></script>';
      }
    ?>

</body>

</html>
