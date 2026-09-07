<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
       <form id="pdfForm" action="print_form.php" method="POST">
        <input type="hidden" name="ticket_no" id="modal_ticket_no">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Fixed Asset Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text"
                               class="form-control"
                               name="desc"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Serial Number</label>
                        <input type="text"
                               class="form-control"
                               name="serial"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Asset Tag</label>
                        <input type="text"
                               class="form-control"
                               name="asset_tag"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Generate PDF
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>
