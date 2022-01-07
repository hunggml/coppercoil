@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Group Materials') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.groupMaterials.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">
							   	<div class="form-group col-md-4 hide">
				                    <label for="idMaterials">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($data ? $data->ID : '') }}" class="form-control" id="idMaterials" name="ID" readonly>
	                            </div>

		                    	<div class="form-group col-md-6">
				                    <label for="nameMaterials">{{__('Group Materials')}}</label>
				                    <input type="text" value="{{old('Name') ? old('Name') : ($data ? $data->Name : '') }}" class="form-control" id="nameMaterials" name="Name" required placeholder="{{__('Enter')}} {{__('Name')}}">
				                    @if($errors->any())
				                    	<span role="alert">
		                                    <strong style="color: red">{{$errors->first('Name')}}</strong>
		                                </span>
				                    @endif
	                            </div>
	                            @if(false)
	                            <div class="form-group col-md-3">
				                    <label for="symbolsMaterials">{{__('Symbols')}}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($data ? $data->Symbols : '') }}" class="form-control" id="symbolsMaterials" name="Symbols" required placeholder="{{__('Enter')}} {{__('Symbols')}}">
				                    @if($errors->any())
				                    	<span role="alert">
		                                    <strong style="color: red">{{$errors->first('Symbols')}}</strong>
		                                </span>
				                    @endif
	                            </div>
				                @endif

	                            <div class="form-group col-md-3">
				                    <label for="symbolsMaterials">{{__('Quantity')}} {{__('Max')}}</label>
				                    <input type="number" min="1" value="{{old('Quantity') ? old('Quantity') : ($data ? $data->Quantity : '') }}" class="form-control" id="symbolsMaterials" name="Quantity" required placeholder="{{__('Enter')}} {{__('Quantity')}}">
				                    @if($errors->any())
				                    	<span role="alert">
		                                    <strong style="color: red">{{$errors->first('Quantity')}}</strong>
		                                </span>
				                    @endif
	                            </div>
	                            <div class="form-group col-md-3">
				                    <label for="unitID">{{__('Unit')}}</label>
				                    <select class="select2 form-control" id="unitID" Name="Unit_ID">
										@foreach($units as $unit)
											<option value="{{$unit->ID}}" {{old('Unit_ID') ? old('Unit_ID') : ($data ? ($data->Unit_ID == $unit->ID) : '') }}>
												{{$unit->Symbols}}
											</option>
										@endforeach									
                                	</select>
				                    @if($errors->any())
				                    	<span role="alert">
		                                    <strong style="color: red">{{$errors->first('Unit')}}</strong>
		                                </span>
				                    @endif
	                            </div>
	                     		@if(false)
	                            <div class="form-group col-md-12">
				                    <label for="noteParking">{{__('Note')}}</label>
				                    <textarea type="text" value="" class="form-control" id="noteParking" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" >{{old('Note') ? old('Note') : ($data ? $data->Note : '') }}</textarea>
				                </div>
				                @endif
                                <div class="form-group col-md-12">
                                	<select class="duallistbox form-control" Name="Group[]"   multiple="multiple" style='' >
										@foreach($materials as $mat)
											<option value="{{$mat->ID}}" {{old('Group') ? 
												(in_array($mat->ID, old('Group')) ? 'selected' : '') : 
												($selects ? ($selects->contains('Materials_ID', $mat->ID) ? 'selected' : '') : '')}}>{{$mat->Symbols}}</option>
										@endforeach									
                                	</select>
                                </div>
							</div>
						</div>
		                <div class="card-footer">
		                	<a href="{{route('masterData.groupMaterials')}}" class="btn btn-info">{{__('Back')}}</a>
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
		$('.duallistbox').bootstrapDualListbox({
			nonSelectedListLabel: __materials.materials,
			selectedListLabel   : __materials.select,
			infoText            : __group.show+' {0}',
			infoTextEmpty       : __group.empty,
			showFilterInputs    : true,
			filterPlaceHolder   : __filter.symbols
		});

		$('.select2').select2()
	</script>
@endpush