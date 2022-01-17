@extends('layouts.main')

@section('content')

@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
@endif

	<!-- @if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('warehousesystem.import.import_file')])
	@include('basic.modal_table_error')
	@endif -->
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<span class="text-bold" style="font-size: 23px">
							{{ __('List') }} {{ __('Command') }} {{ __('Export') }}
						</span>
						<div class="card-tools">
							@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
							<button class="btn btn-success btn-import" data-toggle="modal" data-target=".bd-example-modal-lg" style="width: 180px">
								{{ __('Create') }}  {{ __('Command') }} {{ __('Export') }}
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
						<form action="{{ route('warehousesystem.export') }}" method="get">
							@csrf
							<div class="row">
								<div class="form-group col-md-2">
									<label>{{__('Choose')}} {{__('Symbols')}} {{ __('Command') }}</label>
									<select class="custom-select select2" name="materials">
										<option value="">
											{{__('Choose')}} {{__('Symbols')}}
										</option>
										@foreach($materials as $value)
										<option value="{{$value->ID}}" {{$request ? ($request->materials == $value->ID ? 'selected' : '') : ''}}>
											{{$value->Symbols}}
										</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-md-1">
									<label>{{__('Choose')}} {{__('Type')}}</label>
									<select class="custom-select select2" name="type">
										<option value="">
											{{__('Choose')}} {{__('Type')}}
										</option>
										<option value="1" {{$request ? ($request->type == 1 ? 'selected' : ''): ''}}>
											{{__('PDA')}}
										</option>
										<option value="2" {{$request ? ($request->type == 2 ? 'selected' : ''): ''}}>
											{{__('Software')}}
										</option>
										<option value="3" {{$request ? ($request->type == 3 ? 'selected' : ''): ''}}>
											{{__('System')}}
										</option>
									</select>
								</div>
								<div class="form-group col-md-1">
									<label>{{__('Choose')}} {{__('Warehouse')}} {{__('Go')}}</label>
									<select class="custom-select select2" name="warehouse_go">
										<option value="">
											{{__('Choose')}} {{__('Warehouse')}}
										</option>
										@foreach($warehouse as $value)
										<option value="{{$value->ID}}" {{$request ? ($request->warehouse_go == $value->ID ? 'selected' : '') : ''}}>
											{{$value->Symbols}}
										</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-md-1">
									<label>{{__('Choose')}} {{__('Warehouse')}} {{__('To')}}</label>
									<select class="custom-select select2" name="warehouse_to">
										<option value="">
											{{__('Choose')}} {{__('Warehouse')}}
										</option>
										@foreach($warehouse as $value)
										<option value="{{$value->ID}}" {{$request ? ($request->warehouse_to == $value->ID ? 'selected' : '') : ''}}>
											{{$value->Symbols}}
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
								<div class="form-group col-md-2">
									<label>{{__('Choose')}} {{__('Status')}}</label>
									<select class="custom-select select2" name="status">
										<option value="">
											{{__('Choose')}} {{__('Status')}}
										</option>
										<option value="0" {{$request ? ($request->status === 0 ? 'selected' : ''): ''}}>
											{{__('Waiting')}} {{__('Accept')}} {{__('Bout')}} 1
										</option>
										<option value="1" {{$request ? ($request->status === 1 ? 'selected' : ''): ''}}>
											{{__('Waiting')}} {{__('Accept')}} {{__('Bout')}} 2
										</option>
										<option value="2" {{$request ? ($request->status == 2 ? 'selected' : ''): ''}}>
											{{__('Waiting Export')}}
										</option>
										<option value="3" {{$request ? ($request->status == 3 ? 'selected' : ''): ''}}>
											{{__('Success')}}
										</option>
										
										<option value="4" {{$request ? ($request->status == 4 ? 'selected' : ''): ''}}>
											{{__('Cancel')}}
										</option>
										
									</select>
								</div>
								<div class="col-md-1" style="margin-top: 33px">
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
							<th>{{__('Type')}}</th>
							<th>{{__('Name')}}</th>
							<th>{{__('Materials')}} {{__('Symbols')}}</th>
							<th>{{__('Warehouse')}} {{__('Go')}}</th>
							<th>{{__('Warehouse')}} {{__('To')}}</th> 
							<th>{{__('Quantity')}} {{__('To Be Exported')}}</th>
							<th>{{__('Quantity')}} {{__('Exported')}}</th>
							<th>{{__('Quantity')}} {{__('Is')}} {{__('Transfer')}}</th>
							<th>{{__('Unit')}}</th>
							<th>{{__('Status')}}</th>
							<th>{{__('User Created')}}</th>
							<th>{{__('Time Created')}}</th>
							<th>{{__('Action')}}</th>
						</thead>
						<tbody>
							<?php $dem = 0 ?>
							@foreach($data as $value)
							<?php $dem++;  ?>
							<tr>
								<td>{{$dem}}</td>
								<td>{{$value->Type == 0 ? __('Software') : ($value->Type == 1 ? __('PDA') : __('System')  ) }}</td>
								<td>{{$value->Type == 0 ? __('PM') : ($value->Type == 1 ? __('PDA') : __('HT')  ) }}-{{date_format(date_create($value->Time_Created),"YmdHis")}}</td>
								<td>{{$value->materials ? $value->materials->Symbols : ''}}</td>
								<td>{{$value->go ? $value->go->Symbols : '' }}</td>
								<td>{{$value->to ? $value->to->Symbols : '' }}</td>
								<td id="com_{{$value->ID}}">{{$value->Count ? $value->Count : floatval($value->Quantity) }}</td>
								<?php $a = $value->Count ? $value->Count : $value->Quantity; ?>
								<?php $b = $value->Count ? (count($value->detail->where('Status',1))) : floatval(collect($value->detail->where('Status',1))->sum('Quantity')); ?>
								<td id="trans_{{$value->ID}}">{{$value->Count ? (count($value->detail->where('Status',1))) : floatval(collect($value->detail->where('Status',1))->sum('Quantity')) }}</td>
								@if($value->Go == $value->To ) 
								<td>0</td>
								@else
								<td>{{$value->Count ? (count($value->detail->where('Transfer',1))) : floatval(collect($value->detail->where('Transfer',1))->sum('Quantity')) }}</td>
								@endif 
								<td>{{$value->Count ? 'Box' : 'Kg' }}</td>
								@if( (floatval($a) - floatval($b)) < 0 && (($value->go ? $value->go->Symbols : '') != ($value->to ? $value->to->Symbols : '')))
									<td>
										{{__('Waiting')}} {{__('Transfer')}} {{__('Warehouse')}}
									</td>
									@else 
									@if((floatval($a) - floatval($b)) <= 0 && $value->Status != 3 ) 
										<td>
											{{__('Waiting')}} {{__('Success')}} 
										</td>
										@else
										<td>
											{{$value->Status == 0 || $value->Status == 1 ? __('Waiting') : ($value->Status == 2 ?  __('Waiting Export') : ( $value->Status == 3 ? __('Success') :   __('Cancel'))  ) }} {{$value->Status == 0 || $value->Status == 1 ? __('Accept') : '' }} {{$value->Status == 0 || $value->Status == 1 ? __('Bout') : '' }} {{$value->Status == 0 ? '1' : '' }} {{$value->Status == 1 ? '2' : '' }}
										</td>
										@endif
										@endif
										<td>
											{{$value->user_created ? $value->user_created->name : ''}}
										</td>
										<td>{{$value->Time_Created}}</td>
										
										@if($value->Status == 0)
										<td>
											<button class="btn btn-warning btn-accept" id="btn-{{$value->ID}}" >
												{{ __('Accept') }}
											</button>
											<button class="btn btn-danger btn-cancel" id="btn-{{$value->ID}}" >
												{{ __('Cancel') }}
											</button>
										</td>
										@elseif($value->Status == 1)
										<td>
											<button class="btn btn-warning btn-accept" id="btn-{{$value->ID}}" >
												{{ __('Accept') }}
											</button>
											<button class="btn btn-danger btn-cancel" id="btn-{{$value->ID}}" >
												{{ __('Cancel') }}
											</button>
										</td>
										@elseif($value->Status == 2)
										<td>
											<a href="{{route('warehousesystem.export.detail',['ID'=>$value->ID])}}" class="btn btn-info" >
												{{ __('Detail') }}
											</a>
											@if((floatval($b)) > 0 && (($value->go ? $value->go->Symbols : '') != ($value->to ? $value->to->Symbols : '')))
												<button class="btn btn-light btn-Transfer" id="btn-{{$value->To}}-{{$value->ID}}" data-toggle="modal" data-target=".modal-tranfer" >
													{{ __('Transfer') }} {{ __('Warehouse') }}
												</button>
												@endif
												<a href="{{route('warehousesystem.export.success',['ID'=>$value->ID])}}" class="btn btn-success" >
													{{ __('Success') }}
												</a>
											</td>
											@else
											<td>
												<a href="{{route('warehousesystem.export.detail',['ID'=>$value->ID])}}" class="btn btn-info"  >
													{{ __('Detail') }}
												</a>
											</td>
											@endif
										</tr>
										@endforeach
									</tbody>
								</table>
								<hr>
								<div style="float:right">
									{{$data->links()}}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- modal add export -->
			<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">{{ __('Create') }}  {{ __('Command') }} {{ __('Export') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="warehouse">
								<label>{{__('Choose')}} {{__('Warehouse')}}  {{__('Go')}}</label>
								<select class="custom-select ware select2" name="Warehouse_Detail_ID">
									<option value="">
										{{__('Choose')}} {{__('Warehouse')}}
									</option>
									@foreach($warehouse as $value)
									<option value="{{$value->ID}}">
										{{$value->Symbols}}
									</option>
									@endforeach
								</select>
								<span style="color :red; font-size:10px" class=" err hide">{{__('Warehouse No More')}} {{__('Materials')}}</span>
							</div>
							
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
							<button type="button" class="btn btn-add hide float-right btn-warning ">{{__('Export')}}</button>
						</div>
					</div>
				</div>
			</div>

<!-- 
	modal_cancel -->

	<div class="modal fade" id="modalRequestCan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="title">{{__('Cancel')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('warehousesystem.export.cancel') }}" method="get">
					@csrf
					<div class="modal-body">
						<strong style="font-size: 23px">{{__('Do You Want To Cancel')}} <span id="nameDel" style="color: blue"></span> ?</strong>
						<input type="text" name="ID" id="ID" class="form-control hide">
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
						<button type="submit" class="btn btn-danger">{{__('Cancel')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>

<!-- 
	modal_accept -->

	<div class="modal fade" id="modalRequestAC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="title">{{__('Accept')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('warehousesystem.export.accept') }}" method="get">
					@csrf
					<div class="modal-body">
						<strong style="font-size: 23px">{{__('Do You Want To Accept')}} <span id="nameDel" style="color: blue"></span> ?</strong>
						<input type="text" name="ID" id="ID1" class="form-control hide">
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
						<button type="submit" class="btn btn-danger">{{__('Accept')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- modal tranfer -->
	<div class="modal fade modal-tranfer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{__('Transfer')}}  {{__('Warehouse')}}</h5>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<p class="err_tran hide" style="color:red; font-size:15px; text-align:center;font-weight: bold;">{{__('Uncomplete Export')}}</p>
				<div class="modal-body transfer-body">
					
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
					<button type="button" class="btn  btn-save-transfer float-right btn-warning ">{{__('Transfer')}}  {{__('Warehouse')}}</button>
				</div>
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
			scrollY : '100%'
		});
		$(document).on('click', '.btn-delete', function()
		{
			let id = $(this).attr('id');
			let name = $(this).parent().parent().children('td').first().text();
			$('#modalRequestDel').modal();
			$('#nameDel').text(name);
			$('#ID').val(id.split('-')[1]);
		});
		$(document).on('click', '.btn-cancel', function()
		{   
			console.log('run')
			let id = $(this).attr('id');
			let name = $(this).parent().parent().children('td').first().text();
			$('#modalRequestCan').modal();
			$('#nameDel').text(name);
			$('#ID').val(id.split('-')[1]);
			
		});
		$(document).on('click', '.btn-accept', function()
		{   
			console.log('run')
			let id = $(this).attr('id');
			let name = $(this).parent().parent().children('td').first().text();
			$('#modalRequestAC').modal();
			$('#nameDel').text(name);
			$('#ID1').val(id.split('-')[1]);
			
		});
		$(document).on('input', '.ware', function()
		{
			let id = $('.ware').val();
			$('.new').remove();
			$.ajax({
				type: "GET",
				url: "{{route('masterData.warehouses.get_list_materials_in_warehouse')}}",
				data: { 
					Warehouse_ID : id
				},
				success: function(data) 
				{
					if(data.success)
					{
						$('.err').hide()
						let a = ``
						$.each(data.data , function (index,value){
							a = a + `
							<option value="`+value.Materials_ID+`" class="`+value.Quantity+`  `+value.Count+` ">`+value.Materials+`</option>
							`
						})
						$('.warehouse').append(`
							<div class="new">
							<div class="mater  mater1">
							<label>{{__('Choose')}} {{__('Materials')}} </label>
							<select class="custom-select mater2 select2" name="Materials">
							<option value="">
							{{__('Choose')}} {{__('Materials')}}
							</option>
							`+a+`
							</select>
							</div>
							<input type="text" name="Materials_ID" id="idCan5" class="form-control hide mater" readonly>
							<label class="mater"> {{__('Quantity')}} {{__('Stock')}}</label>
							<input type="text" name="Inventory" id="idCan6" class="form-control  mater" readonly>
							<label  class="mater"> {{__('Quantity')}} {{__('Export')}}</label>
							<input type="Number" name="Quantity" id="idCan7" min='0' class="form-control  mater"  step="0.01">
							<span style="color :red; font-size:10px" class=" err1 hide mater">{{__('Quantity Requested Greater Than Allowed Quantity')}}</span>
							<br>
							<label class="mater"> {{__('Roll Number')}} {{__('Stock')}}</label>
							<input type="text" name="Count" id="idCan66" class="form-control " readonly>
							<label class="mater">{{__('Export')}} {{__('Roll Number')}}</label>
							<input type="Number" name="Count" id="idCan77" min='0' class="form-control  mater"  step="1">
							<span style="color :red; font-size:10px" class=" err2 hide mater">{{__('Quantity Requested Greater Than Allowed Quantity')}}</span>
							<div class="mater">
							<label>{{__('Choose')}} {{__('Warehouse')}}  {{__('To')}}</label>
							<select class="custom-select ware1 select2" name="To">
							<option value="">
							{{__('Choose')}} {{__('Warehouse')}}
							</option>
							@foreach($warehouse as $value)
							<option value="{{$value->ID}}">
							{{$value->Symbols}}
							</option>
							@endforeach
							</select>
							<span style="color :red; font-size:10px" class=" err hide">{{__('Warehouse No More')}} {{__('Materials')}}</span>
							</div>
							</div>
							`)   
						$('.select2').select2()
                            //  $(".ware1 option[value='"+id+"']").remove();
                            $('.mater2').on('input',function(){
                            	let a = $('.mater :selected').text();
                            	let b = $('.mater :selected').attr('class');
                            	let c = $('.mater :selected').val();
                            	$('#idCan5').val(c)
                            	$('#idCan6').val(b.split(' ')[0])
                            	$('#idCan66').val(b.split(' ')[2])
                            	$('#idCan7').attr('max',b)
                            	console.log(a,b.split(' '),c)
                            })
                            $('#idCan7').on('input',function(){
                            	
                            	let a = $('#idCan6').val()
                            	let b = $('#idCan7').val()
                            	
                            	if(Number(b)>Number(a))
                            	{
                            		$('.btn-add').hide()
                            		$('.err1').show()
                            	}
                            	else
                            	{
                            		$('.btn-add').show()
                            		$('.err1').hide()
                            	}
                            })
                            $('#idCan77').on('input',function(){
                            	
                            	let a = $('#idCan66').val()
                            	let b = $('#idCan77').val()
                            	
                            	if(Number(b)>Number(a))
                            	{
                            		$('.btn-add').hide()
                            		$('.err2').show()
                            	}
                            	else
                            	{
                            		$('.btn-add').show()
                            		$('.err2').hide()
                            	}
                            })
                          }
                          else
                          {
                          	$('.btn-add').hide()
                          	$('.mater').remove()
                          	$('.err').show()
                          }    
                        },
                        error: function() {
                        	
                        }
                      });
});
$('.btn-add').on('click',function(){
	
	let a = $('.ware').val()
	let b = $('.mater2').val()
	let c = $('#idCan7').val()
	let d = $('.ware1').val()
	let e = $('#idCan77').val()
	console.log(a,b,c,d,e)
	$.ajax({
		type: "get",
		url: "{{route('warehousesystem.export.add')}}",
		data: { 
			'_token' : $('meta[name="csrf-token"]').attr('content'),
			Go : a,
			Materials_ID :b,
			Quantity : c,
			Count : e,
			To  : d,
		},
		success: function(data) 
		{
			if(data.data)
			{
				location.reload();
			}
		},
		error: function() {
		}
	});
})
$('.btn-Transfer').on('click',function(){
	let id = $(this).attr('id')
	$('.transfer-box').remove()
	$('.table-box').remove()
	$('.transfer-ware').remove()
	let com = $('#com_'+id.split('-')[2]).text()
	let tran = $('#trans_'+id.split('-')[2]).text()
	if( com > tran )
	{
		$('.err_tran').show()
	}
	else
	{
		$('.err_tran').hide()
	}
	$.ajax({
		type: "get",
		url: "{{route('masterData.warehouses.filter_warehouse')}}",
		data: { 
			'_token' : $('meta[name="csrf-token"]').attr('content'),
			ID : id.split('-')[1],
			Export_ID : id.split('-')[2],
		},
		success: function(data) 
		{
			console.log(data.data)
			if(data.data.length > 0)
			{
				let a =''
				let b =''
				$.each(data.data[0].detail , function (index,value){
					a = a + `
					<option value="`+value.ID+`" >`+value.Symbols+`</option>
					`
				})
				$.each(data.list_Box , function (index,value){
                        // console.log(value)
                        if (value.Transfer == 0) 
                        {
                        	b = b + `
                        	<option value="`+value.ID+`" 
                        	class="`+value.Box_ID+`/`+value.Pallet_ID+`/`+value.materials.Symbols+`/`+value.materials.Spec+`/`+value.Quantity+`/`+value.location.Symbols+`">`
                        	+value.Box_ID+
                        	`</option>
                        	`
                        }
                      })
				$('.transfer-body').append(`
					<input type="text" name="ID" value="`+id.split('-')[2]+`" id=Export_ID" class="form-control hide" >
					<div class="transfer-box">
					<label>{{__('Choose')}} {{__('Box')}}</label>
					<select class="custom-select box-To select2" name="box-To">
					<option value="">
					{{__('Choose')}} {{__('Box')}}
					</option>
					`+b+`
					</select>
					</div>
					<br>
					<div class="table-box" >
					<table class="table table-striped table-hover"  >
					<thead>
					<th>{{__('BOXID')}} </th>
					<th>{{__('Pallet')}}</th>
					<th>{{__('Materials')}}</th>
					<th>{{__('Spec')}}</th>
					<th>{{__('Quantity')}}(kg)</th>
					<th>{{__('Location')}}</th>
					</thead>
					<tbody class="table-detail-box">
					</tbody>
					</div>
					<div class="transfer-ware">
					<label>{{__('Choose')}} {{__('Warehouse')}} {{__('To')}} </label>
					<select class="custom-select Location-To select2" name="Location-To">
					<option value="">
					{{__('Choose')}} {{__('Location')}}
					</option>
					`+a+`
					</select>
					</div>
					<br>
					`)   
				$('.select2').select2()
				$('.box-To').on('input',function(){
					$('.detail-box').remove()
					let a = $('.box-To :selected').attr('class')
					console.log(a)
					$('.table-detail-box').append(`
						<tr class="detail-box">
						<td>`+a.split('/')[0]+`</td>
						<td>`+a.split('/')[1]+`</td>
						<td>`+a.split('/')[2]+`</td>
						<td>`+a.split('/')[3]+`</td>
						<td>`+parseFloat(a.split('/')[4])+`</td>
						<td>`+a.split('/')[5]+`</td>
						</tr>
						`)
				})
			}
		},
		error: function() {
		}
	});
})
$('.btn-save-transfer').on('click', function(){
	let a = $('#Export_ID').val()
	let c = $('.box-To').val()
	let b = $('.Location-To').val()
	console.log(a,b)
	$.ajax({
		type: "get",
		url: "{{route('warehousesystem.transfer.add_transfer')}}",
		data: { 
			'_token' : $('meta[name="csrf-token"]').attr('content'),
			Export_ID : a,
			Detail_ID :c,
			To : b,
		},
		success: function(data) 
		{
			if(data.data)
			{
				location.reload();
			}
		},
		error: function() {
		}
	});
})
</script>
@endpush