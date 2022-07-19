@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Materials') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.materials.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4">
				                    <label for="idMaterials">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($materials ? $materials->ID : '') }}" class="form-control" id="idMaterials" name="ID" readonly>
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="nameMaterials">{{__('Name')}} {{ __('Materials') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($materials ? $materials->Name : '') }}" class="form-control" id="nameMaterials" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" @if($show == null){ readonly }@endif required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                
				                <div class="form-group col-md-4">
				                    <label for="symbolsMaterials">{{__('Symbols')}} {{ __('Materials') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($materials ? $materials->Symbols : '') }}" class="form-control" id="symbolsMaterials" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" @if($show == null){ readonly }@endif required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="unitMat">{{__('Unit')}}</label>

				                    <select name="Unit_ID" class="form-control select2" required>
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Unit')}}
				                    	</option>
				                    	@foreach($units as $value)
				                    	<option value="{{$value->ID}}" {{old('Unit_ID') ? 
				                    		(old('Unit_ID') == $value->ID ? 'selected' : '') :
				                    		($materials ? ($materials->Unit_ID == $value->ID ? 'selected' : '') : '')}}>
				                    		{{$value->Symbols}}
				                    	</option>
				                    	@endforeach
				                    </select>
				                    
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Address')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="
				                    supMat">{{__('Supplier')}}</label>
				                    <select name="Supplier_ID" class="form-control select2" required>
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Supplier')}}
				                    	</option>
				                    	@foreach($suppliers as $value)
				                    	<option value="{{$value->ID}}" {{old('Supplier_ID') ? (old('Supplier_ID') == $value->ID ? 'selected' : '') : ($materials ? ($materials->Supplier_ID == $value->ID ? 'selected' : '') : '')}}>
				                    		{{$value->Symbols}}
				                    	</option>
				                    	@endforeach
				                    </select>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Phone')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								<div class="form-group col-md-4">
				                    <label for="
				                    noteMaterials">{{__('Difference')}}</label>
				                    <input type="Number" step='0.1' value="{{old('Difference')? old('Difference') : ($materials ? $materials->Difference : '') }}" class="form-control" id="
				                    noteMaterials" name="Difference" placeholder="{{__('Enter')}} {{__('Difference')}}" @if($show == null){ readonly }@endif>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Difference')}}</strong>
	                                    </span>
				                    @endif 
				                </div>
								<div class="form-group col-md-4">
				                    <label for="
				                    noteMaterials">{{__('Spec')}}</label>
				                    <input type="number" value="{{old('Spec')? old('Spec') : ($materials ? $materials->Spec : '') }}" class="form-control" id="
				                    noteMaterials" name="Spec" placeholder="{{__('Enter')}} {{__('Spec')}}" @if($show == null){ readonly }@endif>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Spec')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								<div class="form-group col-md-4">
				                    <label for="
				                    noteMaterials">{{__('Wire Type')}}</label>
				                    <input type="text" value="{{old('Wire_Type')? old('Wire_Type') : ($materials ? $materials->Wire_Type : '') }}" class="form-control" id="
				                    noteMaterials" step='0.1' name="Wire_Type" placeholder="{{__('Enter')}} {{__('Wire Type')}}" @if($show == null){ readonly }@endif>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Spec')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								<div class="form-group col-md-4">
									<label for="export_type"> {{__('Type')}} {{__('Export')}}</label>
									<select name="Export_Type" class="form-control select2" required>
										<option value="0" {{old('Export_Type') ? 
				                    		(old('Export_Type') == $value->ID ? 'selected' : '') :
				                    		($materials ? ($materials->Export_Type === 0 ? 'selected' : '') : '')}}>
											{{__('Export')}} {{__('Even')}}
										</option>
										<option value="1" {{old('Export_Type') ? 
				                    		(old('Export_Type') == $value->ID ? 'selected' : '') :
				                    		($materials ? ($materials->Export_Type === 1 ? 'selected' : '') : '')}}>
											{{__('Export')}} {{__('Odd')}}
										</option>
									</select>
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.materials') }}" class="btn btn-info">{{__('Back')}}</a>
		                	<button type="submit" class="btn btn-success float-right"  @if($show == null){ hidden="hidden" }@endif>{{__('Save')}}</button>
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