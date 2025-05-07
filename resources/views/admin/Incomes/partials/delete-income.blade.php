<!-- Delete Income Modal -->
<div class="modal fade" id="deleteIncomeModal" tabindex="-1" aria-labelledby="deleteIncomeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteIncomeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteIncomeModalLabel">Delete Income</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete_income_name"></strong>?</p>
                    <input type="hidden" id="delete_income_id" name="income_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete Income</button>
                </div>
            </form>
        </div>
    </div>
</div>