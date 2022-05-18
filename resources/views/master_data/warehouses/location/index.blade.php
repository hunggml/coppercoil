@extends('layouts.main')

@section('content')

@push('myCss')
    <link rel="stylesheet" href="{{asset('my_setup/css/warehouse.css')}}">
@endpush

@include('basic.modal_detail_inventories')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="text-bold col-md-3" style="font-size: 23px">
                            {{__('Location')}} {{__('Materials')}}
                        </span>
                        <div class="col-md-2">
                            <button class="btn btn-info" style="width: 50px; height: 30px; background: #009787"></button>
                            {{__('Have')}} {{__('Materials')}}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-warning" style="width: 50px; height: 30px"></button>
                            {{__("Don't")}} {{__('Have')}} {{__('Materials')}}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-light" style="width: 50px; height: 30px"></button>
                            {{__('Position')}} {{__('Null')}}
                        </div>

                        <div class="col-md-3">
                            <a href="{{route('warehousesystem.import.detail.inventory')}}" class="btn btn-info" >{{__('List')}}</a>
                            
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @foreach($warehouses as $value)
                    <table class="table table-bordered table-warehouse table-responsive">
                        <thead>
                            <th>{{__('Warehouse')}}</th>
                            <th>{{__('Position')}}</th>
                        </thead>
                        <tbody>
                             <tr>
                                <td>
                                    <table class="table table-bordered col-12">
                                        <tbody>
                                            @for($i=1; $i <= $value->Quantity_Rows; $i++)
                                            @if($i == 1)
                                            <div class="text-left text-bold" style="font-size: 23px">
                                                {{$value->Name}}
                                            </div>
                                            @endif
                                            <!-- <tr>
                                                @if($i == 1)
                                                <td class="td-first" colspan="{{$value->Quantity_Rows}}">
                                                    <span class="name-cell">
                                                        {{$value->Name}}
                                                    </span><br>
                                                    <span style="color: blue;">
                                                        {{$value->Symbols}}
                                                    </span><br>
                                                    {{$value->Note}}<br>
                                                </td>
                                                @endif
                                            </tr> -->
                                            <tr>
                                                @for($j=1; $j <= $value->Quantity_Columns; $j++)
                                                    <td class="my-td {{$value->Symbols}}-{{$i}}-{{$j}}">
                                                        {{$value->Symbols}}-{{$i}}-{{$j}}
                                                    </td>
                                                @endfor
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function filter_detail(dataDetail)
        {
            return $.ajax({
                method    : 'get',
                url       : '{{ route("masterData.warehouses.filterDetail1") }}',
                data      : dataDetail,
                dataType  : 'json',
                beforeSend: function()
                {
                    $('.loading').show();
                }
            })
        }
        function filter_detail_history(dataDetail)
        {
            return $.ajax({
                method    : 'get',
                url       : '{{ route("masterData.warehouses.filter_history") }}',
                data      : dataDetail,
                dataType  : 'json',
                beforeSend: function()
                {
                    $('.loading').show();
                }
            })
        }
        function filter_import()
        {
            return $.ajax({
                method    : 'get',
                url       : '{{ route("masterData.warehouses.filterDetail") }}',
                data      : {},
                dataType  : 'json',
            })
        }

        function filter_warehouse()
        {
            clearTimeout(check);

            check = setTimeout(function()
            {
                filter();
            }, 30000);
        }

        function filter()
        {
            filter_import().done(function(data)
            {
                for(let dat of data.data)
                {
                    if (dat.inventory.length != 0) 
                    {
                        $('.'+dat.Symbols).addClass('green')
                    } else if(dat.inventory_null.length != 0 || dat.Group_Materials_ID != null)
                    {
                        $('.'+dat.Symbols).addClass('yellow')
                    } else
                    {
                        $('.'+dat.Symbols).removeClass('green')
                        $('.'+dat.Symbols).removeClass('yellow')
                    }
                }
                filter_warehouse();
            }).fail(function(err){console.log(err)})
        }

        let check;
        filter();      

        $('.my-td').on('click', function()
        {
            let name = $(this).attr('class').split(' ')[1]
            $('#modalRequestDetail').modal();
            $('#nameDetail').text(name);
            $('.hide').hide();

            let dataDetail = {
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'ID'     : '',
                'Name'   : name,
                'Symbols': name
            }


            filter_detail(dataDetail).done(function(data)
            {
                $('.cill').remove()
                // console.log(data);
                let i = 1;
                // table.clear().draw();
                for (let dat of data.data)
                {
                    // console.log(dat);
                    let a = '';
                    for(let label of dat.inventory_nl)
                    {
                        if(label.Pallet_ID == null)
                        {
                            label.Pallet_ID = ''
                        }
                        // console.log(label.materials) 
                        a = a+`<tr class="cill">
                            <td>`+label.materials.Symbols+`</td>
                            <td>`+parseFloat(label.Inventory)+`</td>
                            <td>`+label.Pallet_ID+`</td>
                            <td>`+label.Box_ID+`</td>
                            <td>`+label.Count+`</td>
                            
                        </tr>`
                    }
                    $('#tableDetail tbody').append(a)  
                }

                $('.loading').hide();
                $('.hide').hide();
            }).fail(function(err)
            {
                console.log(err);
            })


            filter_detail_history(dataDetail).done(function(data)
            {

                $('.cill1').remove()
                let dat2 =  data.data;
                dat2.sort(function(a,b){
                  return new Date(b.Time_Created) - new Date(a.Time_Created);
                });
                console.log(dat2);
                let i = 1;
                // table.clear().draw();
                for (let dat of data.data)
                {
                    // console.log(dat);
                    let b = '';
                    let c ;
                    if(dat.Type == 0)
                    {
                        c = 'Nhập Kho';
                    }else if(dat.Type == 1)
                    {
                        c = 'Nhập Lại'; 
                    }else if(dat.Type == 2)
                    {
                        c = 'Kiểm Kê'; 
                    }
                    else if(dat.Type == 3)
                    {
                        c = 'Nhập Cập Nhật'; 
                    }
                    else if(dat.Type == 4)
                    {
                        c = 'Xuất Kho'; 
                    }
                    else if(dat.Type == 5)
                    {
                        c = 'Xuất Cập Nhật'; 
                    }
                    else if(dat.Type == 6)
                    {
                        c = 'Nhập Chuyển Kho'; 
                    }
                    else if(dat.Type == 7)
                    {
                        c = 'Xuất Kiểm Kê'; 
                    }
                    
                    if(dat.Pallet_ID == null)
                    {
                        dat.Pallet_ID = ''
                    }
                    b = b+`<tr class="cill1">
                            <td>`+(i++)+`</td>
                            <td>`+dat.materials.Symbols+`</td>
                            <td>`+parseFloat(dat.Quantity)+`</td>
                            <td>`+dat.Pallet_ID+`</td>
                            <td>`+dat.Count+`</td>
                            <td>`+dat.Box_ID+`</td>
                            <td>`+c+`</td>
                        </tr>`
                    
                    $('#tableHistory tbody').append(b)  
                    // $('#tableHistory').DataTable({
                    //     language: __languages.table,
                    //     scrollX : '100%',
                    //     scrollY : '100%'
                    // });
                }

                $('.loading').hide();
                $('.hide').hide();
            }).fail(function(err)
            {
                console.log(err);
            })
        });


    </script>
@endpush
