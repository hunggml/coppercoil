@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Supplier') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.supplier.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idsupplier">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($supplier ? $supplier->ID : '') }}" class="form-control" id="idsupplier" name="ID" readonly>
				                </div>

				                <div class="form-group col-md-6">
				                    <label for="nameSupplier">{{__('Name')}} {{ __('Supplier') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($supplier ? $supplier->Name : '') }}" class="form-control" id="nameSupplier" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                
				                <div class="form-group col-md-6">
				                    <label for="symbolssupplier">{{__('Symbols')}} {{ __('Supplier') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($supplier ? $supplier->Symbols : '') }}" class="form-control" id="symbolssupplier" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="addSupplier">{{__('Address')}}</label>
				                    <input type="text" value="{{old('Address')? old('Address') : ($supplier ? $supplier->Address : '') }}" class="form-control" id="addSupplier" name="Address" placeholder="{{__('Enter')}} {{__('Address')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Address')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="
				                    contactSupplier">{{__('Contact')}}</label>
				                    <input type="text" value="{{old('Contact')? old('Contact') : ($supplier ? $supplier->Contact : '') }}" class="form-control" id="
				                    contactSupplier" name="Contact" placeholder="{{__('Enter')}} {{__('Contact')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Contact')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="
				                    phoneSupplier">{{__('Phone')}}</label>
				                    <input type="text" value="{{old('Phone')? old('Phone') : ($supplier ? $supplier->Phone : '') }}" class="form-control" id="
				                    phoneSupplier" name="Phone" placeholder="{{__('Enter')}} {{__('Phone')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Phone')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="
				                    taxSupplier">{{__('Tax Code')}}</label>
				                    <input type="text" value="{{old('Tax_Code')? old('Tax_Code') : ($supplier ? $supplier->Tax_Code : '') }}" class="form-control" id="
				                    taxSupplier" name="Tax_Code" placeholder="{{__('Enter')}} {{__('Tax Code')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Tax_Code')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-8">
				                    <label for="
				                    noteSupplier">{{__('Note')}}</label>
				                    <input type="text" value="{{old('Note')? old('Note') : ($supplier ? $supplier->Note : '') }}" class="form-control" id="
				                    noteSupplier" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Note')}}</strong>
	                                    </span>
				                    @endif
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.supplier') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
		                	<button type="submit" class="btn btn-success float-right" style="width: 80px">{{__('Save')}}</button>
		                </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection