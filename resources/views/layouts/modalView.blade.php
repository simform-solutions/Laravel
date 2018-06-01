@yield('form_start')
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
    <h4 class="modal-title" id="defaultModalLabel">@yield('modal-title')</h4>
</div>
<div class="modal-body">
    @yield('modal-body')
</div>
<div class="modal-footer">@yield('modal-footer')</div>
@yield('form_end')