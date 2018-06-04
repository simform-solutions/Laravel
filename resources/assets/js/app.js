var allowFormAjaxProcess = true
var bufferCache = null
var buttonText = null

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
  var $submitButton = $form.find('[type=submit]')
  if ($submitButton.length > 0) {
    $submitButton.prop('disabled', makeDisable)
    if (makeDisable) {
      var buttonWidth = $submitButton.width()
      bufferCache = $submitButton.find('i.fa').remove()
      buttonText = $submitButton.text()
      $submitButton.text('')
      $submitButton.prepend('<div class="pre-loader" style="width: ' + buttonWidth + 'px">' +
        '                       <i class="fa fa-spinner fa-spin"></i>' +
        '                     </div>\n')
    } else {
      $submitButton.find('.pre-loader').remove()
      $submitButton.text(buttonText)
      bufferCache.prependTo($submitButton)
      bufferCache = null
      buttonText = null
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
      if ($('#manager-form').length > 0) {
        initManagerForm()
      }
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
  }).delegate('form.ajax-form-submit', 'submit', function (e) {
    e.preventDefault()
    if (typeof $(this).data('extra-validation') === 'undefined' || !$(this).data('extra-validation') || window[$(this).data('extra-validation')]($(this))) {
      ajaxFormSubmit($(this))
    }
    return false
  })
})
