@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
		@include('basic.modal_request_destroy', ['route' => route('account.destroy')])
	@endif

	@if(Auth::user()->level == 9999)
		@include('basic.modal_reset_pass')
	@endif

	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Account') }}
	                	</span>
	                	<div class="card-tools">
	                		@if(Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
	                		<a href="{{ route('account.show') }}" class="btn btn-warning" style="width: 180px">
                  				{{__('Create')}} {{__('Account')}}
                  			</a>
	                			@if(Auth::user()->level == 9999)
	                  			<a href="{{ route('account.role') }}" class="btn btn-success" style="width: 180px">
	                  				{{__('Role')}} {{__('Account')}}
	                  			</a>
                  				@endif
                  			@endif
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('masterData.unit.filter') }}" method="post">
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

	                	</form>
		                @include('basic.alert')
		            	</br>
		                <table class="table table-bordered text-nowrap w-100" id="tableUser" width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('User Name')}}</th>
		                		<th>{{__('Name Username')}}</th>
		                		<th>{{__('Email')}}</th>
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
			                			<td>{{$value->username}}</td>
			                			<td>{{$value->name}}</td>
			                			<td>{{$value->email}}</td>
										<td>{{$value->created_at}}</td>
			                			<td>{{$value->updated_at}}</td>
			                			<td>
	                						@if(Auth::user()->level == 9999)
				                				<button class="btn btn-warning btn-reset" style="width: 180px" id="reset-{{$value->id}}">
				                					{{__('Reset')}} {{__('Password')}}
				                				</button>
			                				@endif

			                				@if(Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
				                				<a href="{{ route('account.show', ['username' => $value->username]) }}" class="btn btn-success" style="width: 80px">
				                					{{__('Edit')}}
				                				</a>
			                				@endif

	                						@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
				                				<button id="del-{{$value->id}}" class="btn btn-danger btn-delete" style="width: 80px">
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

		$('#tableUser').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});

		$(document).on('click', '.btn-reset', function()
        {
            let id = $(this).attr('id');
            // let name = $(this).parent().parent().children('td').first().text();

            $('#modalResetPass').modal();
            $('#idReset').val(id.split('-')[1]);
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