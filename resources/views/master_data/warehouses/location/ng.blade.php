@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif

	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('masterData.unit.importFileExcel')])
	@include('basic.modal_table_error')
	@endif
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Stock') }} NG
	                	</span>
	                	<div class="card-tools">
			               	@if(Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
	                		<a href="#" class="btn btn-warning btn-import"  data-toggle="modal" data-target=".bd-example-modal-lg">
                  				{{__('Import')}} {{ __('Warehouse') }} {{ __('NG') }}
                  			</a>
                  			@endif
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="#" method="post">
	                		@csrf
	                		<div class="row">
                                <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Location')}}</label>
		                        	<select class="custom-select select2" name="Symbols">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Location')}}
		                          		</option>
		                        	</select>
	                      		</div>
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Materials')}}</label>
		                        	<select class="custom-select select2" name="Symbols">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Materials')}}
		                          		</option>
		                        	</select>
	                      		</div>
	                      		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Name')}} {{ __('Error') }}</label>
		                        	<select class="custom-select select2" name="Name">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Name')}}
		                          		</option>
		                          		
		                        	</select>
	                      		</div>
                                <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Status')}}</label>
		                        	<select class="custom-select select2" name="Name">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Status')}}
		                          		</option>
		                          		<option value="">
		                          			{{__('Waiting')}} {{__('Handle')}}
		                          		</option>
                                        <option value="">
		                          			{{__('Are')}} {{__('Handle')}}
		                          		</option>
                                        <option value="">
		                          			{{__('Handle')}} {{__('Success')}}
		                          		</option>
		                        	</select>
	                      		</div>
	                      		<div class="col-md-2" style="margin-top: 33px">
	                      			<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
									<a href="#" class="btn btn-success btn-handle" data-toggle="modal" data-target="#modalRequestTa">
			                			{{__('Tái Sử Dụng')}}
									</a>
									<a href="#" class="btn btn-danger btn-handle"data-toggle="modal" data-target="#modalRequestDel">
										{{__('Hủy')}}
									</a>
	                      		</div>
                      		</div>
	                	</form>
		                @include('basic.alert')
		            	</br>
						<p>Tổng Số Lượng NG : <span style="color:red">60 ( Kg )</span></p>
						<p>Tổng Số Lượng đã xử Lý : <span style="color:red">20 ( Kg )</span></p>
		                <table class="table table-bordered text-nowrap w-100" id="tableUnit" width="100%">
		                	<thead>
								<th>
                                    <div class="form-group pro1 col-2 style="text-align:center;">
										<div class="icheck-primary d-inline " >
										<input type="checkbox"  id="checkboxPrimary" class="checkAllStock" >
										<label for="checkboxPrimary">  Chọn Hết </label>
										</div>
                                    </div>
                                </th>
                                <th>{{__('Location')}}</th>
		                		<th>{{__('Box_ID')}}</th>
                                <th>{{__('Materials')}}</th>
		                		<th>{{__('Error') }}</th>
                                <th>{{__('Location') }} {{__('Incurred')}}</th>
                                <th>{{__('Quantity')}}</th>
		                		<th>{{__('Handle')}}</th>
                                <th>{{__('Note')}}</th>
		                		<th>{{__('User Created')}}</th>
		                		<th>{{__('Time Created')}}</th>
		                		<th>{{__('User Updated')}}</th>
		                		<th>{{__('Time Updated')}}</th>
		                		<th>{{__('Action')}}
								
								</th>

		                	</thead>
		                	<tbody>
		                		<tr>
									<td>
                                        <div class="form-group col-1" style=" text-align:center;padding-top: 2.5%;" >
                                            <div class="icheck-primary d-inline" >
                                                <input type="checkbox" value=""  id="checkboxPrimaryQC"  >
                                                <label for="checkboxPrimaryQC" ></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>NG-01</td>
                                    <td>242022002</td>
                                    <td>99M-1PEWN0023</td>
                                    <td>L1</td>
                                    <td>May1</td>
                                    <td>10</td>
                                    <td>0</td>
                                    <td>Lỗi 1 Phát Sinh ngày 18/07</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>
			                				@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
			                				<a href="#" class="btn btn-success btn-handle"data-toggle="modal" data-target="#modalRequestTa">
			                					{{__('Tái Sử Dụng')}}
			                				</a>
											<a href="#" class="btn btn-danger btn-handle"data-toggle="modal" data-target="#modalRequestDel">
			                					{{__('Hủy')}}
			                				</a>
			                				@endif
			                		</td>
                                </tr>
								<tr>
									<td>
                                        <div class="form-group col-1" style=" text-align:center;padding-top: 2.5%;" >
                                            <div class="icheck-primary d-inline" >
                                                <input type="checkbox" value=""  id="checkboxPrimaryQC"  >
                                                <label for="checkboxPrimaryQC" ></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>NG-01</td>
                                    <td>242022002</td>
                                    <td>99M-1PEWN0023</td>
                                    <td>L1</td>
                                    <td>May1</td>
                                    <td>10</td>
                                    <td>0</td>
                                    <td>Lỗi 1 Phát Sinh ngày 18/07</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>
			                				@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
			                				<a href="#" class="btn btn-success btn-handle">
			                					{{__('Tái Sử Dụng')}}
			                				</a>
											<a href="#" class="btn btn-danger btn-handle"data-toggle="modal" data-target="#modalRequestDel">
			                					{{__('Hủy')}}
			                				</a>
			                				@endif
			                		</td>
                                </tr>
								<tr>
									<td>
                                        <div class="form-group col-1" style=" text-align:center;padding-top: 2.5%;" >
                                            <div class="icheck-primary d-inline" >
                                                <input type="checkbox" value=""  id="checkboxPrimaryQC"  >
                                                <label for="checkboxPrimaryQC" ></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>NG-01</td>
                                    <td>242022002</td>
                                    <td>99M-1PEWN0023</td>
                                    <td>L1</td>
                                    <td>May1</td>
                                    <td>10</td>
                                    <td>0</td>
                                    <td>Lỗi 1 Phát Sinh ngày 18/07</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>
			                				@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
			                				<a href="#" class="btn btn-success btn-handle">
			                					{{__('Tái Sử Dụng')}}
			                				</a>
											<a href="#" class="btn btn-danger btn-handle"data-toggle="modal" data-target="#modalRequestDel">
			                					{{__('Hủy')}}
			                				</a>
			                				@endif
			                		</td>
                                </tr>
								<tr>
									<td>
                                        <div class="form-group col-1" style=" text-align:center;padding-top: 2.5%;" >
                                            <div class="icheck-primary d-inline" >
                                                <input type="checkbox" value=""  id="checkboxPrimaryQC"  >
                                                <label for="checkboxPrimaryQC" ></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>NG-01</td>
                                    <td>242022002</td>
                                    <td>99M-1PEWN0023</td>
                                    <td>L1</td>
                                    <td>May1</td>
                                    <td>10</td>
                                    <td>0</td>
                                    <td>Lỗi 1 Phát Sinh ngày 18/07</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>
			                				@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
			                				<a href="#" class="btn btn-success btn-handle">
			                					{{__('Tái Sử Dụng')}}
			                				</a>
											<a href="#" class="btn btn-danger btn-handle"data-toggle="modal" data-target="#modalRequestDel">
			                					{{__('Hủy')}}
			                				</a>
			                				@endif
			                		</td>
                                </tr>
                                <tr>
									<td>
                                        <div class="form-group col-1" style=" text-align:center;padding-top: 2.5%;" >
                                            <div class="icheck-primary d-inline" >
                                                <input type="checkbox" value=""  id="checkboxPrimaryQC"  >
                                                <label for="checkboxPrimaryQC" ></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>NG-01</td>
                                    <td>242022002</td>
                                    <td>99M-1PEWN0023</td>
                                    <td>L2</td>
                                    <td>May2</td>
                                    <td>20</td>
                                    <td>20</td>
                                    <td>Lỗi 2 Phát Sinh ngày 18/07</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>Admin</td>
                                    <td>2022-07-16 11:00:43</td>
                                    <td>
											  
			                		</td>
                                </tr>
		                	</tbody>
		                </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

    <!-- modal import -->
    <div class="modal fade  bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Import') }} {{ __('Warehouse') }} {{ __('NG') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
                    <div class="form-group col-12">
		                <label>{{__('Choose')}} {{__('Type')}}</label>
		                <select class="custom-select select2 type" name="Symbols">
		                    <option value="">
		                        {{__('Choose')}} {{__('Type')}}
		                    </option>
                            <option value="1">
		                        {{__('Machine')}}
		                    </option>
                            <option value="2">
		                        {{__('Warehouse')}}
		                    </option>
		                </select>
	                </div>
                    <div class="form-group col-12 hide mac">
		                <label>{{__('Choose')}} {{__('Machine')}}</label>
		                <select class="custom-select select2" name="Machine_ID">
		                    <option value="">
		                        {{__('Choose')}} {{__('Machine')}}
		                    </option>
		                </select>
	                </div>
                    <div class="form-group col-12 hide loc">
		                <label>{{__('Choose')}} {{__('Location')}}</label>
		                <select class="custom-select select2" name="Location">
		                    <option value="">
		                        {{__('Choose')}} {{__('Location')}}
		                    </option>
		                </select>
	                </div>
					<div class="form-group col-12">
		                <label>{{__('Choose')}} {{__('Box_ID')}}</label>
		                <select class="custom-select select2" name="Box_ID">
		                    <option value="">
		                        {{__('Choose')}} {{__('Box_ID')}}
		                    </option>
		                </select>
	                </div>
					<div class="form-group col-12">
		                <label>{{__('Choose')}} {{__('Error')}}</label>
		                <select class="custom-select select2" name="Error">
		                    <option value="">
		                        {{__('Choose')}} {{__('Error')}}
		                    </option>
		                </select>
	                </div>
					<div class="form-group col-12">
						<label>{{ __('Quantity') }}</label>
						<input type="Number" value="" class="form-control"  name="Quantity" placeholder="{{__('Enter')}} {{__('Quantity')}}">
					</div>
					<div class="form-group col-12">
						<label>{{ __('Note') }}</label>
						<input type="Text" value="" class="form-control"  name="Note" placeholder="{{__('Enter')}} {{__('Note')}}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
					<button type="button" class="btn btn-add hide float-right btn-warning ">{{__('Export')}}</button>
				</div>
			</div>
		</div>
	</div>
	<!-- modal handle -->
<div class="modal fade" id="modalRequestDel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">{{__('Delete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" method="get">
        @csrf
        <div class="modal-body">
          <strong style="font-size: 23px">{{__('Bạn Có Muốn Hủy ')}} <span id="nameDel" style="color: blue"></span> ?</strong>
          <input type="text" name="ID" id="idDel" class="form-control hide">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="submit" class="btn btn-danger">{{__('Hủy')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modalRequestTa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">{{__('Delete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="3" method="get">
        @csrf
        <div class="modal-body">
          <strong style="font-size: 23px">{{__('Bạn Có Muốn Tái Sử Dụng')}} <span id="nameDel" style="color: blue"></span> ?</strong>
          <input type="text" name="ID" id="idDel" class="form-control hide">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="submit" class="btn btn-success">{{__('Tái Sử Dụng')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>
	
@endsection
@push('scripts')
	<script>
		$('.select2').select2()
        $('#tableUnit').DataTable({
            language: __languages.table,
            scrollX : '100%',
            scrollY : '100%',
            dom: '<"bottom"><"clear">',
        });
        $(document).on('click', '.btn-delete', function()
        {
            let id = $(this).attr('id');
            let name = $(this).parent().parent().children('td').first().text();

            $('#modalRequestDel').modal();
            $('#nameDel').text(name);
            $('#idDel').val(id.split('-')[1]);
        });
        $(document).on('change','.type',function(){
            let type = $('.type').val()
            if(type == 1)
            {
                $('.mac').show()
                $('.loc').val('').change()
                $('.loc').hide()
            }
            else if(type == 2)
            {
                $('.mac').hide()
                $('.mac').val('').change()
                $('.loc').show()
            }
            else
            {
                $('.loc').val('').change()
                $('.mac').hide()
                $('.mac').val('').change()
                $('.loc').hide()
            }
        })
	</script>
@endpush