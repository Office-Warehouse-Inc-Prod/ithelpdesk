<style>

#ViewFile .modal-dialog{
  max-width: 1100px; /* desktop width */
  margin: 1.25rem auto;
}

#ViewFile .modal-content{
  border-radius: 16px;
  border: 1px solid rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header with hierarchy */
#ViewFile .modal-header{
  background: linear-gradient(180deg, rgba(79,70,229,0.08), rgba(255,255,255,0));
  border-bottom: 1px solid rgba(0,0,0,0.08);
  padding: 16px 18px;
}

#ViewFile_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

/* Body spacing */
#ViewFile .modal-body{
  padding: 16px 18px;
}

/* Section cards inside modal */
.modal-section{
  background: rgba(255,255,255,0.75);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 8px 22px rgba(0,0,0,0.04);
}

/* Section title */
.modal-section-title{
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .6px;
  text-transform: uppercase;
  color: rgba(0,0,0,0.55);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.modal-section-title:before{
  content: "";
  width: 10px;
  height: 10px;
  border-radius: 3px;
  background: rgba(79,70,229,0.55);
}

/* Make inputs feel “premium” */
#ViewFile .form-control,
#ViewFile select,
#ViewFile textarea{
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.12);
  background: white;
}

#ViewFile label{
  font-weight: 700;
  font-size: 12px;
  letter-spacing: .4px;
  text-transform: uppercase;
  color: #213456;
}

/* Comment thread container: scrollable, not endless */
.container_remarks{
  max-height: 480px;
  overflow: auto;
  padding-right: 6px;
}

/* Thread message card look (works with your existing markup) */
#remarks_view .msg-item{
  border: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.90);
  border-radius: 14px;
  padding: 12px 12px;
  margin-bottom: 10px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

#remarks_view .msg-head{
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 6px;
}

#remarks_view .msg-name{
  font-weight: 800;
  font-size: 14px;
}

#remarks_view .msg-meta{
  font-size: 12px;
  color: rgba(0,0,0,0.55);
}

#remarks_view .msg-body{
  font-size: 13px;
  color: rgba(0,0,0,0.80);
  line-height: 1.35;
  white-space: pre-wrap;
}

/* Sticky footer actions (great on mobile) */
#ViewFile .modal-footer{
  border-top: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.92);
  position: sticky;
  bottom: 0;
  z-index: 5;
  padding: 12px 14px;
}


label {
  font-size: 11px;
  font-weight: 900;
  color: #e1ad01; 
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: #fcfcfc; !important; 
  border: black !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  resize: none !important; 
  margin-bottom: -10px;
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  background-color: #fcfcfc; !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #0a0a0a !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  background-color: #fcfcfc; !important;
}

.form-control,
.form-control-sm,
input.form-control,
select.form-control,
textarea.form-control {
  background: #fff !important;
  color: black !important;
  border-bottom: 1px solid #E1AD01 !important; 
}

.form-control:focus,
.form-control-sm:focus,
input.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
  box-shadow: 0 10px 18px rgba(17,24,39,.06);
  border-color: 2px solid rgba(114, 89, 21, 0.94) !important;
}

</style>

<!-- Modal -->
<div class="modal fade" id="ViewFile" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ViewFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header"
            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h5 class="modal-title font-weight-bold" id="createReportModalLabel"><i class="fa fa-file-image-o" aria-hidden="true"></i> Attached Files</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
              style="opacity: 0.8; outline: none; background: none; border: none;">
              <span aria-hidden="true" style="font-size: 28px;">&times;</span>
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
        $('#ViewFile').modal('show');
        

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
    $('#ViewFile').modal('show');

    
});




</script>
