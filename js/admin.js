
    function showAnnEditMenu(elem) {
             var param = "";
             if(document.getElementById("ann"+elem).innerHTML.indexOf("textarea") !== -1) param = "cancel";
             else param="edit";

             $.ajax({
                url: "refreshers/getAnnEditForm.php?id="+elem+"&type="+param,
                type: "POST",
                processData: false,
                data: {},
                cache: false,
                contentType: false,
                success: function(data) {
                  if(data.indexOf("code-1") >= 0) alert("Insufficient privs");
                  else if(data.indexOf("code-2") >= 0) alert("No announcement with ID="+elem);
                  else if(data.indexOf("code-3") >= 0) alert("Wrong request param");
                  else {
                    $("#ann"+elem).html(data);
                    ClassicEditor
                       .create( document.querySelector( '#editor' ) )
                       .then( editor => {
                           console.log( editor );
                       } )
                       .catch( error => {
                           console.error( error );
                       } );
                  }
                },
                error: function (jXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
              });
    }


    function showNewAnnModal() {
             $('#newAnnModal').modal('show');
    }
