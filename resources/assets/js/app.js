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
    $.AdminBSB.input.activate()

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

window.readURL = function (input, target) {
  var ValidImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg']
  if (input.files && input.files[0] && $.inArray(input.files[0]['type'], ValidImageTypes) >= 0) {
    var reader = new FileReader()

    reader.onload = function (e) {
      target.attr('src', e.target.result)
    }

    reader.readAsDataURL(input.files[0])
  } else if (typeof target.data('default-image') !== 'undefined') {
    target.attr('src', target.data('default-image'))
  }
}

$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  if (typeof $.validator !== 'undefined') {
    $.validator.addMethod(
      'regex',
      function (value, element, regexp) {
        const re = new RegExp(regexp)
        return this.optional(element) || re.test(value)
      },
      'Please check your input.'
    )
  }

  init($('body'))

  if (typeof $.fn.slimscroll !== 'undefined') {
    activeSlimScrollTo($('.sidebar .menu'), '100%')
    activeSlimScrollTo($('.table-responsive'), '100%')
    activeSlimScrollTo($('.navbar-right .dropdown-menu .body .menu'))
  }

  if ($('.modal').length > 0) {
    $('.modal').on('hidden.bs.modal', function () {
      $(this).removeData('bs.modal').find('.modal-content').empty().html('')
    }).on('loaded.bs.modal', function () {
      init($('.modal.in'))
    })
  }

  $('body').delegate('img.img_upload', 'click', function () {
    $(this).closest('.form-group, .input-group').find('input:file').trigger('click')
  }).delegate('a.confirm-delete', 'click', function (e) {
    e.preventDefault()
    const url = $(this).attr('href')
    const successCallback = $(this).data('success-callback')
    const errorCallback = $(this).data('error-callback')

    if (confirm('Are you sure?')) {
      $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        data: {method: '_DELETE', submit: true},
        success: function (response) {
          location.reload()
          // window[successCallback](response)
        },
        error: function (jqXHR, textStatus, errorThrown) {
          // window[typeof errorCallback === 'undefined' || !errorCallback ? 'formAJAXError' : errorCallback](jqXHR, textStatus, errorThrown)
        }
      })
    }
  })
})
