<div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="editTypeForm" >
              @csrf
            
                <div class="modal-header">
                    <h5 class="modal-title" id="editTypeModalLabel">Edit Client Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_type_id" class="form-label">Select Client Type</label>
                        <select class="form-select" id="edit_type_id" name="type_id" data-url="{{ url('/admin/edit-type') }}" required>
                            <option selected disabled  >Select Client Type</option>
                           @foreach($clienttype as $type)
                                <option value="{{$type->type_id}}">{{$type->type_name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type_name" class="form-label">New Type Name</label>
                        <input type="text" class="form-control border" id="edit_type_name" name="type_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="delete_type" class="form-label">Delete Client Type</label>
                        <ul class="list-group">
                          @foreach($clienttype as $type)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                 {{$type->type_name}}
                                    <button class="btn btn-danger btn-sm" onclick="deleteType({{$type->type_id}})">
                                        Delete
                                    </button>
                                </li>
                          @endforeach
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('editTypeForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const baseUrl = document.getElementById('edit_type_id').dataset.url;
  const typeId = document.getElementById('edit_type_id').value;
  const typeName = document.getElementById('edit_type_name').value;
  
  try {
      const response = await fetch(`${baseUrl}/${typeId}`, {
          method: 'PUT',
          headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ type_name: typeName })
      });

      const data = await response.json();
      
      if (!response.ok) throw new Error(data.message || 'Update failed');
      
      alert('Updated successfully');
      location.reload();
      
  } catch (error) {
      console.error('Error:', error);
      alert(error.message);
  }
});
</script>

<script>
  function deleteType(typeId) {
      if (!confirm('Are you sure you want to delete this type?')) return;
  
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
      fetch(`/admin/delete-type/${typeId}`, {
          method: 'DELETE', 
          headers: {
              'X-CSRF-TOKEN': csrfToken, 
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert('Client type deleted successfully!');
        
              const itemToRemove = document.getElementById('type-' + typeId); 
              if (itemToRemove) {
                  itemToRemove.remove();
                  location.reload();
              }
          } else {
              alert('Failed to delete client type.');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('An error occurred.');
      });
  }
  </script>