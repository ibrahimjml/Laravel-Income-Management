  <!-- Delete Client Modal -->
  <div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="deleteClientForm">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteClientModalLabel">Delete Client</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Are you sure you want to delete <strong id="delete_client_name"></strong>?</p>
                  <input type="hidden" id="delete_client_id" name="client_id">
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-danger">Delete Client</button>
              </div>
          </form>
      </div>
  </div>
</div>