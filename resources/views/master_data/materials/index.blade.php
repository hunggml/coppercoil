@extends('layouts.main')

@section('content')
	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.materials.destroy')])
	@endif

	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('masterData.materials.importFileExcel')])
	@include('basic.modal_table_error')
	@endif

	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Materials') }}
	                	</span>
	                	<div class="card-tools">
			               	@if(Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
	                		<a href="{{ route('masterData.materials.show') }}" class="btn btn-warning" style="width: 180px">
                  				{{__('Create')}}
                  			</a>
                  			@endif
			               	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                   			<button class="btn btn-success btn-import" style="width: 180px">
                            	{{__('Import By File Excel')}}
                        	</button>
                        	@endif
                        	@if(session()->has('table'))
			          			@if(session()->get('table') && session()->get('danger') != '')
				                	<button type="button" class="btn btn-danger btn-detail-error">
					                	{{__('Detail')}} {{__('Error')}}
					                </button>
			                	@endif
			                @endif
                  			@if(Auth::user()->checkRole('export_master') || Auth::user()->level == 9999)
                  			<a href="{{route('masterData.materials.export_file',[
								'Wire_Type'=>$request->Wire_Type,'Spec'=>$request->Spec])}}" class="btn btn-info " style="width: 180px">
                            	{{__('Export By File Excel')}}
							</a>
							@endif
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('masterData.materials.filter') }}" method="post">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Symbols')}}</label>
		                        	<select class="custom-select select2" name="Symbols">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Symbols')}}
		                          		</option>
		                          		@foreach($materialss as $value)
		                          			<option value="{{ $value->Symbols }}" {{$request->Symbols == $value->Symbols ? 'selected' : ''}}>
		                          				{{ $value->Symbols }}
		                          			</option>
		                          		@endforeach
		                        	</select>
	                      		</div>
	                      		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Name')}}</label>
		                        	<select class="custom-select select2" name="Name">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Name')}}
		                          		</option>
		                          		@foreach($materialss as $value)
		                          			<option value="{{ $value->Name }}" {{$request->Name == $value->Name ? 'selected' : ''}}>
		                          				{{ $value->Name }}
		                          			</option>
		                          		@endforeach
		                        	</select>
	                      		</div>
								  <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Spec')}}</label>
		                        	<select class="custom-select select2" name="Spec">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Spec')}}
		                          		</option>
		                          		@foreach($materialss as $value)
										    @if($value->Spec > 0)
		                          			<option value="{{ floatval($value->Spec) }}" {{$request->Spec == $value->Spec ? 'selected' : ''}}>
		                          				{{ floatval($value->Spec) }}
		                          			</option>
											@endif
		                          		@endforeach
		                        	</select>
	                      		</div>
								  <div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Wire Type')}}</label>
		                        	<select class="custom-select select2" name="Wire_Type">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Wire Type')}}
		                          		</option>
		                          		@foreach($materialss as $value)
										  @if($value->Wire_Type)
		                          			<option value="{{ $value->Wire_Type }}" {{ $request->Wire_Type ?  $request->Wire_Type == $value->Wire_Type ? 'selected' : '' : ''}}>
		                          				{{ $value->Wire_Type }}
		                          			</option>
										  @endif
		                          		@endforeach
		                        	</select>
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
		                <table class="table table-bordered text-nowrap w-100" id="tableMaterials" width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('Part Type')}}</th>
		                		<th>{{__('Part Number')}}</th>
		                		<th>{{__('Maker')}}</th>
								<th>{{__('Spec')}}</th>
								<th>{{__('Wire Type')}}</th>
		                		<th>{{__('Unit')}}</th>
		                		<th>{{__('Difference')}}</th>
		                		
		                		<th>{{__('User Updated')}}</th>
		                		<th>{{__('Time Updated')}}</th>
		                		<th>{{__('Action')}}</th>
		                	</thead>
		                	<tbody>
		                		@foreach($materials as $value)
			                		<tr>
			                			<th colspan="1">{{$value->ID}}</th>
			                			<td>{{$value->Name}}</td>
			                			<td>{{$value->Symbols}}</td>
				                		<td>
				                			{{$value->supplier ? $value->supplier->Symbols : ''}}
				                		</td>
										<td>{{$value->Spec}}</td>
										<td>{{$value->Wire_Type}}</td>
			                			<td>
			                				{{$value->unit ? $value->unit->Symbols : ''}}
			                			</td>
				                		
				                		<td>{{floatval($value->Difference)}}%</td>
										<td>
											{{$value->user_updated ? $value->user_updated->name : '--'}}
										</td>
			                			<td>{{$value->Time_Updated ? $value->Time_Updated : '--'}}</td>
			                			<td>
			               					@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
			                				<a href="{{ route('masterData.materials.show', ['ID' => $value->ID])}}" class="btn btn-success" style="width: 80px">
			                					{{__('Edit')}}
			                				</a>
			                				@endif
			               					@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
			                				<button id="del-{{$value->ID}}" class="btn btn-danger btn-delete" style="width: 80px">
			                					{{__('Delete')}}
			                				</button>
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
@endsection
@push('scripts')
	<script>
		$('.select2').select2()

		$('#tableMaterials').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});

		let tableError = $('#tableError').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%',
			info    : false,
		});

		let tabErr = "<?php echo(session()->has('table')) ?>";
		let dataErr = '';
		if (tabErr)
		{
			dataErr = "<?php echo(session()->get('dataError')) ?>";
		}

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

            if (typeFile != 'xlsx' && typeFile != 'xls') 
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


        $('.btn-detail-error').on('click', function()
        {
        	$('#modalTableError').modal();
        	tableError.clear();
			let arr = dataErr.split(',');
			let i   = 1;
        	for(let err of arr)
        	{
        		if (err != '') 
        		{
        			tableError.row.add([
	        			i,
	        			err
	        		]);
        			i++;
        		}
        	}
        	
        	setTimeout(function()
        	{
        		tableError.columns.adjust().draw();
        	}, 300);

        });
        $(document).on('click', '.btn-delete', function()
		{
			let id = $(this).attr('id');
			let name = $(this).parent().parent().children('td').first().text();

			$('#modalRequestDel').modal();
			$('#nameDel').text(name);
			$('#idDel').val(id.split('-')[1]);
		});
	</script>
@endpush