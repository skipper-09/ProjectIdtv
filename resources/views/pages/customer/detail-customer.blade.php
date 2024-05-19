<div class="modal-header">
  <h5 class="modal-title">Detail {{$page_name}} </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class=" row ">
    <label class="col-sm-3 ">Nama</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->name}}</label>
    </div>
    <label class="col-sm-3 ">Address</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->address}}</label>
    </div>
    <label class="col-sm-3 ">No Telepon</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->phone}}</label>
    </div>
    <label class="col-sm-3 ">Mac STB</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->mac}}</label>
    </div>
    <label class="col-sm-3 ">Username</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->username}}</label>
    </div>
    <label class="col-sm-3 ">Ppoe</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->ppoe}}</label>
    </div>
    <label class="col-sm-3 ">Type STB</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->stb->name}}</label>
    </div>
    <label class="col-sm-3 ">Area</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->region->name}}</label>
    </div>
    <label class="col-sm-3 ">Perusahaan</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->region->company->name}}</label>
    </div>
    <label class="col-sm-3 ">Tanggal Aktif</label>
    <div class="col-sm-9">
      <label for="">{{date('d-m-Y', strtotime($customer[0]->created_at))}}</label>
    </div>
    <label class="col-sm-3 ">Status</label>
    <div class="col-sm-9">
      @if ($customer[0]->is_active == 1)
      <span class="badge badge-primary">Aktif</span>
      @else
      <span class="badge badge-secondary">Tidak Aktif</span>
      @endif

    </div>
  </div>
</div>
<div class="modal-footer bg-whitesmoke br">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>