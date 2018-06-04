var countryCodes = require('country-data').callingCountries.all

window.initManagerForm = function () {
  for (var c in countryCodes) {
    $('#country_code').append('<option value="' + countryCodes[c].countryCallingCodes[0] + '">' + countryCodes[c].emoji + ' ' + countryCodes[c].countryCallingCodes[0] + '</option>')
  }
  //$('#mobile_number').inputmask('(999) 999-99-99', { placeholder: '(___) ___-__-__' })
}
