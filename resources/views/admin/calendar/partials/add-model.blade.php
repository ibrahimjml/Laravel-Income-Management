<!-- Add Calendar Modal -->
<div class="modal fade" id="addCalendarModal" tabindex="-1" aria-labelledby="addCalendarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('calendar.store')}}" id="addCalendarForm" method="POST">
            @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="addCalendarModalLabel">{{__('message.Add Calendar')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="d-flex mb-3">
                      <div class="flex-fill me-2">
                          <label for="event_name" class="form-label">Event name :</label>
                          <input type="text" class="form-control border" id="event_name" name="event_name" required>
                      </div>
                      <div class="flex-fill me-2">
                          <label for="color" class="form-label">Color :</label>
                          <input type="color" class="form-control border" id="color" name="color">
                      </div>
                    </div>
                  <div class="mb-3">
                      <label for="start_date" class="form-label">Start date :</label>
                      <input type="date" class="form-control border" id="start_date" name="start_date"></input>
                  </div>
                  <div class="mb-3">
                      <label for="end_date" class="form-label">End date :</label>
                      <input type="date" class="form-control border" id="end_date" name="end_date"></input>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add</button>
              </div>
          </form>
      </div>
  </div>
</div>