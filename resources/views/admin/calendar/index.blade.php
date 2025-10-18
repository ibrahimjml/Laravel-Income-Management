@extends('layouts.app')

@section('title', 'calender')
@push('styles')
  <style>
    .fc-daygrid-day-number,
    .fc-col-header-cell-cushion,
    .fc-event-time {
      color: #000 !important;
      text-decoration: none !important;
    }
    .fc-event-title{
      color: #fcf8f8;
      font-weight: 500
    }
  </style>
@endpush
@section('content')

  <div class="container-fluid">
    <h1 class="mb-4 text-center">Calendar</h1>

    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary w-100" id="btn-new-event" data-bs-toggle="modal"
              data-bs-target="#addCalendarModal">
              <i class="ti ti-plus me-2 align-middle"></i> Create New Event
            </button>

            <div id="external-events" class="mt-2">
              <p class="text-muted">
                Drag and drop your event or click in the calendar</p>
            
              <p class="text-black text-center font-weight-bold">
                Upcoming Payments Events</p>
              @foreach ($upcomigPayments as $income)
                @foreach ($income->payments as $payment)
                  <div class="external-event fc-event text-black mb-2 p-2 bg-success-subtle rounded">
                    <p class="mb-1">{{ $income->client->full_name }} - ${{ number_format($payment->payment_amount) }}</p>
                  </div>
                @endforeach
              @endforeach
            </div>

          </div>
        </div>
      </div> <!-- end col-->

      <div class="col-xl-9">
        <div class="row mb-3">
          <div class="col-sm-12">
            <input type="text" id="search-input" class="form-control border"
              placeholder="{{__('message.Search for Events')}}...">
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div id="calendar"></div>
          </div>
        </div>
      </div><!-- end col -->
    </div>
    <!--end row-->
  </div>
  <!--add event-->
  @include('admin.calendar.partials.add-model')
  <!--edit event-->
  @include('admin.calendar.partials.edit-model')
  <!--delete event-->
  @include('admin.calendar.partials.delete-model')
@endsection

@push('scripts')
  <!-- render calendar -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
  <script>
    $(document).ready(function () {
      let currentEventId = null;

      const calendarEl = document.getElementById('calendar');
      const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        editable: true,
        timeZone: 'UTC',
        events: '{{ route("calendar.events") }}',

        dateClick: function (info) {
          $('#addCalendarModal').modal('show');
          $('#start_date').val(info.dateStr);
          $('#end_date').val(info.dateStr);
        },

        eventClick: function (info) {
          currentEventId = info.event.id;
          const event = info.event;

          $('#edit_event_name').val(event.title);
          $('#edit_color').val(event.backgroundColor);
          $('#edit_start_date').val(event.startStr.split('T')[0]);
          $('#edit_end_date').val(event.endStr ? event.endStr.split('T')[0] : event.startStr.split('T')[0]);

          $('#delete_calendar_name').text(event.title);
          $('#delete_calendar_id').val(event.id);

          $('#EditCalendarModal').modal('show');
        },

        eventDrop: function (info) {
          const eventId = info.event.id;
          const newStartDate = info.event.start;
          const newEndDate = info.event.end || newStartDate;

          const Url = "{{ route('calendar.move', ':id') }}".replace(':id', eventId);
          $.ajax({
            url: Url,
            method: 'PUT',
            data: {
              _token: '{{ csrf_token() }}',
              start_date: newStartDate.toISOString().split('T')[0],
              end_date: newEndDate.toISOString().split('T')[0]
            },
            success: function (response) {
              showAlert(response.message, 'success');
            },
            error: function (xhr) {
              showAlert('Error moving event!', 'error');
              info.revert();
            }
          });
        },

        eventResize: function (info) {
          const eventId = info.event.id;
          const newEndDate = info.event.end;
          const Url = "{{ route('calendar.resize', ':id') }}".replace(':id', eventId);

          $.ajax({
            url: Url,
            method: 'PUT',
            data: {
              _token: '{{ csrf_token() }}',
              end_date: newEndDate.toISOString().split('T')[0]
            },
            success: function (response) {
              showAlert(response.message, 'success');
            },
            error: function (xhr) {
              showAlert('Error resizing event!', 'error');
              info.revert();
            }
          });
        },

        eventReceive: function (info) {
          const eventId = info.event.id;
          const newStartDate = info.event.start;
          const newEndDate = info.event.end || newStartDate;

          const Url = "{{ route('calendar.move', ':id') }}".replace(':id', eventId);
          $.ajax({
            url: Url,
            method: 'PUT',
            data: {
              _token: '{{ csrf_token() }}',
              start_date: newStartDate.toISOString().split('T')[0],
              end_date: newEndDate.toISOString().split('T')[0]
            },
            success: function (response) {
              showAlert(response.message, 'success');
            },
            error: function (xhr) {
              showAlert('Error moving event!', 'error');
              info.revert();
            }
          });
        }

      });

      calendar.render();

      initializeExternalEvents();

      // Add Event Form 
      $('#addCalendarForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize(),
          success: function (response) {
            $('#addCalendarModal').modal('hide');
            calendar.refetchEvents();
            $('#addCalendarForm')[0].reset();
            showAlert('Event added successfully!', 'success');
          },
          error: function (xhr) {
            showAlert('Error adding event!', 'error');
          }
        });
      });

      // Edit Event Form 
      $('#EditCalendarForm').on('submit', function (e) {
        e.preventDefault();

        if (!currentEventId) return;

        const updateUrl = "{{ route('calendar.update', ':id') }}".replace(':id', currentEventId);

        $.ajax({
          url: updateUrl,
          method: 'PUT',
          data: $(this).serialize(),
          success: function (response) {
            $('#EditCalendarModal').modal('hide');
            calendar.refetchEvents();
            currentEventId = null;
            showAlert('Event updated successfully!', 'success');
          },
          error: function (xhr) {
            showAlert('Error updating event!', 'error');
          }
        });
      });

      // Delete Event Form 
      $('#deleteCalendarForm').on('submit', function (e) {
        e.preventDefault();

        const eventId = $('#delete_calendar_id').val();

        // Create the URL properly
        const deleteUrl = "{{ route('calendar.destroy', ':id') }}".replace(':id', eventId);

        $.ajax({
          url: deleteUrl,
          method: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
          },
          success: function (response) {
            $('#deleteCalendarModal').modal('hide');
            calendar.refetchEvents();
            showAlert('Event deleted successfully!', 'success');
          },
          error: function (xhr) {
            showAlert('Error deleting event!', 'error');
          }
        });
      });

      // Show delete confirmation from edit modal
      $(document).on('click', '.delete-event-btn', function () {
        $('#editCalendarModal').modal('hide');
        $('#deleteCalendarModal').modal('show');
      });

      // alert prepand
      function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
          message +
          '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
          '</div>');

        $('.card-body').prepend(alert);

        setTimeout(function () {
          alert.alert('close');
        }, 3000);
      }
      // darg event
      function initializeExternalEvents() {
        const containerEl = document.getElementById('external-events');

        new FullCalendar.Draggable(containerEl, {
          itemSelector: '.external-event',
          eventData: function (eventEl) {
            return {
              id: eventEl.getAttribute('data-event-id'),
              title: eventEl.innerText.trim(),
              color: eventEl.style.backgroundColor || eventEl.getAttribute('data-color')
            };
          }
        });
      }
    });
  </script>
@endpush