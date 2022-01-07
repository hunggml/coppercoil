@extends('layouts.main')

@section('content')
@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
@include('basic.modal_import', ['route' => route('warehousesystem.import.import_file')])
@include('basic.modal_table_error') 
@endif
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<span class="text-bold" style="font-size: 23px">
						{{ __('Update') }} {{ __('Location') }} 
					</span>
					<div class="card-tools">
						<button class="btn btn-success btn-update" data-toggle="modal" data-target=".modal-update" >
							{{ __('Update') }} {{ __('Location') }}
						</button>
					</div>
				</div>
				<div class="card-body">
					<form action="{{ route('warehousesystem.update_location') }}" method="get">
						@csrf
						<div class="row">
							<div class="form-group col-md-2">
								<input type="text" name="ID" value="{{$request->ID}}" class="form-control hide">
								<label>{{__('Choose')}} {{__('Symbols')}} {{ __('Materials') }}</label>
								<select class="custom-select select2" name="Materials_ID">
									<option value="">
										{{__('Choose')}} {{__('Symbols')}}
									</option>
									@foreach($list_materials as $value)
									<option value="{{$value->ID}}" {{$request ? ($request->Materials_ID == $value->ID ? 'selected' : '') : ''}}>
										{{$value->Symbols}}
									</option>
									@endforeach
								</select>

							</div>
							<div class="form-group col-md-2">
								<label>{{ __('Location') }} {{ __('Go') }}</label>
								<select class="custom-select select2" name="Lo_Go" >
									<option value="">
										{{__('Choose')}} {{__('Location')}}
									</option>
									@foreach($list_location as $key => $value)
									<option value="{{$value->ID}}"{{$request ? ($request->Lo_Go == $value->ID ? 'selected' : '') : ''}}>
										{{$value->Symbols}}
									</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-2">
								<label>{{ __('Location') }} {{ __('To') }}</label>
								<select class="custom-select select2" name="Lo_To">
									<option value="">
										{{__('Choose')}} {{__('Location')}}
									</option>
									@foreach($list_location as $key => $value)
									<option value="{{$value->ID}}"{{$request ? ($request->Lo_To == $value->ID ? 'selected' : '') : ''}}>
										{{$value->Symbols}}
									</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-2">
								<label>{{ __('From') }}</label>
								<input type="date" value="{{$request->from}}" class="form-control datetime"  name="from"  >
							</div>

							<div class="form-group col-md-2">
								<label>{{ __('To') }}</label>
								<input type="date" value="{{$request->to}}" class="form-control datetime"  name="to"  >
							</div>
							<div class="col-md-2" style="margin-top: 33px">
								<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
							</div>
						</div>
					</form>
					@include('basic.alert')
				</br>
				<table class="table table-striped table-hover"  width="100%" id ="tableUnit">
					<thead>
						<th>{{__('ID')}}</th>
						<th>{{__('Symbols')}} {{ __('Materials') }}</th>
						<th>{{__('Pallet')}}</th>
						<th>{{__('Box')}}</th>
						<th>{{__('Location')}} {{__('Go')}}</th>
						<th>{{__('Location')}} {{__('To')}}</th>
						<th>{{__('Quantity')}}(kg)</th>
						<th>{{__('User Created')}}</th>
						<th>{{__('Time Created')}}</th>
						<th>{{__('User Updated')}}</th>
						<th>{{__('Time Updated')}}</th>
					</thead>
					<tbody>
						<?php $dem = 0 ?>
						@foreach($data as $key=> $value)
						<?php $dem++;  ?>
						<tr>
							<td>{{$dem}}</td>
							<td>{{$value->materials ? $value->materials->Symbols : ''}}</td>
							<td>{{$value->Pallet_ID}}</td>
							<td>{{$value->Box_ID}}</td>
							<td>{{$value->location_go ? $value->location_go->Symbols : ''}}</td>
							<td>{{$value->location_to ? $value->location_to->Symbols : ''}}</td>
							<td>{{floatval($value->Quantity)}}</td>
							<td>
								{{$value->user_created ? $value->user_created->name : ''}}
							</td>
							<td>{{$value->Time_Created}}</td>
							<td>
								{{$value->user_updated ? $value->user_updated->name : ''}}
							</td>
							<td>{{$value->Time_Updated}}</td>       
						</tr>
						@endforeach
					</tbody>
				</table>
				@if(count($data) >0)
                {{ $data->links() }}
                @endif
			</div>
		</div>
	</div>
</div>
</div>
<!-- modal update location -->
<div class="modal fade modal-update" tabindex="-1"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{__('Update')}}  {{__('Location')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body update-body">
				<form method="POST" action="{{route('warehousesystem.update_location.update_location')}}">
					@csrf
					<label>{{__('Choose')}} {{__('Type')}}</label>
					<select class="custom-select select-type select2">
						<option value="1">
							{{__('Choose')}} {{__('Type')}}
						</option>
						<option value="3">
							{{__('Pallet')}}
						</option>
						<option value="2">
							{{__('Box')}}
						</option>
					</select>

					<div class="choose-pallet hide">
						<label>{{__('Choose')}} {{__('Pallet')}}</label>
						<select class="custom-select pallet-To select2" name="Pallet_ID">
							<option value="">
								{{__('Choose')}} {{__('Pallet')}}
							</option>
							@foreach($pallets->GroupBy('Pallet_ID') as $key => $value)
							@if($key != '')
							<option value="{{$key}}" class="">
								{{$key}}
							</option>
							@endif
							@endforeach
						</select>
						<div class="form-group ">
			              <label> {{__('Pallet')}} </label>
			              <input type="text" name="Pallet_ID" id="idCan5" class="form-control " readonly>
			              <label> {{__('Materials')}} </label>
			              <input type="text" name="Materials" id="idCan6" class="form-control " readonly>
			              <label> {{__('Spec')}} </label>
			              <input type="text" name="Spec" id="idCan7" class="form-control " readonly>
			              <label> {{__('Quantity')}}(kg) </label>
			              <input type="text" name="Quantity" id="idCan8" class="form-control " readonly>
			              <label> {{__('Roll Number')}} </label>
			              <input type="text" name="Count" id="idCan9" class="form-control " readonly>
			            </div>
			            <label>{{__('Choose')}} {{__('Location')}} {{__('New')}}</label>
						<select class="custom-select location-To2 select2 loca" name="Warehouse_Detail_ID_pallet" >
							<option value="">
							{{__('Choose')}} {{__('Location')}}
							</option>
							@foreach($list_location as $value)
			                  <option value="{{$value->ID}}">
			                    {{$value->Symbols}}
			                  </option>
			                @endforeach
						</select>
					</div>

					<div class="choose-box hide">
						<label>{{__('Choose')}} {{__('Box')}}</label>
						<select class="custom-select box-To select2" name="Box_ID">
							<option value="">
							{{__('Choose')}} {{__('Box')}}
							</option>
							@foreach($boxs->GroupBy('Box_ID') as $key => $value)
							<option value="{{$key}}" >
								{{$key}}
							</option>
							@endforeach
						</select>
						<label>{{__('Choose')}} {{__('Location')}} {{__('New')}}</label>
						<select class="custom-select location-To select2 loca" name="Warehouse_Detail_ID_box" >
							<option value="">
							{{__('Choose')}} {{__('Location')}}
							</option>
							@foreach($list_location as $value)
			                  <option value="{{$value->ID}}">
			                    {{$value->Symbols}}
			                  </option>
			                @endforeach
						</select>
					</div>
					<br>
					<button type="submit" class="btn  btn-save-update float-right btn-warning hide">{{__('Update')}}  {{__('Location')}}</button>
					<button type="button" class="btn btn-secondary btn-close float-right" data-dismiss="modal">{{__('Close')}}</button>
					<br>
		            <br>
		            <label> {{__('Detail')}} </label>
				</form>
				
			</div>
		</div>
	</div>
</div>
@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
@include('basic.modal_request_destroy', ['route' => route('warehousesystem.import.destroy')])
@endif
@endsection
@push('scripts')
<script>
	$('.select2').select2()

	$('.select-type').change( function() {
		let a = $('.select-type').val()
		// console.log(a)
		if (a == 2) 
		{
			// console.log('run1')
			$('.choose-box').show();
			$('.choose-pallet').hide();
		}
		else if (a == 3)
		{
			// console.log('run2')
			$('.choose-pallet').show();
			$('.choose-box').hide();
		}
		else
		{
			$('.choose-pallet').hide();
			$('.choose-box').hide();
		}
	})

	$('.btn-update').on('click',function(){
		let id = $(this).attr('id')
		$('.update-box').remove()
		$('.table-box').remove()
		$('.update-ware').remove()
		
			
		$('.update-body').append(`
			<div class="table-box" >
			<table class="table table-striped table-hover"  >
			<thead>
			<th>{{__('STT')}} </th>
			<th>{{__('BOXID')}} </th>
			<th>{{__('Pallet')}}</th>
			<th>{{__('Materials')}}</th>
			<th>{{__('Spec')}}</th>
			<th>{{__('Quantity')}}(kg)</th>
			<th>{{__('Location')}}</th>
			</thead>
			<tbody class="table-detail-box">
			</tbody>
			</div>
			<div class="update-ware">
			</div>
			<br>
			`)   
		$('.select2').select2()
		$('.box-To').on('input',function(){
			$('.detail-box').remove()
			let a = $('.box-To :selected').attr('value')
			// console.log(a)
			let dem = 1;
			$.ajax({
			    type: "GET",
			    url: "{{route('warehousesystem.update_location.get_list_box')}}",
			    data: {
			      Box_ID: a,
			    },
			    success: function(data) 
			    {
			    	console.log(data.data)
			      
			      let d = ''
			      $('.detail-box').remove()
			      
			      	// console.log(value)
			        d = d +`
			        <tr class="detail-box">
			        <td>`+dem+`</td>
			        <td>`+data.data.Box_ID+`</td>
			        <td>`+data.data.Pallet_ID+`</td>
			        <td>`+data.data.materials.Symbols+`</td>
			        <td>`+data.data.materials.Spec+`</td>
			        <td>`+parseFloat(data.data.Quantity)+`</td>
			        <td>`+data.data.location.Symbols+`</td>

			        </tr>
			        `
			      
			      $('.table-detail-box').append(
			        d   
			        )
			    },
			    error: function() {

			    }
			  });
		})

		$('.pallet-To').on('input',function(){
			// $('.detail-box').remove()
      		let p = $('.pallet-To :selected').attr('value')
			 $.ajax({
			    type: "GET",
			    url: "{{route('warehousesystem.update_location.get_list_pallet')}}",
			    data: { 
			      Pallet_ID: p,
			    },
			    success: function(data) 
			    {
			      // console.log(data.data)
			      let a = parseFloat(data.data.Quantity)
			      $('#idCan5').val(data.data.Pallet_ID);
			      $('#idCan6').val(data.data.Materials);
			      $('#idCan7').val(data.data.Spec);
			      $('#idCan8').val(a);
			      $('#idCan9').val(data.data.Count);
			      
			      let d = ''
			      let dem = 1;
			      $('.detail-box').remove()
			      $.each(data.data.List , function (index,value){
			        d = d +`
			        <tr class="detail-box">
			        <td>`+dem+`</td>
			        <td>`+value.Box_ID+`</td>
			        <td>`+value.Pallet_ID+`</td>
			        <td>`+value.materials.Symbols+`</td>
			        <td>`+value.materials.Spec+`</td>
			        <td>`+parseFloat(value.Quantity)+`</td>
			        <td>`+value.location.Symbols+`</td>

			        </tr>
			        `
			        dem++
			      })
			      $('.table-detail-box').append(
			        d   
			        )
			    },
			    error: function() {

			    }
			  });
		})

 
	})

	$(".modal-update").on("hidden.bs.modal", function () {
		$('.pallet-To').val('')
		$('.box-To').val('')
		$('.location-To').val('')
		$('input').val('')
	});

	$('.loca').on('input',function(){
		let a = $(this).val()
		console.log(a.length)
		if(a.length == 0)
		{
			$('.btn-save-update').hide()
		}
		else
		{
			$('.btn-save-update').show()
		}
	})

	$('.btn-save-update').on('click', function(){
		let a = $('.pallet-To').val()
		let c = $('.box-To').val()
		let b
		let z = $('.select-type').val();
		if (z == 2)
		{
			b = $('.location-To').val()
		}
		else
		{
			b = $('.location-To2').val()
		}
	})
</script>
@endpush