<div class="modal fade" id="showmodalkeu" tabindex="-1" role="dialog" aria-labelledby="showmodalkeu" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showmodalkeu">@yield('modaltitle')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @yield('modal-content')
            </div>
        </div>
    </div>
</div>
