<!-- Add Calendar Modal -->
<div class="modal fade" id="EditCalendarModal" tabindex="-1" aria-labelledby="EditCalendarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="EditCalendarForm" >
            @csrf
            @method('PUT')
              <div class="modal-header">
                  <h5 class="modal-title" id="EditCalendarModalLabel">Edit Calendar</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="d-flex mb-3">
                      <div class="flex-fill me-2">
                          <label for="edit_event_name" class="form-label">Event name :</label>
                          <input type="text" class="form-control border" id="edit_event_name" name="event_name" required>
                      </div>
                      <div class="flex-fill me-2">
                          <label for="edit_color" class="form-label">Color :</label>
                          <input type="color" class="form-control border" id="edit_color" name="color">
                      </div>
                    </div>
                  <div class="mb-3">
                      <label for="edit_start_date" class="form-label">Start date :</label>
                      <input type="date" class="form-control border" id="edit_start_date" name="start_date"></input>
                  </div>
                  <div class="mb-3">
                      <label for="edit_end_date" class="form-label">End date :</label>
                      <input type="date" class="form-control border" id="edit_end_date" name="end_date"></input>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Edit</button>
                  <button type="button" class="delete-event-btn btn btn-danger">Delete</button>

              </div>
          </form>
      </div>
  </div>
</div>