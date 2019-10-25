<?php
/*
  FILE: src/funcs/footer.php
  DESCRIPTION: All operations with footer section

  Methods:
   - genFooter() - generates footer

  Variables:
   - startYear - rangeBegin for date span in footer
*/

  class Footer {

     private const startYear = 2019;


     public function genFooter() {
        $that = get_called_class();

        echo '<section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Copyright © '.((int)$that::startYear == (int)date('Y') ? $that::startYear : $that::startYear."-".date('Y')).' by <a href="https://pojs.ii.uni.wroc.pl/~jpat" target="_blank">Jakub Patałuch</a>. All rights reserved. </p>
                            </div>
                        </div>
                    </div>
                </div>
              </section>


              <!-- Languages selection modal -->
              <div id="LanguageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="LanguageModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title" id="LanguageModalLabel">'.Langs::translations[$_SESSION['lang']]["header"]["selectLang"].'</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        </div>
                        <div class="modal-body">
                           <table class="table table-bordered">
                              <tbody>';

                              foreach(Langs::languages as $lang => $value) { //Print all languages
                                 echo '<tr><td><a href="?lang='.$lang.'"><span style="color:black;">'.$value.'</span></a></td></tr>';
                              }

                              echo '</tbody>
                            </table>
                        </div>
                     </div>
                  </div>
             </div>';
     }


  }

?>
