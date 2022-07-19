@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Error') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="#">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idError">{{__('ID')}}</label>
				                    <input type="text" value="" class="form-control" id="idError" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="nameError">{{__('Name')}} {{ __('Error') }}</label>
				                    <input type="text" value="" class="form-control" id="nameError" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="symbolsError">{{__('Symbols')}} {{ __('Error') }}</label>
				                    <input type="text" value="" class="form-control" id="symbolsError" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                </div>
                                <div class="form-group col-md-4">
				                    <label for="NoteError">{{__('Note')}} {{ __('Error') }}</label>
				                    <input type="text" value="" class="form-control" id="NoteError" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" required>
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="#" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
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