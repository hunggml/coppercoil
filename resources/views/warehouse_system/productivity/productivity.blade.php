@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif

	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('warehousesystem.productivity.import_file')])
	@include('basic.modal_table_error')
	@endif
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
						{{__('Report')}} {{__('Productivity')}}
	                	</span>
	                	<div class="card-tools">
							@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                            <button class="btn btn-success btn-import" style="width: 180px">
                            	{{__('Import By File Txt')}}
                        	</button>
							<button class="btn btn-warning btn-update" style="width: 180px"  data-toggle="modal" data-target=".modal-update">
							{{__('Report')}} {{__('Productivity')}}
                        	</button>
							<a href="{{route('warehousesystem.productivity.export_file',['Order'=>$request->Order,'Product'=>$request->Product,'Materials'=>$request->Materials,
								'from'=>$request->from,'to'=>$request->to])}}" class="btn btn-info " style="width: 180px">
                            	{{__('Export By File Excel')}}
							</a>
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
	                	<form action="{{ route('warehousesystem.productivity') }}" method="get">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Order')}}</label>
		                        	<select class="custom-select select2" name="Order">
		                          			<option value="">
		                          			{{__('Choose')}} {{__('Order')}}
		                          			</option>
										  @foreach($data_all as $value)
										  	<option value="{{$value->Order_ID}}" {{$request->Order_ID == $value->Order_ID ? 'selected' : ''}}>
		                          			{{$value->Order_ID}}
		                          			</option>
										  @endforeach
		                        	</select>
	                      		</div>
								  <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Product')}}</label>
		                        	<select class="custom-select select2" name="Product">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Product')}}
		                          		</option>
										  @foreach($data_all->GroupBy('Product_ID') as $key => $value)
										  	<option value="{{$key}}" {{$request->Product == $key ? 'selected' : ''}}>
		                          			{{ $value[0]->product ? $value[0]->product->Symbols : '' }}
		                          			</option>
										  @endforeach
		                        	</select>
	                      		</div>
								  <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Materials')}}</label>
		                        	<select class="custom-select select2" name="Materials">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Materials')}}
		                          		</option>
										  @foreach($data_all->GroupBy('Materials_Stock_ID') as $key => $value)
										  	<option value="{{$key}}" {{$request->Materials == $key ? 'selected' : ''}}>
											  {{ $value[0]->materials ? $value[0]->materials->Symbols : '' }}
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
		                		<th>{{__('Name')}} {{ __('Order') }}</th>
								<th>{{__('Product')}}</th>
		                		<th>{{__('Materials')}}</th>
								<th>{{__('Roll Number')}}</th>
								<th>{{__('OK')}}</th>
								<th>{{__('Quantity')}} {{__('OK')}} (kg)</th>
								<th>{{__('NG')}}</th>
								<th>{{__('Quantity')}} {{__('NG')}} (kg)</th>
								<th>{{__('Total')}} {{__('Quantity')}} (kg)</th>
		                		<th>{{__('User Updated')}}</th>
		                		<th>{{__('Time Updated')}}</th>
		                	</thead>
		                	<tbody>
								<?php $dem = 0 ?>
		                		@foreach($data as $value)
								<?php $dem ++ ?>
									<tr>
										<td>{{$dem}}</td>
										<td>{{$value->Order_ID}}</td>
										<td>{{$value->product ? $value->product->Symbols : ''}}</td>
										<td>{{$value->materials ? $value->materials->Symbols : ''}}</td>
										<td>{{floatval($value->Quantity)}}</td>
										<td>{{floatval($value->OK)}}</td>
										<td>{{floatval((($value->product ? $value->product->Quantity : 0) * ($value->OK)))}}</td>
										<td>{{floatval($value->NG)}}</td>
										<td>{{floatval((($value->product ? $value->product->Quantity : 0) * ($value->NG)))}}</td>
										<td>{{floatval((($value->product ? $value->product->Quantity : 0) * ($value->OK))) + floatval((($value->product ? $value->product->Quantity : 0) * ($value->NG)))}}</td>
										<td>
											{{$value->user_updated ? $value->user_updated->name : ''}}
										</td>
										<td>{{$value->Time_Updated}}</td>
									</tr>
								@endforeach	
		                	</tbody>
		                </table>
						{{ $data->links() }}
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

<div class="modal fade modal-update" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('Update')}} {{__('Report')}} {{__('Productivity')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('warehousesystem.productivity.update')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="form-group row">
				<div class="col-12">
					<label>{{ __('Order') }}</label>
					<select class="custom-select order select2" name="Order">
						<option value="">
							{{__('Choose')}} {{__('Type')}}
						</option>
					@foreach($data_all as $value)
						<option value="{{$value->ID}}">
						{{$value->Order_ID}}
						</option>
					@endforeach        
					</select>
				</div>
		        <div class="col-3">
					<label> ID </label>
					<input type="text" name="ID" id="ID" class="form-control" readonly>
				</div>
				
				<div class="col-3">
					<label> {{__('Product')}} </label>
					<input type="text" name="Product" id="Product" class="form-control" readonly>
				</div>
				<div class="col-3">
					<label> {{__('Materials')}} </label>
					<input type="text" name="Materials" id="Materials" class="form-control" readonly>
				</div>
				<div class="col-3">
					<label> {{__('Quantity')}} </label>
					<input type="text" name="Quantity" id="Quantity" class="form-control" readonly>
				</div>
				<div class="col-6">
					<label> {{__('OK')}} </label>
					<input type="number"  name="OK" id="OK" class="form-control" >
				</div>				
				<div class="col-6">
					<label> {{__('NG')}} </label>
					<input type="number" name="NG" id="NG" class="form-control" >
				</div>
	        </div>
			
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn btn-success hide btn-add">{{__('Save')}}</button>
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

		$('.order').on('input',function(){
                let a = $(this).val()
              	console.log(a)
				$.ajax({
                type: "GET",
                url: "{{route('warehousesystem.productivity.show')}}",
                data: { 
                   
                    ID:a,
                },
                success: function(data) 
                {
				    console.log(data.data)
					if(data.suc)
					{
						
						$('#ID').val(data.data.ID)
						$('#Product').val(data.data.product.Symbols)
						$('#Materials').val(data.data.materials.Symbols)
						$('#Quantity').val(data.data.Quantity)
						$('#OK').val(data.data.OK)
						$('#NG').val(data.data.NG)
						$('.btn-add').show()
					}
					else
					{
						$('#ID').val()
						$('#Product').val()
						$('#Materials').val()
						$('#Quantity').val()
						$('#OK').val()
						$('#NG').val()
						$('.btn-add').hide()
					}
                },
                error: function() {
                  
                }
			});
                
        })


        $('.btn-import').on('click', function()
        {
            $('#modalImport').modal();
            $('#importFile').val('');
            $('.input-text').text(__input.file);
            $('.error-file').hide();
            $('.btn-save-file').prop('disabled', false);
            $('#product_id').val('');

        });

        $('#importFile').on('change', function()
        {
            check_file   = false;
            let val      = $(this).val();
            let name     = val.split('\\').pop();
            let typeFile = name.split('.').pop().toLowerCase();
            $('.input-text').text(name);
            $('.error-file').hide();

            if (typeFile != 'xlsx' && typeFile != 'xls' && typeFile != 'txt') 
            {
                $('.error-file').show();
                $('.btn-save-file').prop('disabled', true);
            } else
            {
                $('.btn-save-file').prop('disabled', false);
                check_file = true;
            }
        });

        $('.btn-save-file').on('click', function()
        {
            $('.error-file').hide();

            if (check_file) 
            {
                $('.btn-submit-file').click();
            } else
            {
                $('.error-file').show();
            }
        });
	</script>
@endpush