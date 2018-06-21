var managersTable

$(function () {
  managersTable = $('#managers-list').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    info: true,
    stateSave: true,
    ajax: managersListURL,
    deferRender: true,
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
      { data: null, name: null, sortable: false, searchable: false, defaultContent: '' },
      { data: 'photo', name: 'photo', sortable: false, searchable: false },
      { data: 'name', name: 'name' },
      { data: 'address', name: 'address', sortable: false },
      { data: 'contact_info', name: 'contact_info', sortable: false, searchable: false },
      { data: 'manager_account', name: 'manager_account', sortable: false, searchable: false },
      { data: 'price_range', name: 'price_range', searchable: false },
      { data: 'current_status', name: 'current_status', sortable: false, searchable: false },
      { data: 'updated_at', name: 'updated_at', searchable: false },
      { data: 'is_active', name: 'is_active', searchable: false, sortable: false },
      { data: 'editAction', name: 'editAction', sortable: false, searchable: false },
      { data: 'deleteAction', name: 'deleteAction', sortable: false, searchable: false },
      { data: 'manager.first_name', name: 'manager.first_name', visible: false },
      { data: 'manager.last_name', name: 'manager.last_name', visible: false },
      { data: 'phone', name: 'phone', visible: false },
      { data: 'email', name: 'email', visible: false }
    ],
    'order': [[ 8, 'desc' ]],
    language: {
      processing: $('.page-loader-wrapper').html(),
      sLengthMenu: '_MENU_ Restaurants',
      sInfo: 'Showing _START_ to _END_ of _TOTAL_ Restaurant(s)',
      sInfoFiltered: ' (filtered from _MAX_ total restaurants)'
    }
  })

  $('<label>' +
    'Current Status: ' +
    '<select class="form-control input-sm" aria-controls="managers-list" id="current_status">' +
    '<option value="0">All</option>' +
    '<option value="1">Open</option>' +
    '<option value="2">Close</option>' +
    '<option value="3">Opening Soon</option>' +
    '<option value="4">Closing Soon</option>' +
    '</select>' +
    '</label>').appendTo('#managers-list_filter')

  $('#managers-list_filter').find('>label').addClass('pull-right m-l-15')

  $('#current_status').on('change', function () {
    managersTable.columns(7).search($(this).val()).draw()
  })
})
