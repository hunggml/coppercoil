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
						{{__('Import-Export-Inventory')}}
	                	</span>
	                	<div class="card-tools">
							@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                            
							<a href="{{route('warehousesystem.report.export_file',['Materials'=>$request->Materials,'Warehouse'=>$request->Warehouse,'Type'=>$request->Type,
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
	                	<form action="{{ route('warehousesystem.report') }}" method="get">
	                		@csrf
	                		<div class="row">
								<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Materials')}}</label>
		                        	<select class="custom-select select2" name="Materials">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Materials')}}
		                          		</option>
										@foreach($list_materials as $value)
										<option value="{{$value->ID}}" {{$request->Materials == $value->ID ? 'selected' : '' }}>
		                          			{{$value->Symbols}}
		                          		</option>
										@endforeach
		                        	</select>
	                      		</div>
                                <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Warehouse')}}</label>
		                        	<select class="custom-select select2" name="Warehouse">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Warehouse')}}
		                          		</option>
										@foreach($list_warehouse as $value)
										<option value="{{$value->ID}}" {{$request->Warehouse == $value->ID ? 'selected' : '' }}>
		                          			{{$value->Symbols}}
		                          		</option>
										@endforeach
		                        	</select>
	                      		</div>
								<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Type')}}</label>
		                        	<select class="custom-select select2" name="Type">
		                          		<option value="0" {{$request->Type == 0 ? 'selected' : '' }}>
		                          			{{__('Quantity')}}
		                          		</option>
										<option value="1" {{$request->Type == 1 ? 'selected' : '' }}>
		                          			{{__('Roll Number')}}
		                          		</option>
		                        	</select>
	                      		</div>
	                      		<div class="form-group col-md-2">
		                        	<label>{{ __('From') }}</label>
		                        	<input type="date" value="{{$request->from}}" class="form-control datetime"  name="from">
	                      		</div>
                                <div class="form-group col-md-2">
		                        	<label>{{ __('To') }}</label>
		                        	<input type="date" value="{{$request->to}}" class="form-control datetime"  name="to">
	                      		</div>
	                      		<div class="col-md-2" style="margin-top: 33px">
	                      			<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
	                      		</div>
                      		</div>
	                	</form>
		            	</br>
		                <table class="table table-bordered text-nowrap " width="100%"  id="table">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('Materials')}}</th>
								<th>{{__('Spec')}}</th>
								<th>{{__('First')}}</th>
								<th>{{__('Import')}}</th>
								<th>{{__('Retype')}}</th>
		                		<th>{{__('Import Inventory')}}</th>
		                		<th>{{__('Export')}}</th>
		                		<th>{{__('Export Inventory')}}</th>
		                		<th>{{__('End')}}</th>
		                	</thead>
		                	<tbody>
								<?php $dem = 0 ?>
		                		@foreach($data as $value)
                                <?php $dem ++ ?>
								@if(!$request->Type)
                                <tr>
                                    <td>{{$dem}}</td>
                                    <td>{{$value->Symbols}}</td>
                                    <td>{{$value->Spec}}</td>
                                    <td>{{$value->first1}}</td>
                                    <td>{{$value->imm1}}</td>
                                    <td>{{$value->imm2}}</td>
                                    <td>{{$value->imm3}}</td>
                                    <td>{{$value->exx1}}</td>
                                    <td>{{$value->exx2}}</td>
                                    <td>{{($value->first1 + $value->imm1 + $value->imm2 + $value->imm3 - $value->exx1 -$value->exx2 ) < 0.000000001 ? 0 : ($value->first1 + $value->imm1 + $value->imm2 + $value->imm3 - $value->exx1 -$value->exx2 ) }}</td>
                                </tr>
								@else
								<tr>
                                    <td>{{$dem}}</td>
                                    <td>{{$value->Symbols}}</td>
                                    <td>{{$value->Spec}}</td>
                                    <td>{{$value->first}}</td>
                                    <td>{{$value->im1}}</td>
                                    <td>{{$value->im2}}</td>
                                    <td>{{$value->im3}}</td>
                                    <td>{{$value->ex1}}</td>
                                    <td>{{$value->ex2}}</td>
                                    <td>{{($value->first + $value->im1 + $value->im2 + $value->im3 - $value->ex1 -$value->ex2 ) < 0.000000001 ? 0 : ($value->first + $value->im1 + $value->im2 + $value->im3 - $value->ex1 -$value->ex2 ) }}</td>
                                </tr>
								@endif
                                @endforeach
		                	</tbody>
		                </table>
	                </div>
	            </div>
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
		$('#table').DataTable({
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
						$('#Ok').val(data.data.Ok)
						$('#NG').val(data.data.Ng)
						$('.btn-add').show()
					}
					else
					{
						$('#ID').val()
						$('#Product').val()
						$('#Materials').val()
						$('#Quantity').val()
						$('#Ok').val()
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