@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif

	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('warehousesystem.import.import_file')])
	@include('basic.modal_table_error')
	@endif
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Command') }} {{ __('Inventory') }}
	                	</span>
	                	<div class="card-tools">
							@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                   			<button class="btn btn-success" style="width: 180px" data-toggle="modal" data-target=".modal-add-commad">
                               {{__('Create')}} {{__('Command')}}
                        	</button>
                        	@endif
                        	@if(session()->has('table'))
			          			@if(session()->get('table') && count(session()->get('danger')) >0 )
				                	<button type="button" class="btn btn-danger btn-detail-error">
					                	{{__('Detail')}} {{__('Error')}}
					                </button>
			                	@endif
			                @endif
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('warehousesystem.inventory') }}" method="get">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Name')}} {{ __('Command') }}</label>
		                        	<select class="custom-select select2" name="name">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Name')}}
		                          		</option>
										@foreach($data_all as $value)
										<option value="{{$value->Name}}" {{$request->name == $value->Name ? 'selected' : '' }}>
		                          			{{$value->Name}}
		                          		</option>
										@endforeach
		                        	</select>
	                      		</div>
	                      		<div class="form-group col-md-2">
		                        	<label>{{ __('From') }}</label>
		                        	<input type="date" value="{{$request->from}}" class="form-control datetime"  name="from"  >
	                      		</div>
                                <div class="form-group col-md-2">
		                        	<label>{{ __('To') }}</label>
		                        	<input type="date" value="{{$request->to}}" class="form-control datetime"  name="to"  >
	                      		</div>
	                      		<div class="col-md-2" style="margin-top: 33px">
	                      			<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
	                      		</div>
                      		</div>

	                	</form>
		                @if(session()->has('success'))
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								<strong>{{session()->get('success')}}</strong>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif
						@if(session()->has('danger'))
							@foreach(session('danger') as $value)

							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								<strong>{{$value}}</strong>
								<br>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endforeach
						@endif
		            	</br>
		                <table class="table table-striped table-hover"  width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('Name')}} {{ __('Command') }}</th>
								<th>{{__('Trạng Thái')}}</th>
		                		<th>{{__('Note')}}</th>
		                		<th>{{__('User Created')}}</th>
		                		<th>{{__('Time Created')}}</th>
		                		<th>{{__('User Updated')}}</th>
		                		<th>{{__('Time Updated')}}</th>
		                		<th>{{__('Action')}}</th>
		                	</thead>
		                	<tbody>
								<?php $dem = 0 ?>
		                		@foreach($data as $value)
								<?php $dem ++ ?>
									<tr>
										<td>{{$dem}}</td>
										<td>{{$value->Name}}</td>
										<td>{{$value->Status == 0 ? __('Waiting') : ($value->Status == 1 ? __('Success') : __('Cancel')) }}</td>
										<td>{{$value->Note}}</td>
										<td>
											{{$value->user_created ? $value->user_created->name : ''}}
										</td>
										<td>{{$value->Time_Created}}</td>
										<td>
											{{$value->user_updated ? $value->user_updated->name : ''}}
										</td>
										<td>{{$value->Time_Updated}}</td>
										<td>
											<a href="{{route('warehousesystem.inventory.detail',['ID'=>$value->ID])}}" class="btn btn-detail btn-info">
											{{__('Detail')}}
                                           	</a>
											@if($value->Status == 0)
											<a href="{{route('warehousesystem.inventory.success',['Command_ID'=>$value->ID])}}" class="btn btn-success">
                                            {{__('Success')}}
											</a>
										   	<a href="{{route('warehousesystem.inventory.cancel',['Command_ID'=>$value->ID])}}" class="btn btn-danger btn-cancel">
                                            {{__('Cancel')}}
											</a>
											@endif
										</td>
									</tr>
								@endforeach
		                	</tbody>
		                </table>
						
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

<div class="modal fade modal-add-commad" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('Create')}} {{__('Command')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('warehousesystem.inventory.add')}}" method="post">
        @csrf
        <div class="modal-body">
            <label> {{__('Name')}} </label>
            <input type="text" name="Name" id="idCan5" class="form-control " >
            <label> {{__('Note')}} </label>
            <input type="text" name="Note" id="idCan5" class="form-control " >
            <div class="form-group">
		        <label>{{ __('Type') }}</label>
		        <select class="custom-select type select2" name="Status">
                    <option value="">
		                {{__('Choose')}} {{__('Type')}}
		            </option>
		            <option value="1">
		                {{__('Materials')}}
		            </option>
                    <option value="2">
		                {{__('Warehouse')}}
		            </option>
                    <option value="3">
		                 {{__('Location')}}
		            </option>           
		        </select>
	        </div>
            <br>
            <div class="form-group col-md-12 mater hide">
                <select class="duallistbox form-control" Name="Materials[]"   multiple="multiple" style='' >
                    @foreach($list_materials as $value)
						<option value="{{$value->ID}}" >
                        {{$value->Symbols}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 ware hide">
                <select class="duallistbox form-control" Name="Warehouse[]"   multiple="multiple" style='' >
                    @foreach($warehouse as $value)
						<option value="{{$value->ID}}" >
                        {{$value->Symbols}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 local hide">
                <select class="duallistbox form-control" Name="Location[]"   multiple="multiple" style='' >
                    @foreach($list_location as $value)
						<option value="{{$value->ID}}" >
                        {{$value->Symbols}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn btn-success hide btn-add">{{__('Create')}}</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
	<script>
		$('.select2').select2()
        $('.duallistbox').bootstrapDualListbox({
			nonSelectedListLabel: __warehouses.no_select_com,
			selectedListLabel   : __warehouses.select_com,
			infoText            : __group.show+' {0}',
			infoTextEmpty       : __group.empty,
			showFilterInputs    : true,
			filterPlaceHolder   : __filter.symbols
		});
		$('#tableUnit').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});

		$('.type').on('input',function(){
                let a = $(this).val()
                if(a == 1)
                {
                    $('.btn-add').show()
                    $('.mater').show()
                    $('.ware').hide()
                    $('.local').hide()
                }
                else if(a==2)
                {
                    $('.btn-add').show()
                    $('.ware').show()
                    $('.mater').hide()
                    $('.local').hide()
                }
                else if(a==3)
                {
                    $('.btn-add').show()
                    $('.local').show()
                    $('.mater').hide()
                    $('.ware').hide()
                }
                else
                {
                    $('.btn-add').hide()
                    $('.mater').hide()
                    $('.ware').hide()
                    $('.local').hide()
                }
        })



	</script>
@endpush