@extends('layouts.main')

@section('content')

@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
@include('basic.modal_table_error')
@endif
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="text-bold" style="font-size: 23px">
                        {{ __('Stock') }} {{ __('Detail') }}
                    </span>
                    <div class="col-md-1" style="float:right;">
                        <a href="{{route('warehousesystem.import.location',['Format'=>$request->Format])}}" class="btn btn-warning">{{__('Diagram')}}</a>
                    </div>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('warehousesystem.import.detail.inventory') }}" method="get">
                        <input type="text" value="{{$request->Format}}" name="Format" class="hide">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>{{__('Choose')}} {{__('Symbols')}} {{ __('Materials') }}</label>
                                <select class="custom-select select2" name="Materials_ID">
                                    <option value="">
                                        {{__('Choose')}} {{__('Symbols')}}
                                    </option>
                                    @foreach($list_materials as $value)
                                    <option value="{{$value->ID}}" {{ ($request->Materials_ID == $value->ID ? 'selected' :'') }}>
                                        {{$value->Symbols}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Pallet') }}</label>
                                <select class="custom-select select2" name="Pallet_ID">
                                    <option value="">
                                        {{__('Choose')}} {{__('Pallet')}}
                                    </option>
                                    @foreach($list_pallet->where('Pallet_ID','!=','')->GroupBy('Pallet_ID') as $key=> $value)
                                    <option value="{{$key}}" {{($request->Pallet_ID == $key ? 'selected':'' )}}>
                                        {{$key}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Location') }}</label>
                                <select class="custom-select select2" name="location">
                                    <option value="">
                                        {{__('Choose')}} {{__('Location')}}
                                    </option>
                                    @foreach($list_location as $key => $value)
                                    <option value="{{$value->ID}}" {{($request->location == $value->ID ? 'selected':'' ) }}>
                                        {{$value->Symbols}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Warehouse') }}</label>
                                <select class="custom-select select2" name="warehouse">
                                    <option value="">
                                        {{__('Choose')}} {{__('Warehouse')}}
                                    </option>
                                    @foreach($list_ware as $key => $value)
                                    <option value="{{$value->ID}}" {{($request->warehouse == $value->ID ? 'selected' :'') }}>
                                        {{$value->Symbols}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" style="margin-top: 33px">
                                <button type="submit" class="btn btn-info">{{__('Filter')}}</button>
                            </div>
                        </div>
                    </form>
                    @include('basic.alert')
                    </br>
                    <table class="table table-bordered " width="100%">
                        <thead>
                            <!-- <th>{{__('ID')}}</th> -->
                            <!-- <th>{{__('Name')}} {{__('Warehouse')}}</th>
                            <th>{{__('Symbols')}} {{__('Warehouse')}}</th> -->
                            <th>{{__('Location')}}</th>
                            <th>{{__('Pallet')}}</th>
                            <th>{{__('Materials')}}</th>
                            <th>{{__('Supplier')}}</th>
                            <th>{{__('Time_Import')}}</th>
                            <th>{{__('Box')}}</th>
                            <th>{{__('Quantity')}}(kg)</th>
                        </thead>
                        <tbody>
                            @foreach($data as $value)
                            <?php $sum_t = 0;
                            $dem_t = 0 ?>
                              @foreach($value['Inven'] as $value1)
                                @foreach($value1['Inven'] as $value2)
                                <tr>
                                    <th rowspan="{{count($value2['Inven'])+2}}" style="vertical-align: middle;">{{$value1['Symbols']}}</th>
                                    <td rowspan="{{count($value2['Inven'])+2}}" style="vertical-align: middle;">{{$value2['Pallet_ID']}}</td>
                                    <td rowspan="{{count($value2['Inven'])+2}}" style="vertical-align: middle;">{{$value2['Materials']}}</td>
                                    <td rowspan="{{count($value2['Inven'])+2}}" style="vertical-align: middle;">{{$value2['Supplier']}}</td>
                                    <!-- <td rowspan="{{count($value2['Inven'])+2}}" style="vertical-align: middle;">{{date_format(date_create($value2['Time_Import']),"d/m/Y")}}
                                    </td> -->
                                </tr>
                                <?php $sum = 0;
                                $dem = 0 ?>
                                  @foreach($value2['Inven'] as $value2)
                                  <tr>
                                    <td>{{date_format(date_create($value2->Time_Import),"d/m/Y")}}</td>
                                    <td>{{$value2->Box_ID}}</td>
                                    <td>{{floatval($value2->Inventory)}}</td>
                                      <?php $sum += $value2->Inventory;
                                      $dem++ ?>
                                      <?php $sum_t += $value2->Inventory;
                                      $dem_t++ ?>
                                  </tr>
                                  @endforeach
                                  <tr>
                                      <td colspan="2" style="background-color: #ccffff;">{{__('Roll Number')}} : {{$dem}}</td>
                                      <td colspan="2" style="background-color: #ccffff;">{{__('Quantity')}}(kg) : {{floatval($sum)}}</td>
                                  </tr>
                                @endforeach
                              @endforeach
                            <tr>
                                <td colspan="4" style="background-color: #99ff99;">{{__('Roll Number')}} : {{$dem_t}}</td>
                                <td colspan="4" style="background-color: #99ff99;">{{__('Quantity')}}(kg) : {{floatval($sum_t)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
</script>
@endpush