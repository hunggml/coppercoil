@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Warehouse') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.warehouses.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idWarehouse">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($warehouse ? $warehouse->ID : '') }}" class="form-control" id="idWarehouse" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-4 name">
				                    <label for="nameWarehouse">{{__('Name')}} {{__('Warehouse')}}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($warehouse ? $warehouse->Name : '') }}" class="form-control" id="nameWarehouse" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4 symbols">
				                    <label for="symbolSwarehouse">{{__('Code')}} {{__('Warehouse')}}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($warehouse ? $warehouse->Symbols : '') }}" class="form-control" id="symbolSwarehouse" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="groupMaterialsWare">
				                    	{{__('Group Materials')}}
				                    </label>
				                   	<select name="Group_Materials_ID" class="form-control select2" id="groupMaterials">
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Group Materials')}}
				                    	</option>
				                    	@foreach($groups as $value)
				                    	<option value="{{$value->ID}}" 
				                    		{{old('Group_Materials_ID') ? (old('Group_Materials_ID') == $value->ID ? 'selected' : '') : ($warehouse ? ($warehouse->Group_Materials_ID == $value->ID ? 'selected' : '') : '')}} class="{{$value->Unit_ID}}-{{$value->Quantity}}">
				                    		{{$value->Symbols}}
				                    	</option>
				                    	@endforeach
				                    </select>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Group_Materials_ID')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-3 rowpan">
				                    <label for="quantityRow">{{__('Quantity Columns')}}</label>
				                    <input type="number" min="1" value="{{old('Quantity_Rows') ? old('Quantity_Rows') : ($warehouse ? $warehouse->Quantity_Rows : '') }}" class="form-control" id="quantityRow" name="Quantity_Rows" placeholder="{{__('Enter')}} {{__('Quantity Columns')}}" 
				                     >
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Quantity_Rows')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-3 colpan">
				                    <label for="quantityColumn">
				                    	{{__('Quantity Row')}}
				                    </label>
				                    <input type="number" min="1" value="{{old('Quantity_Columns') ? old('Quantity_Columns') : ($warehouse ? $warehouse->Quantity_Columns : '') }}" class="form-control " id="quantityColumn" name="Quantity_Columns" placeholder="{{__('Enter')}} {{__('Quantity Row')}}" >
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Quantity_Columns')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								
				                <!-- <div class="form-group col-md-3">
				                    <label for="mac">{{__('MAC')}}</label>
				                    <input type="text" value="{{old('MAC') ? old('MAC') : ($warehouse ? $warehouse->MAC : '') }}" class="form-control" id="mac" name="MAC" placeholder="{{__('Enter')}} {{__('MAC')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('MAC')}}</strong>
	                                    </span>
				                    @endif
				                </div> -->

				                <!-- <div class="form-group col-md-3">
				                    <label for="quantityUnit">{{__('Quantity Unit')}}</label>
				                    <input type="number" min="1" step="0.0000001" value="{{old('Quantity_Unit') ? old('Quantity_Unit') : ($warehouse ? (floatval($warehouse->Quantity_Unit) == '0.0' ? '' : $warehouse->Quantity_Unit) : '') }}" class="form-control" id="quantityUnit" name="Quantity_Unit" placeholder="{{__('Enter')}} {{__('Quantity Unit')}}">
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Quantity_Unit')}}</strong>
	                                    </span>
				                    @endif
				                </div> -->

				                <!-- <div class="form-group col-md-3">
				                    <label for="unitWare">{{__('Unit')}}</label>
				                    <select name="Unit_ID" class="form-control select2" id="unitID">
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Unit')}}
				                    	</option>
				                    	@foreach($units as $value)
				                    	<option value="{{$value->ID}}" {{old('Unit_ID') ? 
				                    		(old('Unit_ID') == $value->ID ? 'selected' : '') :
				                    		($warehouse ? ($warehouse->Unit_ID == $value->ID ? 'selected' : '') : '')}}>
				                    		{{$value->Symbols}}
				                    	</option>
				                    	@endforeach
				                    </select>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Address')}}</strong>
	                                    </span>
				                    @endif
				                </div> -->
				                <div class="form-group col-md-3">
				                    <label for="quantityPacking">
				                    	{{__('Quantity Packing')}}
				                    </label>
				                    <input type="number" min="1" step="0.0000001" value="{{old('Quantity_Packing') ? old('Quantity_Packing') : ($warehouse ? (floatval($warehouse->Quantity_Packing) == '0.0' ? '' : $warehouse->Quantity_Packing) : '') }}" class="form-control" id="quantityPacking" name="Quantity_Packing" placeholder="{{__('Enter')}} {{__('Quantity Packing')}}">
				                </div>
								<div class="form-group col-md-3">
									<label for="Accept">{{__('Accept')}}</label>
									<select id="Accept" name="Accept" class="form-control select2">
									<option value="0" {{ ($warehouse ? ($warehouse->Accept == '0' ? 'selected' : ''):'') }}>
										{{__('Accept')}}
									</option>
									<option value="1" {{ ($warehouse ? ($warehouse->Accept == '1' ? 'selected' : '') : '') }}>
										{{__('Dont')}} {{__('Accept')}}
									</option>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label for="Email">{{__('Email')}}</label>
									<input type="text"  class="form-control" id="Email" value="{{old('Email') ? old('Email') : ($warehouse ? ($warehouse->Email == '' ? '' : $warehouse->Email) : '') }}" name="Email" placeholder="{{__('Enter')}} {{__('Email')}}">
								</div>
								<div class="form-group col-md-3">
									<label for="Email2">{{__('Email')}}2</label>
									<input type="text"  class="form-control" id="Email2" value="{{old('Email2') ? old('Email2') : ($warehouse ? ($warehouse->Email2 == '' ? '' : $warehouse->Email2) : '') }}" name="Email2" placeholder="{{__('Enter')}} {{__('Email')}} 2">
								</div>
								<div class="form-group col-md-4 row">
				                    <label for="Area" class="col-12">
				                    	{{__('Area')}}
				                    </label>
									<div class="area_select col-6">
										<select name="Area" class="form-control select2" >
											<option value="">
												{{__('Choose')}} {{__('Area')}}
											</option>
											@foreach($area as $value)
											<option value="{{$value->ID}}" 
												{{old('Area') ? (old('Area') == $value->ID ? 'selected' : '') : ($warehouse ? ($warehouse->Area == $value->ID ? 'selected' : '') : '')}} class="">
												{{$value->Name}}
											</option>
				                    	@endforeach
				                    </select>
									</div>
									<div class="area_input col-6 hide">
										<input type="text"  class="form-control area_input hide" id="Area" value="" name="Area1" placeholder="{{__('Enter')}} {{__('Area')}}">
									</div>
									<button type="button" class="btn btn-success float-right col-6 btn-input" >{{__('Create')}} {{__('Area')}}</button>
									<button type="button" class="btn btn-success float-right col-6 btn-select hide" >{{__('Choose')}} {{__('Area')}}</button>
				                </div>
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.warehouses') }}" class="btn btn-info">{{__('Back')}}</a>
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
		let a = $('#idWarehouse').attr('value')
		// console.log(a)
		if (a) 	
		{
			$('.rowpan').hide();
			$('.colpan').hide();
			$('.name').hide();
			$('.symbols').hide();

		}
		$('.select2').select2();
		
		$('#groupMaterials').on('change', function()
		{
			let myClss = $('#groupMaterials option:selected').attr('class')
			console.log(myClss)
			$('#quantityUnit').val(myClss.split('-')[1])
			$('#unitID').val(myClss.split('-')[0]).select2()
		});
		$('.btn-input').on('click',function(){
				$('.area_select').val('')
				$('.area_select').hide()
				$('.area_input').show()
				$('.btn-input').hide()
				$('.btn-select').show()
				
				
		})
		$('.btn-select').on('click',function(){
				$('.area_input').val('')
				$('.area_select').show()
				$('.area_input').hide()
				$('.btn-input').show()
				$('.btn-select').hide()
		})
	</script>
@endpush