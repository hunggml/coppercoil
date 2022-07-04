@extends('layouts.main')

@section('content')
    @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
        @include('basic.modal_import', ['route' => route('warehousesystem.import.import_file')])
        @include('basic.modal_table_error')
    @endif
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="text-bold" style="font-size: 23px">
                            {{ __('Command') }} {{ __('Import') }} {{ __('Detail') }}
                        </span>
                        <div class="card-tools">
                            <button class="btn btn-success btn-pallet" data-toggle="modal" data-target="#add-pallet"
                                style="width: 180px">
                                {{ __('Create') }} {{ __('Pallet') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('warehousesystem.import.detail') }}" method="get">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <input type="text" name="ID" value="{{ $request->ID }}"
                                        class="form-control hide">
                                    <label>{{ __('Choose') }} {{ __('Symbols') }} {{ __('Materials') }}</label>
                                    <select class="custom-select select2" name="Materials_ID">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Symbols') }}
                                        </option>
                                        @foreach ($list_materials as $value)
                                            <option value="{{ $value->ID }}"
                                                {{ $request->Materials_ID == $value->ID ? 'selected' : '' }}>
                                                {{ $value->Symbols }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>{{ __('Pallet') }}</label>
                                    <select class="custom-select select2" name="Pallet_ID">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Pallet') }}
                                        </option>
                                        @foreach ($data_all->GroupBy('Pallet_ID') as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $request->Pallet_ID == $key ? 'selected' : '' }}>
                                                {{ $key }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>{{ __('Wire Type') }}</label>
                                    <select class="custom-select select2" name="Wire_Type">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Wire Type') }}
                                        </option>
                                        @foreach ($list_materials->GroupBy('Wire_Type') as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $request->Wire_Type == $key ? 'selected' : '' }}>
                                                {{ $key }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>{{ __('Status') }}</label>
                                    <select class="custom-select select2" name="Status">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Status') }}
                                        </option>
                                        <option value="0" {{ $request->Status === '0' ? 'selected' : '' }}>
                                            {{ __('Waiting') }} {{ __('Import') }}
                                        </option>
                                        <option value="1" {{ $request->Status === '1' ? 'selected' : '' }}>
                                            {{ __('Success') }}
                                        </option>
                                        <option value="2" {{ $request->Status === '2' ? 'selected' : '' }}>
                                            {{ __('Cancel') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2" style="margin-top: 33px">
                                    <button type="submit" class="btn btn-info">{{ __('Filter') }}</button>
                                </div>
                            </div>

                        </form>
                        @include('basic.alert')
                        </br>
                        <table class="table table-striped table-hover" id="tableUnit" width="100%">
                            <thead>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Symbols') }} {{ __('Materials') }}</th>
                                <th>{{ __('Pallet') }}</th>
                                <th>{{ __('Wire Type') }}</th>
                                <th>{{ __('Roll Number') }}</th>
                                <th>{{ __('Quantity') }}(kg)</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('User Created') }}</th>
                                <th>{{ __('Time Created') }}</th>
                                <th>{{ __('User Updated') }}</th>
                                <th>{{ __('Time Updated') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                <?php $dem = 0; ?>
                                @foreach ($data as $key => $value1)
                                    <?php $value2 = $value1->GroupBy('Pallet_ID'); ?>
                                    @foreach ($value2 as $key1 => $value3)
                                        <?php $dem++; ?>
                                        <?php $value = $value3[0]; ?>
                                        <?php $count = count($value3); ?>
                                        <?php $quan = $value3->sum('Quantity'); ?>
                                        <tr>
                                            <td>{{ $dem }}</td>
                                            <td>{{ $value->materials ? $value->materials->Symbols : '' }}</td>
                                            <td>{{ $key1 }}</td>
                                            <td>{{ $value->materials ? $value->materials->Wire_Type : '' }}</td>
                                            <td>{{ $count }}</td>
                                            <td>{{ $quan }}</td>
                                            <td>{{ $value->location ? $value->location->Symbols : '' }}</td>
                                            <td>
                                                {{ $value->user_created ? $value->user_created->name : '' }}
                                            </td>
                                            <td>{{ $value->Time_Created }}</td>
                                            <td>
                                                {{ $value->user_updated ? $value->user_updated->name : '' }}
                                            </td>
                                            <td>{{ $value->Time_Updated }}</td>

                                            <td>{{ $value->Status == 0 ? __('Waiting') : ($value->Status == 1 ? __('Success') : __('Cancel')) }}
                                            </td>
                                            <td>

                                                <button class="btn btn-detail1 btn-info"
                                                    id="btn-{{ $value->Command_ID }}-{{ $value->Materials_ID }}-{{ $value->Pallet_ID }}"
                                                    data-toggle="modal" data-target=".bd-example-modal-lg">
                                                    {{ __('Detail') }}
                                                </button>
                                                @if ($value->Status == 0)
                                                    @if (Auth::user()->level == 9999 || Auth::user()->checkRole('create_import'))
                                                        <button class="btn btn-warning btn-add"
                                                            id="btn-{{ $value->Command_ID }}-{{ $value->Materials_ID }}-{{ $value->Pallet_ID }}"
                                                            data-toggle="modal" data-target="#add-stock">
                                                            {{ __('Import') }}
                                                        </button>
                                                        <button class="btn btn-danger btn-cancel"
                                                            id="btn-{{ $value->Command_ID }}-{{ $value->Materials_ID }}-{{ $value->Pallet_ID }}">
                                                            {{ __('Cancel') }}
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        {{-- {{$data->links()}} --}}

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Detail') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover" id="tableUnit1">
                        <thead>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('BOXID') }} </th>
                            <th>{{ __('Pallet') }}</th>
                            <th>{{ __('Lot') }}</th>
                            <th>{{ __('Wire Type') }}</th>
                            <th>{{ __('Quantity') }}(kg)</th>
                            <th>{{ __('Location') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
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
    <!--
      modal_cancel -->

    <div class="modal fade" id="modalRequestCan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">{{ __('Cancel') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('warehousesystem.import.cancel') }}" method="get">
                    @csrf
                    <div class="modal-body">
                        <strong style="font-size: 23px">{{ __('Do You Want To Cancel') }} <span id="nameDel"
                                style="color: blue"></span> ?</strong>
                        <input type="text" name="Command_ID" id="idCan" class="form-control hide">
                        <input type="text" name="Materials_ID" id="idCan1" class="form-control hide">
                        <input type="text" name="Pallet_ID" id="idCan2" class="form-control hide">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- modal add stock -->

    <div class="modal fade" id="add-stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">{{ __('Import') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('warehousesystem.import.detail.add_stock') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group ">
                            <label> {{ __('Pallet') }} </label>
                            <input type="text" name="Pallet_ID" id="idCan5" class="form-control " readonly>
                            <label> {{ __('Materials') }} </label>
                            <input type="text" name="Materials" id="idCan6" class="form-control " readonly>
                            <label> {{ __('Spec') }} </label>
                            <input type="text" name="Spec" id="idCan7" class="form-control " readonly>
                            <label> {{ __('Quantity') }}(kg) </label>
                            <input type="text" name="Quantity" id="idCan8" class="form-control " readonly>
                            <label> {{ __('Roll Number') }} </label>
                            <input type="text" name="Count" id="idCan9" class="form-control " readonly>
                            <div class="warehouse hide">
                                <label>{{ __('Choose') }} {{ __('Location') }} </label>
                                <select class="custom-select ware select2" name="Warehouse_Detail_ID">
                                    <option value="">
                                        {{ __('Choose') }} {{ __('Location') }}
                                    </option>

                                </select>
                                <span style="color :red; font-size:10px" class=" err hide">{{ __('Not') }}
                                    {{ __('Choose') }} {{ __('Location') }} </span>
                            </div>
                            <br>
                            <button type="button"
                                class="btn btn-add-stock float-right btn-warning hide">{{ __('Import') }}</button>
                            <button type="button" class="btn btn-secondary float-right"
                                data-dismiss="modal">{{ __('Close') }}</button>
                            <br>
                            <br>
                            <label> {{ __('Detail') }} </label>
                            <table class="table table-striped table-hover" id="tableUnit2">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('BOXID') }} </th>
                                    <th>{{ __('Pallet') }}</th>
                                    <th>{{ __('Lot') }}</th>
                                    <th>{{ __('Wire Type') }}</th>
                                    <th>{{ __('Quantity') }}(kg)</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal add pallet -->
    <div class="modal fade" id="add-pallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">{{ __('Import') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('warehousesystem.import.detail.add_pallet') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group body-add-pallet ">
                            <input type="text" name="Command_ID" value="{{ $request->ID }}" id="Command_ID"
                                class="form-control Command_ID hide">
                            <label> {{ __('Pallet') }} </label>
                            <input type="text" name="Pallet_ID" id="Pallet_ID" class="form-control Pallet_ID">
                            <label> {{ __('Materials') }} </label>
                            <select class="custom-select select2 Materials_ID" name="Materials_ID">
                                <option value="">
                                    {{ __('Choose') }} {{ __('Symbols') }}
                                </option>
                                @foreach ($list_materials as $value)
                                    <option value="{{ $value->ID }}" class="{{ $value->Spec }}">
                                        {{ $value->Symbols }}
                                    </option>
                                @endforeach
                            </select>
                            <label> {{ __('Spec') }} </label>
                            <input type="text" id="Spec" class="form-control Spec" readonly>
                            <label> {{ __('Box') }} </label>
                            <input type="text" id="Box" class="form-control Box">
                            <label> {{ __('Quantity') }}(kg) </label>
                            <input type="text" id="Quantity" class="form-control Quantity">
                            <br>
                            <button type="button"
                                class="btn btn-add-Box float-right btn-warning ">{{ __('Create') }}</button>
                            <br>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('warehousesystem.import.destroy')])
    @endif
@endsection
@push('scripts')
    <script>
        $('.select2').select2()
        $(document).on('click', '.btn-add', function() {
            let id = $(this).attr('id');
            let name = $(this).parent().parent().children('td').first().text();
            let b = id.split('-')
            $('#idCan3').val(id.split('-')[1]);
            $('#idCan4').val(id.split('-')[2]);

            $('#locati').show()
            $.ajax({
                type: "GET",
                url: "{{ route('warehousesystem.import.get_list') }}",
                data: {
                    Pallet_ID: b[3],
                    Command_ID: b[1],
                },
                success: function(data) {
                    console.log(data.data)
                    let a = parseFloat(data.data.Quantity)
                    $('#idCan5').val(data.data.Pallet_ID);
                    $('#idCan6').val(data.data.Materials);
                    $('#idCan7').val(data.data.Spec);
                    $('#idCan8').val(a);
                    $('#idCan9').val(data.data.Count);
                    if (data.data.Status == 0) {
                        $('.btn-add-stock').show()
                        $('.warehouse').show()
                    }
                    let d = ''
                    let dem = 1;
                    $('.add-detail').remove()
                    $.each(data.data.List, function(index, value) {
                        d = d + `
            <tr class="add-detail">
            <td>` + dem + `</td>
            <td>` + value.Box_ID + `</td>
            <td>` + value.Pallet_ID + `</td>
            <td>` + value.Lot_No + `</td>
            <td>` + value.materials.Wire_Type + `</td>
            <td>` + parseFloat(value.Quantity) + `</td>
            </tr>
            `
                        dem++
                    })
                    $('#tableUnit2 tbody').append(
                        d
                    )
                    console.log(data.location)
                    $('.op_new').remove()
                    $.each(data.location, function(index, value) {

                        var newOption = `<option value="` + value.ID + `" class="op_new">
                            ` + value.Symbols + `
                          </option>`
                        // Append it to the select
                        $('.ware').append(newOption);
                    })

                    let class_ware = $('.ware option')
                    $.each(class_ware, function(index, value) {

                        let cl1 = $(value).attr('class')

                        if (typeof cl1 !== "undefined") {
                            // console.log(cl1)
                            let cl = cl1.split(' ');

                            let arr_cl = cl[0].split('_');

                            let max = arr_cl[0].split('-')

                            if (max[1] != 0) {
                                let inven = arr_cl[1].split('-')

                                if ((inven[1] + data.data.List.length) > max[1]) {
                                    console.log('.max-' + max[1] + '_in-' + inven[1])
                                    $('.max-' + max[1] + '_in-' + inven[1]).hide();

                                    // $( ".ware" ).addClass("select2" );
                                    // $('.select2').select2()

                                }
                            }
                        }
                    })
                },
                error: function() {}
            });
        });
        $(document).on('click', '.btn-add-stock', function() {
            let a = $('.ware').val()
            if (a == '') {
                $('.err').show()
            } else {
                let b = $('#idCan5').val()
                $('.err').hide()
                $.ajax({
                    type: "Post",
                    url: "{{ route('warehousesystem.import.detail.add_stock') }}",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        Pallet_ID: b,
                        Warehouse_Detail_ID: a
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
            console.log(a);
        })
        $(document).on('click', '.btn-cancel', function() {
            let id = $(this).attr('id');
            let name = $(this).parent().parent().children('td').first().text();
            $('#modalRequestCan').modal();
            $('#nameDel').text(name);
            $('#idCan').val(id.split('-')[1]);
            $('#idCan1').val(id.split('-')[2]);
            $('#idCan2').val(id.split('-')[3]);
        });

        $('#tableUnit').DataTable({
            language: __languages.table,
            scrollX: '100%',
            scrollY: '100%'
        });

        $('.btn-import').on('click', function() {
            $('#modalImport').modal();
            $('#importFile').val('');
            $('.input-text').text(__input.file);
            $('.error-file').hide();
            $('.btn-save-file').prop('disabled', false);
            $('#product_id').val('');
        });
        $(document).on('click', '.btn-detail1', function() {
            console.log('run');
            let a = $(this).attr('id')
            let b = a.split('-')

            $.ajax({
                type: "GET",
                url: "{{ route('warehousesystem.import.get_list') }}",
                data: {
                    Command_ID: b[1],
                    Materials_ID: b[2],
                    Pallet_ID: b[3],
                },
                success: function(data) {
                    console.log(data.data)
                    let a = ''
                    let dem = 1;
                    let c = ''
                    $('.detail').remove()
                    $.each(data.data.List, function(index, value) {
                        if (value.Status == 0) {
                            c = __status.waiting
                        } else if (value.Status == 1) {
                            c = __status.success
                        } else {
                            c = __status.cancel
                        }
                        let d = '';
                        if (data.data.Status == 1) {
                            d = 'hide';
                        }
                        let e = '';
                        if (value.location) {
                            e = value.location.Symbols;
                        }

                        a = a + `
          <tr class="detail">
          <td>` + dem + `</td>
          <td>` + value.Box_ID + `</td>
          <td>` + value.Pallet_ID + `</td>
          <td>` + value.Lot_No + `</td>
          <td>` + value.materials.Wire_Type + `</td>
          <td>` + parseFloat(value.Quantity) + `</td>
          <td>` + e + `</td>
          <td>` + c + `</td>
          <td>
          <button href="" id="btn-` + value.ID + `" class="btn btn-danger btn-delete ` + d + `" >
          {{ __('Delete') }}
          </button>
          </td>
          </tr>
          `
                        dem++
                    })
                    $('#tableUnit1 tbody').append(
                        a
                    )
                    // var newOption = new Option(data.location.ID, data.location.Symbol, true, true);
                    // Append it to the select
                    // $('.ware').append(newOption).trigger('change');
                    $(document).on('click', '.btn-delete', function() {
                        let id = $(this).attr('id');
                        let name = $(this).parent().parent().children('td').first().text();

                        $('#modalRequestDel').modal();
                        $('#nameDel').text(name);
                        $('#idDel').val(id.split('-')[1]);
                    });

                },
                error: function() {

                }
            });
        })

        let check;
        $('.Pallet').on('input', function() {

            clearTimeout(check);
            check = setTimeout(function() {
                filter();
            }, 2000);


        })

        function filter() {
            let a = $('.Pallet').val()
            console.log(a);
        }
        $('.Materials_ID').on('input', function() {
            let a = $('.Materials_ID :selected').attr('class')
            console.log(a)
            $('.Spec').val(a)
        })
        var num_box = 0
        $('.btn-add-Box ').on('click', function() {
            let a = $('.Box ').val()
            let b = $('.Quantity ').val()
            console.log(a, b)
            let c = $('.box_num').length
            num_box++;
            $('.body-add-pallet').append(`
        <div class="row box_num box_num_` + num_box + `  ">
        <div class="col-5"> 
        <input type="text" value = "` + a + `" name="Box[]"  class="form-control" readonly>
        </div>
        <div class="col-5"> 
        <input type="text" value = "` + b + `" name="Quantity[]"  class="form-control" readonly>
        </div>
        <div class="col-2"> 
        <button type="button" id="` + num_box + `" class="btn btn-delete-Box  float-right btn-danger ">{{ __('Delete') }}</button>
        </div>
        </div>
        <br  class="row box_num_` + num_box + `  ">
        `)
            $('.btn-delete-Box').on('click', function() {
                let d = $(this).attr('id')
                $('.box_num_' + d).remove()
            })
        })
    </script>
@endpush
