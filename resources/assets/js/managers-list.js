var managersTable

$(function () {
  managersTable = $('#managers-list').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: managersListURL,
    drawCallback: function () {
      $(':checkbox[name=is_active]').off('click').on('click', function (e) {
        e.preventDefault()
        e.stopImmediatePropagation()

        var action = ($(this).is(':checked') ? '' : 'de-') + 'activated'

        ajaxConfirmation('This account will be ' + action + '!', $(this).data('url'), 'managerSavedSuccess', 'It was successfully ' + action + '!', 'Error de-activating!', { is_active: $(this).is(':checked') ? 1 : 0 }, 'PATCH', '_PATCH')

        return false
      })
    },
    columns: [
      { data: 'avatar', name: 'avatar', sortable: false, searchable: false },
      { data: 'first_name', name: 'first_name' },
      { data: 'last_name', name: 'last_name' },
      { data: 'email', name: 'email' },
      { data: 'mobile_number', name: 'mobile_number' },
      { data: 'updated_at', name: 'updated_at', searchable: false },
      { data: 'is_active', name: 'is_active', searchable: false, sortable: false },
      { data: 'editAction', name: 'editAction', sortable: false, searchable: false },
      { data: 'deleteAction', name: 'deleteAction', sortable: false, searchable: false }
    ],
    'order': [[ 5, 'desc' ]],
    language: {
      processing: $('.page-loader-wrapper').html()
    }
  })
})
