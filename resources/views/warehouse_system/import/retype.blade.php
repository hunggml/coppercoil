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
                        {{ __('Retype') }}
                    </span>
                    <div class="card-tools">
                        <button class="btn btn-success btn-pallet" data-toggle="modal" data-target="#add-pallet" style="width: 180px">
                            {{__('Retype')}}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('warehousesystem.retype') }}" method="get">
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
                                    <option value="{{$value->ID}}">
                                        {{$value->Symbols}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Box ID') }}</label>
                                <input type="number" name="Box_ID" value="{{$request->Box_ID}}" class="form-control" placeholder="{{__('Import')}} {{__('Box ID')}}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('From') }}</label>
                                <input type="date" value="{{$request->from}}" class="form-control datetime" name="from">
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('To') }}</label>
                                <input type="date" value="{{$request->to}}" class="form-control datetime" name="to">
                            </div>

                            <div class="col-md-2" style="margin-top: 33px">
                                <button type="submit" class="btn btn-info">{{__('Filter')}}</button>
                            </div>
                        </div>

                    </form>
                    @include('basic.alert')
                    </br>
                    <table class="table table-striped table-hover" width="100%" id="tableUnit">
                        <thead>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Symbols')}} {{ __('Materials') }}</th>
                            <th>{{__('Wire Type')}}</th>
                            <th>{{__('Box')}}</th>
                            <th>{{__('Quantity')}}(kg)</th>
                            <th>{{__('Location')}} {{__('Retype')}}</th>
                            <th>{{__('User')}} {{__('Retype')}}</th>
                            <th>{{__('Time')}} {{__('Retype')}}</th>
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
                                <td>{{floatval($value->Quantity)}}</td>
                                <td>{{$value->location ? $value->location->Symbols : ''}}</td>
                                <td>
                                    {{$value->user_created ? $value->user_created->name : ''}}
                                </td>
                                <td>{{$value->Time_Created}}</td>
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
<!-- modal retype -->

<div class="modal fade retype" id="add-pallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">{{__('Import')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('warehousesystem.retype.add') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-12">
                            <label> {{__('Box_ID')}} </label>
                            <input type="text" name="Box" id="idCan5" class="form-control Box ">
                        </div>
                        <div class="col-6">
                            <label> {{__('Materials')}} </label>
                            <input type="text" name="Materials" id="Materials" class="form-control  mat" readonly>
                        </div>
                        <div class="col-6">
                            <label> {{__('Spec')}} </label>
                            <input type="text" name="Spec" id="Spec" class="form-control  spec" readonly>
                        </div>
                        <div class="col-12">
                            <label> {{__('Quantity')}}(kg) </label>
                            <input type="text" name="Quantity" id="Quantity" class="form-control quan" min="0" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label> {{__('Type')}} </label>
                            <input type="text" name="Type1" id="Type1" class="form-control  type1" readonly>
                        </div>
                        <div class="col-12 hide">
                            <label> {{__('Type')}} </label>
                            <input type="text" name="Type" id="Type" class="form-control  type2" readonly>
                        </div>
                        <span style="color :red; font-size:15px" class="err1 hide stock">{{__('Box Still Exists In Stock')}}</span>
                        <span style="color :red; font-size:15px" class="err2 hide exist">{{__('Box Doesnt Exist')}}</span>
                        <div class="warehouse hide col-12">
                            <label>{{__('Choose')}} {{__('Location')}} </label>
                            <select class="custom-select ware select2" name="Warehouse_Detail_ID">
                                <option value="">
                                    {{__('Choose')}} {{__('Location')}}
                                </option>
                                @foreach($list_location as $value)
                                <option value="{{$value->ID}}">
                                    {{$value->Symbols}}
                                </option>
                                @endforeach
                            </select>
                            <span style="color :red; font-size:10px" class=" err hide">{{__('Not')}} {{__('Choose')}} {{__('Location')}} </span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-add-stock1 btn-add-stock hide float-right btn-warning ">{{__('Retype')}}</button>
                    <button type="button" class="btn btn-add-stock2 btn-add-stock hide float-right btn-warning ">{{__('Import')}}</button>
                </div>
            </form>
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
    $(".retype").on("hidden.bs.modal", function() {
        $('.stock').hide()
        $('.exist').hide()
        $('.type1').val('')
        $('.type2').val('')
        $('.Box').val('')
        $('.mat').val('')
        $('.spec').val('')
        $('.quan').val('')
    });



    let check;
    $('.Box').on('input', function() {

        clearTimeout(check);
        check = setTimeout(function() {
            filter();
        }, 500);
    })

    function filter() {
        let a = $('.Box').val()
        console.log(a);
        $.ajax({
            type: "GET",
            url: "{{route('warehousesystem.check_infor')}}",
            data: {
                Box_ID: a,
            },
            success: function(data) {
                console.log(data.data)
                if (data.success) {
                    $('#Materials').val(data.data.materials.Symbols)
                    $('#Spec').val(data.data.materials.Spec)
                    $('#Quantity').val(parseFloat(data.data.Quantity))
                    if (data.data.Status == 1) {
                        if (data.data.Inventory > 0) {
                            $('.err1').show()
                            $('.warehouse').hide()
                            $('#Type').val()
                            $('#Type1').val()
                            $('.btn-add-stock1').hide()
                        } else {
                            $('.warehouse').show()
                            $('#Type').val(1)
                            $('#Type1').val('Nhập Lại')
                            $('.btn-add-stock1').show()
                        }
                    } else {
                        if (data.data.Inventory > 0) {
                            $('.err1').show()
                            $('.warehouse').hide()
                            $('#Type').val()
                            $('#Type1').val()
                            $('.btn-add-stock1').hide()
                        } else {
                            $('.warehouse').show()
                            $('#Type').val(0)
                            $('#Type1').val('Nhập Mới')
                            $('.btn-add-stock2').show()
                        }

                    }
                } else {
                    $('.err2').show()
                }
            },
            error: function() {

            }
        });
    }
    $(document).on('click', '.btn-add-stock', function() {
        let a = $('.ware').val()
        if (a == '') {
            $('.err').show()
        } else {
            let b = $('#idCan5').val()
            let c = $('#Quantity').val()
            let d = $('#Type').val()
            $('.err').hide()
            $.ajax({
                type: "post",
                url: "{{route('warehousesystem.retype.add')}}",
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    Box: b,
                    Warehouse_Detail_ID: a,
                    Quantity: c,
                    Type: d
                },
                success: function(data) {
                    if (data.success) {
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