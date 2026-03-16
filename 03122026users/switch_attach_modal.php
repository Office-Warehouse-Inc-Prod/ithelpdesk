<style>
    .modal-body {
    overflow-x: hidden;
}

.modal-dialog-scrollable {
  display: flex;
  flex-direction: column;
  max-height: 100vh;
}

.modal-dialog-scrollable .modal-content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.modal-dialog-scrollable .modal-body {
  overflow-y: auto;
  flex-grow: 1;
}

.modal-dialog-scrollable .modal-header {
  position: sticky;
  top: 0;
  z-index: 10;
  background-color: #fff;
}

.modal-dialog-scrollable .modal-footer {
  position: sticky;
  bottom: 0;
  z-index: 10;
  padding: 10px;
  background-color: #f0f0f0;
  border-top: 1px solid #ddd;
}

</style>

<!-- Modal -->
<div class="modal fade" id="ViewFile" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ViewFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ViewFileLabel">Attached Files</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body col-12">
        <input type="hidden" id="ticketxxx" name="ticketxxx">
      <div id="images" name="images"> </div>
      <hr>
 
      <div class="modal-footer">
      <div class="row justify-content-between">
                    <label style="font-weight: bold;">Attached File:</label>
                    <p>
                    <input id="fileinput" type="file" name="file" Multiple>
                    </p>
                    <button type="button" id="cls_view" name="cls_view" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="save_file" name="save_file"class="btn btn-primary">SAVE</button>
                    </div>

      </div>

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$('#save_file').click(function () { 
        var files = $('#fileinput')[0].files;
        var tktno = $('#ticketxxx').val();
        var formData = new FormData();


        for (var i = 0; i < files.length; i++) {
            formData.append('files[]',files[i]);
            
        }
        formData.append('ticket_no',tktno);


        $.ajax({
            type: "POST",
            url: "insertimg.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                // alert(response);
        $('#ViewFile').modal('hide');
        $('#ticket_modal').modal('show');
        

            }
        });
        
   


    });

//validition for file upload size
var uploadField = document.getElementById("fileinput");
var saveButton = document.getElementById('save_file');

saveButton.disabled = true;

uploadField.addEventListener('change', function() {
    var files = this.files;
    var isValidFile = false;

    if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileSize = file.size;

            if (fileSize < 2097152) {
                var ext = fileName.split('.').pop().toLowerCase();
                if ($.inArray(ext, ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls']) !== -1) {
                    isValidFile = true;
                } else {
                    alert("Invalid file extension");
                    this.value = "";
                    saveButton.disabled = true;
                    return false;
                }
            } else {
                alert("File must not exceed 2MB");
                this.value = "";
                saveButton.disabled = true;
                return false;
            }
        }
        if (isValidFile) {
            saveButton.disabled = false;
        }
    } else {
        saveButton.disabled = true;
    }
});
// end of validition for file upload size



$('#cls_view').click(function (e) { 
    e.preventDefault();
    $('#ViewFile').modal('hide');
    $('#ticket_modal').modal('show');

    
});




</script>
