<div class="modal-header">
    <h5 class="modal-title">Detail {{ $page_name }} </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex">
        <label class="font-weight-bolder mr-2">Reseller:</label>
        <div class="">
            <label for="">{{ $detail->reseller->name }}</label>
        </div>
    </div>
    <div class="d-flex">
        <label class="font-weight-bolder mr-2">Nama Bank:</label>
        <div class="">
            <label for="">{{ $detail->reseller->bank->name }}</label>
        </div>
    </div>
    <div class="d-flex">
        <label class="font-weight-bolder mr-2">Nomor Rekening:</label>
        <div class="">
            <label for="">{{ $detail->reseller->rekening }}</label>
        </div>
    </div>
    <div class="d-flex">
        <label class="font-weight-bolder mr-2">Nama Pemilik Rekening:</label>
        <div class="">
            <label for="">{{ $detail->reseller->owner_rek }}</label>
        </div>
    </div>
    <div class="d-flex">
        <label class="font-weight-bolder mr-2">Jumlah:</label>
        <div class="">
            <label for="">Rp. {{ number_format($detail->amount) }}</label>
        </div>
    </div>

    <label class="font-weight-bolder mr-2">Bukti Transfer:</label>
    <div class="" style="width: 400px">
        <img class="img-fluid" src="{{ asset('storage/images/buktitf/' . $detail->bukti_tf) }}" alt="Bukti Transfer">
    </div>

</div>
{{-- <div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div> --}}
