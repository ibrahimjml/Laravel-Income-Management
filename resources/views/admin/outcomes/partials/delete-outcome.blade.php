<div class="modal fade" id="deleteOutcomeModal" tabindex="-1" aria-labelledby="deleteOutcomeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteOutcomeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOutcomeModalLabel">{{__('message.Delete Outcome')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{__('message.Are you sure you want to delete')}} <strong id="delete_outcome_name"></strong>?</p>
                    <input type="hidden" id="delete_outcome_id" name="outcome_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">{{__('message.Delete Outcome')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>