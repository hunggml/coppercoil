@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif

	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		 {{ __('Inventory') }} {{ __('Detail') }}
	                	</span>
	                	<div class="card-tools">
							@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                            @if($command->Status != 1)
                   			<button class="btn btn-success" style="width: 180px" data-toggle="modal" data-target=".modal-inventory">
                               {{__('Inventory')}}
                        	</button>
                        	@endif
                            @endif
                        	@if(session()->has('table'))
			          			@if(session()->get('table') && count(session()->get('danger')) >0 )
				                	<button type="button" class="btn btn-danger btn-detail-error">
					                	{{__('Detail')}} {{__('Error')}}
					                </button>
			                	@endif
			                @endif
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('warehousesystem.inventory') }}" method="get">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{__('Name')}} {{ __('Command') }}</label>
		                        	<select class="custom-select select2" name="name">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Name')}}
		                          		</option>
										
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
		                @if(session()->has('success'))
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								<strong>{{session()->get('success')}}</strong>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif
						@if(session()->has('danger'))
							@foreach(session('danger') as $value)
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								<strong>{{$value}}</strong>
								<br>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endforeach
						@endif
		            	</br>
		                <table class="table table-striped table-hover"  width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('Location')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Pallet')}} ( {{__('Box')}}) {{__('System')}} </th>
                                <th>{{__('Roll Number')}} {{__('System')}}</th>
                                <th>{{__('Quantity')}} {{__('System')}}</th>
								<th>{{__('Pallet')}} ( {{__('Box')}}) {{__('Reality')}}</th>
                                <th>{{__('Roll Number')}} {{__('Reality')}}</th>
                                <th>{{__('Quantity')}} {{__('Reality')}}</th>
                                <th>{{__('Action')}}</th>
		                	</thead>
		                	<tbody>
								<?php $dem = 1 ?>
		                		@foreach($data->GroupBy('Pallet_System_ID') as $key => $value)
                                @if($key == '')
                                @foreach($value as $value1)
                                <tr>
                                    <td>{{$dem}}</td>
                                    <td>{{$value1->location ? $value1->location->Symbols: ''}}</td>
                                    <td>{{__('Not In Pallet')}}</td>
                                    <td>{{$value1->Box_System_ID}}</td>
                                    <td>1</td>
                                    <td>{{floatval($value1->Quantity_System)}}</td>
                                    <td>{{$value1->Box_ID}}</td>
                                    <td>1</td>
                                    <td>{{floatval($value1->Quantity)}}</td>
                                    <td>
                                        <button class="btn btn-info btn-detail" id="btn-end-{{$value1->ID}}" data-toggle="modal" data-target=".bd-example-modal-lg">
                                            {{__('Detail')}}
                                        </button>
                                    </td>
                                </tr>
                                <?php $dem++ ?>

                                @endforeach
                                @else
                                <?php $value1 = $value[0]; ?>
                                <tr>
                                    <td>{{$dem}}</td>
                                    <td>{{$value1->location ? $value1->location->Symbols : ''}}</td>
                                    <td>{{__('In Pallet')}}</td>
                                    <td>{{$value1->Pallet_System_ID}}</td>
                                    <td>{{count($value)}}</td>
                                    <td>{{floatval(collect($value)->sum('Quantity_System'))}}</td>
                                    <td></td>
                                    <td>{{count($value)}}</td>
                                    <td>{{floatval(collect($value)->sum('Quantity'))}}</td>
                                    <td> 
                                        <button class="btn btn-info btn-detail" id="btn-{{$value1->Pallet_System_ID}}-end" data-toggle="modal" data-target=".bd-example-modal-lg">
                                            {{__('Detail')}}
                                        </button>
                                    </td>
                                </tr>
                                <?php $dem++ ?>
                                @endif
                                @endforeach
		                	</tbody>
		                </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('Detail')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-hover"   id ="tableUnit1">
		        <thead>
                    <th>{{__('Box')}} {{__('System')}} </th>
                    <th>{{__('Quantity')}} {{__('System')}}</th>
					<th>{{__('Box')}} {{__('Reality')}}</th>
                    <th>{{__('Quantity')}} {{__('Reality')}}</th>
		        </thead>
		        <tbody>
		        </tbody>
		    </table>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- modal inventory -->


<div class="modal fade modal-inventory" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('Inventory')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="hide span-suc">
            <div class="alert alert-success alert-dismissible fade show text-center " role="alert">
                <strong>{{__('Success')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                    </button>
            </div>
        </div>
        <div class="modal-body">
            <label> {{__('Location')}} {{__('Reality')}}</label>
            <select class="form-control select2 loca" Name="Location" id="Location">
                        <option value="">
                        {{__('Choose')}} {{__('Location')}}
                        </option>
                    @foreach($list_location as $value)
						<option value="{{$value->ID}}" >
                        {{$value->Symbols}}
                        </option>
                    @endforeach
                </select>
            <label> {{__('Pallet')}} {{__('Reality')}}</label>
            <input type="text" name="Pallet" id="Pallet" class="form-control">
            <label> {{__('Box')}} {{__('Reality')}}</label>
            <input type="text" name="Box" id="Box" class="form-control">
            <p class="box hide" style="color:red">{{__('Enter')}} {{__('Box')}}</p>
            <label> {{__('Quantity')}} {{__('Reality')}}</label>
            <input type="number" step="0.01" name="Location" id="Quantity" class="form-control">
            <p class="quan hide" style="color:red">{{__('Enter')}} {{__('Quantity')}}</p>
            <input type="text" name="Command_ID" id="Command_ID" value="{{$request->ID}}" class="form-control hide ">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-save hide">{{__('Save')}}</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
	<script>
		$('.select2').select2()
        $('.duallistbox').bootstrapDualListbox({
			nonSelectedListLabel: __warehouses.no_select_com,
			selectedListLabel   : __warehouses.select_com,
			infoText            : __group.show+' {0}',
			infoTextEmpty       : __group.empty,
			showFilterInputs    : true,
			filterPlaceHolder   : __filter.symbols
		});
		$('#tableUnit').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});
        $('.btn-detail').on('click',function(){
            let a = $(this).attr('id')
            let b 
            let c 
            if(a.split('-')[1] != 'end' )
            {
                b = a.split('-')[1];
                c = '';
            }
            else
            {
                 b = '';
                 c = a.split('-')[2];
            }
            $.ajax({
                type: "GET",
                url: "{{route('warehousesystem.inventory.detail_inven')}}",
                data: { 
                    Pallet_ID:b,
                    ID:c,
                    Command_ID: {{$request->ID}}
                },
                success: function(data) 
                {
				    let d = ''
                    let dem = 1;
                    $('.add').remove()
                    $.each(data.data , function (index,value){
                    let e = ''
                    let f = ''
                    if(value.Box_ID !== null)
                    {
                        e = value.Box_ID
                        f = parseFloat(value.Quantity)
                    }
                    console.log(e,f)
                    d = d +`
                        <tr class="add">
                            <td>`+value.Box_System_ID+`</td>
                            <td>`+parseFloat(value.Quantity_System)+`</td>
                            <td>`+e+`</td>
                            <td>`+f+`</td>
                        </tr>
                    `
                    dem++
                 })
                 $('#tableUnit1 tbody').append(
                  d   
                 )
                },
                error: function() {
                  
                }
			});
        })

        $('.loca').on('input',function(){
            let a = $(this).val()
            console.log(a.length)
            if(a.length == 0)
            {
                $('.btn-save').hide()
            }
            else
            {
                $('.btn-save').show()
            }
        })
        $('#Box').on('keyup change',function(){
            console.log($('#Box').val())
           if ($('#Box').val() != '') 
           {
                $('.box').hide()
           }
           else
           {
                $('.box').show()

           }
        })

        $('#Quantity').on('keyup change',function(){
            console.log($('#Quantity').val())
           if ($('#Quantity').val() != '') 
           {
                $('.quan').hide()
           }
           else
           {
                $('.quan').show()

           }
        })
        
        $('.btn-save').on('click',function(){
                let a = $('#Location').val()
                let b = $('#Pallet').val()
                let c = $('#Box').val()
                let d = $('#Quantity').val()
                let e = $('#Command_ID').val()
                if (c == 0) 
                {
                    $('.box').show()
                }
                if (d == 0) 
                {
                    $('.quan').show()
                }
                
                $('.span-suc').hide()
                $.ajax({
                type: "get",
                url: "{{route('warehousesystem.inventory.update')}}",
                data: { 
                    '_token' : $('meta[name="csrf-token"]').attr('content'),
                    Location:a,
                    Pallet_ID:b,
                    Box:c,
                    Quantity:d,
                    Command_ID:e
                },
                success: function(data) 
                {
				    if(data.data)
                    {
                        $('#Box').val('')
                        $('#Quantity').val('')
                        $('.span-suc').show()
                    }
                },
                error: function() {
                  
                }
			});    
        })

	</script>
@endpush