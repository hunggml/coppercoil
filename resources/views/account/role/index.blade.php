@extends('layouts.main')

@section('content')
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])

	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Role') }} {{__('Account')}}
	                	</span>
	                	<div class="card-tools">
	                		<a href="{{ route('account.role.show') }}" class="btn btn-warning">
                  				{{__('Create')}} {{ __('Role') }}
                  			</a>
	                	</div>
	                </div>
	                <div class="card-body">
	                	<!-- <form action="{{ route('masterData.unit.filter') }}" method="post">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('User Name')}}</label>
		                        	<select class="custom-select select2" name="Symbols">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Symbols')}}
		                          		</option>
		                          		@foreach($data as $value)
		                          			<option value="{{ $value->username }}">
		                          				{{ $value->username }}
		                          			</option>
		                          		@endforeach
		                        	</select>
	                      		</div>

	                      		<div class="col-md-2" style="margin-top: 33px">
	                      			<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
	                      		</div>
                      		</div>

	                	</form> -->
		                @include('basic.alert')
		            	</br>
		                <table class="table table-striped table-hover" id="tableUser" width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('Role')}}</th>
		                		<th>{{__('Description')}}</th>
		                		<!-- <th>{{__('User Created')}}</th> -->
		                		<th>{{__('Time Created')}}</th>
		                		<!-- <th>{{__('User Updated')}}</th> -->
		                		<th>{{__('Time Updated')}}</th>
		                		<th>{{__('Action')}}</th>
		                	</thead>
		                	<tbody>
		                		@foreach($data as $value)
			                		<tr>
			                			<th colspan="1">{{$loop->index + 1}}</th>
			                			<td>{{$value->role}}</td>
			                			<td>{{$value->description}}</td>
										<td>{{$value->time_created}}</td>
			                			<td>{{$value->time_updated}}</td>
			                			<td></td>
			                			<!-- <td>
			                				<a href="{{ route('account.show', ['username' => $value->username]) }}" class="btn btn-success" style="width: 80px">
			                					{{__('Edit')}}
			                				</a>
			                				<button id="del-{{$value->ID}}" class="btn btn-danger btn-delete" style="width: 80px">
			                					{{__('Delete')}}
			                				</button>
			                			</td> -->
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

		$('#tableUser').DataTable({
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
            $('#idDel').val(id.split('-')[1]);
        });
	</script>
@endpush