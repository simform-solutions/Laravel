var allowFormAjaxProcess = true
var bufferCache = null

window.init = function ($selector) {
  const $forms = $selector.find('form.jquery-validate')
  const $dropDowns = $selector.find('select')

  if ($dropDowns.length > 0 && $selector.hasClass('modal')) {
    $.AdminBSB.select.activate()
  }

  if ($forms.length > 0) {
    !$selector.hasClass('modal') || $.AdminBSB.input.activate()

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

window.toggleSubmitButton = function ($form, makeDisable) {
  var $submitButton = $form.find(':submit')
  if ($submitButton.length > 0) {
    $submitButton.prop('disabled', makeDisable)
    if (makeDisable) {
      bufferCache = $submitButton.html()
      $submitButton.html('<div class="preloader" style="width: 16px; height: 16px;">\n' +
                      '        <div class="spinner-layer submit-spinner-layer">\n' +
                      '        <div class="circle-clipper left">\n' +
                      '        <div class="circle"></div>\n' +
                      '        </div>\n' +
                      '        <div class="circle-clipper right">\n' +
                      '        <div class="circle"></div>\n' +
                      '        </div>\n' +
                      '        </div>\n' +
                      '        </div>')
    } else {
      $submitButton.html(bufferCache)
      bufferCache = null
    }
  }
  allowFormAjaxProcess = !makeDisable
}

window.ajaxFormSubmit = function ($form) {
  if (allowFormAjaxProcess) {
    toggleSubmitButton($form, true)
    $.ajax({
      url: $form.attr('action'),
      type: $form.attr('method'),
      data: new FormData($form[0]),
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) {
        toggleSubmitButton($form, false)
        window[$form.data('success-callback')](response, $form)
      },
      error: function (jqXHR, textStatus, errorThrown) {
        toggleSubmitButton($form, false)
        window[typeof $form.data('error-callback') === 'undefined' || !$form.data('error-callback') ? 'formAJAXError' : $form.data('error-callback')](jqXHR, textStatus, errorThrown)
      }
    })
  }
}

window.formAJAXError = function (jqXHR) {
  showNotification(jqXHR.responseJSON.message, 'danger')
}

window.showNotification = function (colorName, title, icon, message, url, animateEnter, animateExit) {
  const allowDismiss = true
  icon = typeof icon === 'undefined' ? 'notifications' : icon
  message = typeof message === 'undefined' ? '' : message
  url = typeof url === 'undefined' ? '#' : url
  colorName = typeof colorName === 'undefined' ? 'bg-black' : colorName

  $.notify({
    title: title,
    message: message,
    url: url
  },
  {
    type: colorName,
    allow_dismiss: allowDismiss,
    newest_on_top: true,
    timer: 1500,
    placement: {
      from: 'top',
      align: 'right'
    },
    animate: {
      enter: typeof animateEnter === 'undefined' ? 'animated fadeInRight' : animateEnter,
      exit: typeof animateExit === 'undefined' ? 'animated fadeOutRight' : animateExit
    },
    z_index: 9999,
    template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible ' + colorName + ' ' + (allowDismiss ? 'p-r-35' : '') + '" role="alert">' +
    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
    '<i data-notify="icon" class="material-icons">' + icon + '</i> ' +
    '<h4 data-notify="title">' + title + '</h4> ' +
    '<span data-notify="message">' + message + '</span>' +
    '<div class="progress" data-notify="progressbar">' +
    '<div class="progress-bar progress-bar-' + colorName + '" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>' +
    '</div>' +
    '<a href="' + url + '" data-notify="url"></a>' +
    '</div>'
  })
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
    }).on('shown.bs.modal', function () {
      init($('.modal.in'))
      if ($('#manager-form').length > 0) {
        initManagerForm()
      }
    })
  }

  $('body').delegate('img.img_upload', 'click', function () {
    $(this).closest('.form-group, .input-group').find('input:file').trigger('click')
  }).delegate('a.confirm-delete', 'click', function (e) {
    e.preventDefault()
    const url = $(this).attr('href')
    const successCallback = $(this).data('success-callback')

    swal({
      title: 'Are you sure?',
      text: 'You will not be able to recover this record!',
      type: 'warning',
      confirmButtonText: 'YES',
      cancelButtonText: 'NO',
      confirmButtonColor: '#DD6B55',
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
      $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        data: {method: '_DELETE', submit: true},
        success: function (response) {
          swal({
            title: 'Done!',
            text: 'It was successfully deleted!',
            timer: 2000,
            type: 'success',
            showConfirmButton: false
          })
          window[successCallback](response)
        },
        error: function (jqXHR, textStatus, errorThrown) {
          swal('Error deleting!', 'Please try again', 'error')
        }
      })
    })
    return false
  }).delegate('form.ajax-form-submit', 'submit', function (e) {
    e.preventDefault()
    if (typeof $(this).data('extra-validation') === 'undefined' || !$(this).data('extra-validation') || window[$(this).data('extra-validation')]($(this))) {
      ajaxFormSubmit($(this))
    }
    return false
  })
})
