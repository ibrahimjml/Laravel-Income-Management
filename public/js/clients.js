// function initializeModalsAndForms() {
//   const editTypeModal = document.getElementById('editTypeModal');
//   const editClientModal = document.getElementById('editClientModal');
//   const deleteClientModal = document.getElementById('deleteClientModal');



//   if (editClientModal) {
//       editClientModal.addEventListener('show.bs.modal', function (event) {
//           const button = event.relatedTarget;
//           document.getElementById('edit_client_id').value = button.getAttribute('data-client-id');
//           document.getElementById('edit_client_fname').value = button.getAttribute('data-client-fname');
//           document.getElementById('edit_client_lname').value = button.getAttribute('data-client-lname');
//           document.getElementById('edit_client_phone').value = button.getAttribute('data-client-phone');

//           const typeIds = button.getAttribute('data-client-type').split(',').map(id => id.trim());
//           const checkboxes = document.querySelectorAll('#editClientModal .form-check-input');
//           checkboxes.forEach(checkbox => checkbox.checked = false);
//           typeIds.forEach(typeId => {
//               const checkbox = document.getElementById('editClientType' + typeId);
//               if (checkbox) checkbox.checked = true;
//           });
//       });
//   }

//   if (deleteClientModal) {
//       deleteClientModal.addEventListener('show.bs.modal', function (event) {
//           const button = event.relatedTarget;
//           document.getElementById('delete_client_id').value = button.getAttribute('data-client-id');
//           document.getElementById('delete_client_name').textContent = button.getAttribute('data-client-name');
//       });
//   }
// }

// document.addEventListener('DOMContentLoaded', initializeModalsAndForms);
