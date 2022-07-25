@extends('layouts.main')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Product') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.product.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4">
				                    <label for="idProduct">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($product ? $product->ID : '') }}" class="form-control" id="idProduct" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="nameProduct">{{__('Name')}} {{ __('Product') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($product ? $product->Name : '') }}" class="form-control" id="nameProduct" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" @if($show == null){ readonly }@endif required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-4">
				                    <label for="symbolsProduct">{{__('Symbols')}} {{ __('Product') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($product ? $product->Symbols : '') }}" class="form-control" id="symbolsProduct" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" @if($show == null){ readonly }@endif required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-4">
				                    <label for="unitProduct">{{__('Unit')}}</label>

				                    <select name="Unit_ID" class="form-control select2" required>
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Unit')}}
				                    	</option>
				                    	@foreach($units as $value)
				                    	<option value="{{$value->ID}}" {{old('Unit_ID') ? 
				                    		(old('Unit_ID') == $value->ID ? 'selected' : '') :
				                    		($product ? ($product->Unit_ID == $value->ID ? 'selected' : '') : '')}}>
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
				                    <label for="MaterialsProduct">{{__('Materials')}}</label>
				                    <select name="Materials_ID" class="form-control select2 mater" >
				                    	<option value="">
				                    		{{__('Choose')}} {{__('Materials')}}
				                    	</option>
				                    	@foreach($materials as $value)
				                    	<option value="{{$value->ID}}" >
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
				                <div class="form-group col-md-3 ">
				                    <label for="symbolsProduct">{{__('Quantity')}}</label>
				                    <input type="number" step="0.01" value="" class="form-control quantity" id="QuantityProduct" name="Quantity" placeholder="{{__('Enter')}} {{__('Quantity')}}" @if($show == null){ readonly }@endif >
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Quantity')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								<div class="col-1 quan">
									<button type="button" class="btn btn-success btn-add" style="margin-top:22%">{{__('Add')}}</button>
								</div>
								@if($product)
									@foreach($product->boms as $bom)
									<div class="col-3 tr-{{$bom->Materials_ID}}">
										<label class="mater"> {{__('Materials')}} ID</label>
										<input type="text" name="Materials_ID[]" id="idCan66" value="{{$bom->Materials_ID}}" class="form-control" readonly>
									</div>
									<div class="col-4 tr-{{$bom->Materials_ID}}">
										<label class="mater"> {{__('Materials')}}</label>
										<input type="text" name="Count" id="idCan66" value="{{$bom->materials ? $bom->materials->Symbols : '' }}" class="form-control" readonly>
									</div>
									<div class="col-3 tr-{{$bom->Materials_ID}}">
										<label class="mater"> {{__('Quantity')}} {{__('Use')}}</label>
										<input type="text" value="{{floatval($bom->Quantity_Materials)}}" name="QuantityUse[]" id="qtyuse-{{$bom->Materials_ID}}" class="form-control qtyuse">
									</div>
									<div class="col-2 tr-{{$bom->Materials_ID}} ">
										<button type="button" id="{{$bom->Materials_ID}}" class="btn btn-danger btn-delete-box" style="margin-top:10%">
												{{__('Delete')}}
										</button>
									</div>	
									@endforeach
								@endif		
				                <div class="form-group col-md-8">
				                    <label for="symbolsProduct">{{__('Note')}}</label>
				                    <input type="text" value="{{old('Note') ? old('Note') : ($product ? $product->Note : '') }}" class="form-control" id="NoteProduct" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" @if($show == null){ readonly }@endif >
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Note')}}</strong>
	                                    </span>
				                    @endif
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.product') }}" class="btn btn-info">{{__('Back')}}</a>
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
		$('.select2').select2()
		let arr = [];
		
		@if($product)
		@foreach($product->boms as $bom)
			arr.push({{$bom->Materials_ID}});						
		@endforeach
		@endif
		
		$('.btn-add').on('click',function(){
			let a = $('.mater :selected').val();
			let b = $('.mater :selected').text();
			let c = $('#QuantityProduct').val();
			// console.log(a,arr,c);
			if(a !== '' && $.inArray(a, arr ) == '-1' && c > 0)
			{
				arr.push(a);
				$('.quan').after(`
					<div class="col-3 tr-`+a+`">
						<label class="mater"> {{__('Materials')}} ID</label>
						<input type="text" name="Materials_ID[]" id="idCan66" value="`+a+`" class="form-control" readonly>
					</div>
					<div class="col-4 tr-`+a+`">
						<label class="mater"> {{__('Materials')}}</label>
						<input type="text" name="Count" id="idCan66" value="`+b.trim()+`" class="form-control" readonly>
					</div>
					<div class="col-3 tr-`+a+`">
						<label class="mater"> {{__('Quantity')}} {{__('Use')}}</label>
						<input type="text" value="`+c+`" name="QuantityUse[]" id="qtyuse-`+a+`" class="form-control qtyuse">
					</div>
					<div class="col-2 tr-`+a+` ">
						<button type="button" id="`+a+`" class="btn btn-danger btn-delete-box" style="margin-top:10%">
								{{__('Delete')}}
						</button>
					</div>
				`) 
				$('.btn-delete-box').on('click',function(){
					$('.tr-'+$(this).attr('id')+'').remove()
					arr.splice($.inArray($(this).attr('id'), arr),1);				
				})
			}
		})
		$('.btn-delete-box').on('click',function(){
			console.log('run');
			$('.tr-'+$(this).attr('id')+'').remove()
			arr.splice($.inArray($(this).attr('id'), arr),1);				
		})
	</script>
@endpush