var countryCodes = require('country-data').callingCountries.all

window.initManagerForm = function () {
  var selectedCountryCode = ''

  if ($(':input[name=c_code_m]').length > 0) {
    selectedCountryCode = $(':input[name=c_code_m]').val()
  }

  for (var c in countryCodes) {
    $('#country_code').append('<option ' + (selectedCountryCode === countryCodes[c].countryCallingCodes[0].split(' ').join('') ? 'selected' : '') + ' value="' + countryCodes[c].countryCallingCodes[0] + '">' + countryCodes[c].emoji + ' ' + countryCodes[c].countryCallingCodes[0] + '</option>')
  }

  $('#country_code').selectpicker('refresh')

  $(':input[name="phone_number"]').rules('add', {
    'remote':
        {
          url: $(':input[name="phone_number"]').data('remote-url'),
          data:
            {
              mobile_number: function () {
                var theMobileNumber = ($(':input[name="country_code"]').val() + $(':input[name="phone_number"]').val()).split(' ').join('')
                $(':input[name="mobile_number"]').val(theMobileNumber)
                return theMobileNumber
              }
            }
        }
  })
}

function refreshList () {
  managersTable.ajax.reload()
}

window.managerSavedSuccess = function (response, $form) {
  refreshList()
  if (typeof $form !== 'undefined') {
    $form.find(':reset').trigger('click')
    window.showNotification('bg-green', 'Success!', 'done', 'Record saved.')
  }
}

window.userDeletedSuccess = function () {
  refreshList()
}
