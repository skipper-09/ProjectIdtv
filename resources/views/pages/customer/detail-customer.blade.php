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
    <label class="col-sm-3 ">Mac STB</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->mac}}</label>
    </div>
    <label class="col-sm-3 ">Type STB</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->stb->name}}</label>
    </div>
    <label class="col-sm-3 ">Area</label>
    <div class="col-sm-9">
      <label for="">{{$customer[0]->region->name}}</label>
    </div>
  </div>
</div>
<div class="modal-footer bg-whitesmoke br">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>