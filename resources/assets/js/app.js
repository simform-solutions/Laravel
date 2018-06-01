window._ = require('lodash')

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

window.init = function ($selector) {
  const $forms = $selector.find('form.jquery-validate')
  if ($forms.length > 0) {
    var submitActor = null
    var $submitActors = $forms.find(':submit')

    $forms.validate({
      ignore: [],
      highlight: function (input) {
        $(input).closest('.form-line').addClass('error')
      },
      unhighlight: function (input) {
        $(input).closest('.form-line').removeClass('error')
      },
      errorPlacement: function (error, input) {
        $(input).closest('.form-group, .input-group').append(error)
      }
    })

    $forms.submit(function () {
      if ($(this).valid()) {
        if (submitActor === null) {
          // If no actor is explicitly clicked, the browser will
          // automatically choose the first in source-order
          // so we do the same here
          submitActor = $submitActors[0]
        }

        $(submitActor).prop('disabled', true).html('<div class="preloader" style="width: 16px; height: 16px;">\n' +
          '        <div class="spinner-layer pl-white">\n' +
          '        <div class="circle-clipper left">\n' +
          '        <div class="circle"></div>\n' +
          '        </div>\n' +
          '        <div class="circle-clipper right">\n' +
          '        <div class="circle"></div>\n' +
          '        </div>\n' +
          '        </div>\n' +
          '        </div>')
      }
    })

    $submitActors.click(function (event) {
      submitActor = this
    })
  }
}

window.activeSlimScrollTo = function ($selector, height) {
  var slimScrollOptions = {
    color: 'rgba(0,0,0,0.5)',
    size: '4px',
    alwaysVisible: false,
    borderRadius: '0',
    railBorderRadius: '0'
  }

  if (typeof height !== 'undefined') {
    slimScrollOptions.height = height
  }

  $selector.slimscroll(slimScrollOptions)
}

$(function () {
  init($('body'))
  activeSlimScrollTo($('.sidebar .menu'), '100%')
  activeSlimScrollTo($('.table-responsive'), '100%')
  activeSlimScrollTo($('.navbar-right .dropdown-menu .body .menu'))
})
