@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Role') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('account.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row"> 
		                		<div class="col-md-6">
			                    	<div class="form-group col-md-12 hide">
					                    <label for="idUnit">{{__('ID')}}</label>
					                    <input type="text" value="{{old('ID') ? old('ID') : ($data ? $data->ID : '') }}" class="form-control" id="idUser" name="ID" readonly>
					                </div>
					                <div class="form-group col-md-12">
					                    <label for="nameUser">{{__('Name')}} {{__('Role')}}</label>
					                    <input type="text" value="{{old('name')? old('name') : ($data ? $data->name : '') }}" class="form-control" id="nameUser" name="name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
					                    @if($errors->any())
					                    	<span role="alert">
		                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
		                                    </span>
					                    @endif
					                </div>

					                <div class="form-group col-md-12">
					                    <label for="description">{{__('Description')}}</label>
					                    <input type="text" value="{{old('username')? old('username') : ($data ? $data->username : '') }}" class="form-control" id="description" name="username" placeholder="{{__('Enter')}} {{__('Description')}}" required>
					                    @if($errors->any())
					                    	<span role="alert">
		                                        <strong style="color: red">{{$errors->first('username')}}</strong>
		                                    </span>
					                    @endif
					                </div>

					                <div class="form-group col-md-12">
					                    <label for="email">{{__('Function')}}</label>
					                    <select name="" id="func" class="form-control select2">
					                    	<option value="">
					                    		{{__('Choose')}} {{__('Function')}}
					                    	</option>
					                    	<option value="supplier">
					                    		{{__('Setting')}} {{__('Supplier')}}
					                    	</option>
					                    	<option value="materials">
					                    		{{__('Setting')}} {{__('Materials')}}
					                    	</option>
					                    	<option value="group_materials">
					                    		{{__('Setting')}} {{__('Group Materials')}}
					                    	</option>
					                    	<option value="product">
					                    		{{__('Setting')}} {{__('Product')}}
					                    	</option>
					                    	<option value="bom">
					                    		{{__('Setting')}} {{__('BOM')}}
					                    	</option>
					                    	<option value="car">
					                    		{{__('Setting')}} {{__('Car')}}
					                    	</option>
					                    	<option value="warehouse">
					                    		{{__('Setting')}} {{__('Warehouse')}}
					                    	</option>
					                    	<option value="import">
					                    		{{__('Import')}}
					                    	</option>
					                    	<option value="export">
					                    		{{__('Export')}}
					                    	</option>
					                    	<option value="inventory">
					                    		{{__('Inventory')}}
					                    	</option>
					                    </select>
					                </div>
		                		</div>
		                		<div class="col-md-6">
		                			<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="view" value="option1">
		                          		<label for="view" class="custom-control-label">{{__('View')}}</label>
		                        	</div>
		                        	<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="create" value="option1">
		                          		<label for="create" class="custom-control-label">{{__('Create')}}</label>
		                        	</div>
		                        	<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="edit" value="option1">
		                          		<label for="edit" class="custom-control-label">{{__('Edit')}}</label>
		                        	</div>
		                        	<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="delete" value="option1">
		                          		<label for="delete" class="custom-control-label">
		                          			{{__('Delete')}} {{__('Or')}} {{__('Destroy')}}
		                          		</label>
		                        	</div>
		                        	<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="import" value="option1">
		                          		<label for="import" class="custom-control-label">
		                          			{{__('Imprort File Excel')}}
		                          		</label>
		                        	</div>
		                        	<div class="custom-control custom-checkbox col-md-12">
		                          		<input class="custom-control-input" type="checkbox" id="createNew" value="option1">
		                          		<label for="createNew" class="custom-control-label">
		                          			{{__('Inventory')}}
		                          		</label>
		                        	</div>
		                		</div>             

			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('account') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
		                	<button type="submit" class="btn btn-success float-right" style="width: 80px">{{__('Save')}}</button>
		                </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@push('scripts')
	<script>
		$('.select2').select2();
	</script>
@endpush