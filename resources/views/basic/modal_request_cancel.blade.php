<div class="modal fade" id="modalRequestCan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">{{__('Cancel')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ $route }}" method="get">
        @csrf
        <div class="modal-body">
          <strong style="font-size: 23px">{{__('Do You Want To Cancel')}} <span id="nameDel" style="color: blue"></span> ?</strong>
          <input type="text" name="ID" id="idCan" class="form-control hide">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="submit" class="btn btn-danger">{{__('Cancel')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>