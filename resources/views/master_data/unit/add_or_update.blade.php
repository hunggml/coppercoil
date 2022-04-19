@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Unit') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.unit.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idUnit">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($unit ? $unit->ID : '') }}" class="form-control" id="idUnit" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="nameUnit">{{__('Name')}} {{ __('Unit') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($unit ? $unit->Name : '') }}" class="form-control" id="nameUnit" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="symbolsUnit">{{__('Symbols')}} {{ __('Unit') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($unit ? $unit->Symbols : '') }}" class="form-control" id="symbolsUnit" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="typeUnit">{{__('Type')}} </label>
				                     <select name="Type" class="form-control select2" required>
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Type')}}
				                    	</option>
				                    	<option value=1 {{$unit ? ($unit->Type == 1 ? 'selected' : '') : ''}}>
				                    		{{__('Unit')}}
				                    	</option>
				                    	<option value=2 {{$unit ? ($unit->Type == 2 ? 'selected' : '') : ''}}>
				                    		{{__('Packing')}}
				                    	</option>
				                    </select>
				                    
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Type')}}</strong>
	                                    </span>
				                    @endif
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.unit') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
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