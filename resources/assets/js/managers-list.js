var managersTable

$(function () {
  managersTable = $('#managers-list').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: managersListURL,
    /* drawCallback: function () {
      $(document).off('click.bs.toggle', 'div[data-toggle=toggle]')
      $(this).find('input:checkbox[data-toggle=toggle]').bootstrapToggle('destroy').bootstrapToggle()
      $(document).on('click.bs.toggle', 'div[data-toggle=toggle]', function (e) {
        e.preventDefault()
        e.stopImmediatePropagation()

        const $checkbox = $(this).find('input[type=checkbox]')
        const value = $checkbox.is(':checked') ? 1 : 0
        $checkbox.bootstrapToggle('toggle')

        if (confirm('Are you sure?')) {
          ajaxFormSubmit($('<form id="account_change_status" action="' + $checkbox.data('url') + '" method="post" data-success-callback="userSavedSuccess"><input type="hidden" name="_method" value="PATCH" /><input type="number" name="is_active" value="' + value + '" /></form>'))
        }
      })
    }, */
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
