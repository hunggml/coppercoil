@extends('layouts.main')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<span>
						<a href="{{route('warehousesystem.export')}}" class="btn btn-warning">
							{{__('Back')}}
						</a>
					</span>
					<br>
					<span class="text-bold" style="font-size: 23px">
						{{ __('Command') }} {{ __('Export') }} {{ __('Detail') }} : {{$command->materials ? $command->materials->Symbols : '' }}
					</span>
					<br>
					<span  class="text-bold" style="font-size: 18px">
						{{__('Wire Type')}} : {{$command->materials ? $command->materials->Wire_Type : '' }} ,  {{__('Spec')}} : {{$command->materials ? $command->materials->Spec : '' }}  
					</span>
					<br>
					<span style="font-size: 15px">
						{{__('Quantity')}} {{__('To Be Exported')}} :  {{$command->Count ? $command->Count : $command->Quantity }}
					</span>
					<br>
					<span style="font-size: 15px">
						{{__('Quantity')}} {{__('Exported')}} : {{$command->Count ? count($data->where('Status',1)) : collect($data->where('Status',1))->Sum('Quantity') }}
					</span>
					<br>
					<span style="font-size: 15px">
						{{__('Unit')}}: {{$command->Count ? 'Box' :'Kg' }}
					</span>
					<br>
					<span style="font-size: 15px">
						{{__('Type')}} {{__('Export')}} : {{$command->materials ? __('Export')  : '' }} {{$command->materials ? ($command->materials->Export_Type == 0 ? __('Even') :  __('Odd') ) : '' }}
					</span>
					<div class="card-tools">
						@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)

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
					<form action="{{ route('warehousesystem.export.detail') }}" method="get">
						@csrf
						<input type="text" name="ID" value="{{$command->ID}}" class="form-control hide">
						<div class="row">
							<div class="form-group col-md-2">
								<label>{{__('Choose')}} {{__('Pallet')}} </label>
								<select class="custom-select select2" name="Pallet_ID">
									<option value="">
										{{__('Choose')}} {{__('Pallet')}}
									</option>
									@foreach($data_all->GroupBy('Pallet_ID') as $key => $value)
									<option value="{{$key}}" {{$request ? ($request->Pallet_ID == $key ? 'selected' : ''): ''}}> 
										{{$key}}
									</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-2">
								<label>{{__('Choose')}} {{__('Box')}} </label>
								<select class="custom-select select2" name="Box_ID">
									<option value="">
										{{__('Choose')}} {{__('Box')}}
									</option>
									@foreach($data_all->GroupBy('Box_ID') as $key => $value)
									<option value="{{$key}}" {{$request ? ($request->Box_ID == $key ? 'selected' : ''): ''}}>
										{{$key}}
									</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-2">
								<label>{{__('Choose')}} {{__('Status')}}</label>
								<select class="custom-select select2" name="status">
									<option value="">
										{{__('Choose')}} {{__('Status')}}
									</option>
									<option value="1" {{$request ? ($request->status === 0 ? 'selected' : ''): ''}}>
										{{__('Waiting')}} {{__('Export')}} 
									</option>
									<option value="2" {{$request ? ($request->status === 1 ? 'selected' : ''): ''}}>
										{{__('Success')}}
									</option>
									<option value="3" {{$request ? ($request->status === 2 ? 'selected' : ''): ''}}>
										{{__('Cancel')}}
									</option>
								</select>
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
				<table class="table table-bordered table-striped "  width="100%" id="table">
					<thead>
						<th>{{__('ID')}}</th>
						<th>{{__('Location')}}</th>
						<th>{{__('Pallet_ID')}}</th>
						<th>{{__('Box_ID')}}</th>
						<th>{{__('Quantity')}}</th>
						<th>{{__('Unit')}}</th>
						<th>{{__('Status')}}</th>
						<th>{{__('User Created')}}</th>
						<th>{{__('Time Created')}}</th>
						<th>{{__('User Updated')}}</th>
						<th>{{__('Time Updated')}}</th>
						<th>{{__('Action')}}</th>
					</thead>
					<tbody>
						<?php $dem = 0 ?>
						<?php $stt = 0 ?>
						<?php $a  = $command->Count ? $command->Count : $command->Quantity ?>
						<?php $b  = $command->Count ? count($data->where('Status',1)) : collect($data->where('Status',1))->Sum('Quantity')  ?>
						<?php if($a-$b <= 0 || $command->Status == 3){
							$data1 = $data->where('Status',1);
						}
						else
						{
							$data1 = $data;
						}
						?>
						@foreach($data1 as $value)
						<?php $dem ++ ?>
						<?php $count = count($data->where('STT',$stt)) ?>
						<?php $count1 = count($data->where('STT',$stt)->where('Status',1));
						if($count1 == $count)
						{
							$stt = $value->Status +1;
						}
						?>
						<tr>
							<td>{{$dem}}</td>
							<td>{{$value->location ? $value->location->Symbols : '' }}</td>
							<td>{{$value->Pallet_ID}}</td>
							<td class ="box_id-{{$value->ID}}">{{$value->Box_ID}}</td>
							<td>{{floatval($value->Quantity)}}</td>
							<td>Kg</td>
							<td>{{$value->Status == 0 ? __('Waiting') : ($value->Status == 1 ? __('Success') : __('Cancel') ) }}  {{$value->Status == 0 ? __('Export') : '' }}</td>
							<td>
								{{$value->user_created ? $value->user_created->name : ''}}
							</td>
							<td>{{$value->Time_Created}}</td>
							<td>
								{{$value->user_updated ? $value->user_updated->name : ''}}
							</td>
							<td>{{$value->Time_Updated}}</td>
							@if($value->Status == 0 && $value->STT <= $stt)
							<td>
								<button class="btn btn-warning btn-export" id="btn/{{$value->ID}}/{{$value->Box_ID}}/{{$value->location ? $value->location->Symbols : '' }}/{{$value->Pallet_ID}}  " >
									{{ __('Export') }}
								</button>
								<button class="btn btn-danger btn-cancel" id="btn-{{$value->ID}}" >
									{{ __('Cancel') }}
								</button>            
							</td>
							@elseif($value->Status == 1)
							<td>
								<button class="btn btn-success btn-accept">
									{{ __('Success') }}
								</button>       
							</td>
							@else
							<td>
								<button class="btn btn-danger btn-cancel" >
									{{ __('Cancel') }}
								</button>            
							</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
 
<!-- modal export -->
 
@endsection
@push('scripts')
<script>
	$('.select2').select2()
	$('#table').DataTable({
		language: __languages.table,
		scrollX : '100%',
		scrollY : '100%'
	});
	$(document).on('click', '.btn-export', function()
	{
		let id = $(this).attr('id');
		let name = $('.box_id-'+id.split('/')[1]).html();
		var currentRow = $(this).closest("tr");
    	var col4 = currentRow.find("td:eq(4)").text();
		$('#modalRequestEx').modal();
		$('#nameDel').text(name);
		$('#Box_ID').val(id.split('/')[2]);
		$('#Location').val(id.split('/')[3]);
		$('#Pallet_ID').val(id.split('/')[4]);
		$('.quan').val(col4);
	});
</script>
@endpush