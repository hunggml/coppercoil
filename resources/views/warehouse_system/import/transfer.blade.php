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
	                		{{ __('Transfer') }} {{ __('Warehouse') }} 
	                	</span>
	                	<div class="card-tools">
	                	</div>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('warehousesystem.transfer') }}" method="get">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
                                    <input type="text" name="ID" value="{{$request->ID}}" class="form-control hide">
		                        	<label>{{__('Choose')}} {{__('Symbols')}} {{ __('Command') }}</label>
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
                                    <label>{{ __('Box ID') }}</label>
                                    <input type="number" name="Box_ID" value="{{$request->Box_ID}}" class="form-control" placeholder="{{__('Import')}} {{__('Box ID')}}">
                                </div>
	                      		<div class="form-group col-md-1">
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
                                  <div class="form-group col-md-1">
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
                                <th>{{__('Wire Type')}}</th>
                                <th>{{__('Box')}}</th>
                                <th>{{__('Location')}} {{__('Go')}}</th>
                                <th>{{__('Location')}} {{__('To')}}</th>
                                <th>{{__('Quantity')}}(kg)</th>
                                <th>{{__('User')}} {{__('Transfer')}}</th>
                                <th>{{__('Time')}} {{__('Transfer')}}</th>
		                	</thead>
		                	<tbody>
						    <?php $dem = 0 ?>
		                	@foreach($data as $key=> $value)
                                <?php $dem++;  ?>
                              <tr>
                                <td>{{$dem}}</td>
                                <td>{{$value->materials ? $value->materials->Symbols : ''}}</td>
                                <td>{{$value->materials ? $value->materials->Wire_Type : ''}}</td>
                                <td>{{$value->Box_ID}}</td>
                                <td>{{$value->location_go ? $value->location_go->Symbols : ''}}</td>
                                <td>{{$value->location_to ? $value->location_to->Symbols : ''}}</td>
                                <td>{{floatval($value->Quantity)}}</td>
                                <td>
                                  {{$value->user_created ? $value->user_created->name : ''}}
                                </td>
                                <td>{{$value->Time_Created}}</td>
                              </tr>
							@endforeach
		                	</tbody>
		                </table>
                        {{ $data->links() }}
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<!-- modal retype -->

@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('warehousesystem.import.destroy')])
	@endif
@endsection
@push('scripts')
	<script>
	$('.select2').select2()
    let check;
    $('.Box').on('input',function(){

      clearTimeout(check);
      check = setTimeout(function()
      {
          filter();
      }, 2000);
    })

    function filter()
    {
      let a = $('.Box').val()
      console.log(a);
      $.ajax({
                type: "GET",
                url: "{{route('warehousesystem.check_infor')}}",
                data: { 
                    Box_ID:a,
                },
                success: function(data) 
                {
                    console.log(data.data)
                    if(data.success)
                    {   $('#Materials').val(data.data.materials.Symbols)
                        $('#Spec').val(data.data.materials.Spec)    
                        if(data.data.Status == 1)
                        {
                            if(data.data.Inventory > 0)
                            {
                                $('.err1').show()
                                $('.warehouse').hide()
                                $('#Type').val() 
                                $('#Type1').val() 
                                $('.btn-add-stock1').hide()
                            }
                            else
                            {
                                $('.warehouse').show()
                                $('#Type').val(1) 
                                $('#Type1').val('Nhập Lại') 
                                $('.btn-add-stock1').show()
                            }
                        }
                        else
                        {
                            $('.warehouse').show()
                            $('#Type').val(0) 
                            $('#Type1').val('Nhập Mới') 
                            $('.btn-add-stock2').show()
                        }
                    }
                    else
                    {
                        $('.err2').show()
                    }
                },
                error: function() {
                  
                }
			});
    }
    $(document).on('click','.btn-add-stock',function()
    {
        let a =  $('.ware').val()
        if(a == '')
        {
          $('.err').show()
        }
        else
        {
            let b = $('#idCan5').val()
            let c = $('#Quantity').val()
            let d = $('#Type').val()
            $('.err').hide()
            $.ajax({
                type: "get",
                url: "{{route('warehousesystem.retype.add')}}",
                data: { 
                    '_token' : $('meta[name="csrf-token"]').attr('content'),
                    Box:b,
                    Warehouse_Detail_ID:a,
                    Quantity:c,
                    Type:d
                },
                success: function(data) 
                {
                      if(data.success)
                      {
                          location.reload();
                      }        
                },
                error: function() {
                  
                }
		      });
        }

    })
	</script>
@endpush