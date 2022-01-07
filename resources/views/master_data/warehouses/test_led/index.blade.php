@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="text-bold" style="font-size: 23px">
                        {{ __('Test') }} {{__('Led')}}
                    </span>
                </div>

                <div class="card-body row">
                    <div class="form-group col-md-2">
                        <label>{{__('MAC')}}</label>
                        <select id="selectMac" class="form-control select2">
                            <option value="0">{{__('Choose')}} {{__('MAC')}}</option>
                            @foreach($macs->unique('MAC') as $mac)
                                @if($mac->MAC != '')
                                <option value="{{$mac->MAC}}">{{$mac->MAC}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{__('Position')}} {{__('Led')}}</label>
                        <input type="number" id="positionLed" min="0" max="255" class="form-control min" value="0">
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{__('R')}}</label>
                        <input type="number" id="rLed" min="0" max="255" class="form-control min max" value="0">
                        <span role="alert" class="hide error-r">
                            <strong style="color: red" class="error-r"></strong>
                        </span>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{__('G')}}</label>
                        <input type="number" id="gLed" min="0" max="255" class="form-control min max" value="0">
                        <span role="alert" class="hide error-g">
                            <strong style="color: red" class="error-g"></strong>
                        </span>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{__('B')}}</label>
                        <input type="number" id="bLed" min="0" max="255" class="form-control min max" value="0">
                        <span role="alert" class="hide error-b">
                            <strong style="color: red" class="error-b"></strong>
                        </span>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{__('Status')}} {{__('Led')}}</label>
                        <select name="" id="statusLed" class="form-control">
                            <option value="0" selected>{{__('Turn Off')}}</option>
                            <option value="1">{{__('Turn On')}}</option>
                            <option value="2">{{__('Flicker')}}</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger btn-reset float-right" style="width: 100px">
                        {{__('Reset')}}
                    </button>
                    <button class="btn btn-success btn-test float-right" style="margin-right: 5px; width: 100px">
                        {{__('Test')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $('.select2').select2();
        function turnOnOrOffLed(data)
        {
            return $.ajax({
                method  : 'get',
                url     : '{{ route("masterData.warehouses.turnOnOffLed") }}',
                data    : data,
                dataType: 'json'
            })
        }

        function checkLed()
        {
            data = {
                '_token'      : $('meta[name="csrf-token"]').attr('content'),
                'MAC'         : $('#selectMac').val(),
                'Position_Led': $('#positionLed').val() == 0 ? '' : $('#positionLed').val(),
                'R'           : $('#rLed').val(),
                'G'           : $('#gLed').val(),
                'B'           : $('#bLed').val(),
                'Status_Led'  : $('#statusLed').val(),
            }

            $('.hide').hide();

            turnOnOrOffLed(data).done(function(data)
            {
                // console.log(data);
                if (data.errors.length != 0) 
                {
                    if (typeof data.errors.R !== 'undefined') 
                    {
                        $('.error-r').show();
                        let text = '';
                        for(let err of data.errors.R)
                        {
                            if (text == '') 
                            {
                                text = err;
                            } else
                            {
                                text = text+ ' ' +__and + ' ' + err;
                            }
                        }

                        $('strong.error-r').text(text);
                    }

                    if (typeof data.errors.G !== 'undefined') 
                    {
                        $('.error-g').show();
                        let text = '';
                        for(let err of data.errors.G)
                        {
                            if (text == '') 
                            {
                                text = err;
                            } else
                            {
                                text = text+ ' ' +__and + ' ' + err;
                            }
                        }

                        $('strong.error-g').text(text);
                    }

                    if (typeof data.errors.B !== 'undefined') 
                    {
                        $('.error-b').show();
                        let text = '';
                        for(let err of data.errors.B)
                        {
                            if (text == '') 
                            {
                                text = err;
                            } else
                            {
                                text = text+ ' ' +__and + ' ' + err;
                            }
                        }

                        $('strong.error-b').text(text);
                    }
                } else
                {                    
                    Toast.fire({
                        icon : 'success',
                        title : __update.success
                    })
                }
                
            }).fail(function(err)
            {
                console.log(err)
                Toast.fire({
                    icon : 'warning',
                    title : __update.fail
                })
            })
        }

        $('.btn-test').on('click', function()
        {
            checkLed();
        })

        $('#positionLed').on('keyup', function(e)
        {
            if (e.which == 13) 
            {
                checkLed();
            }
        });

        $('#rLed').on('keyup', function(e)
        {
            if (e.which == 13) 
            {
                checkLed();
            }
        });

        $('#gLed').on('keyup', function(e)
        {
            if (e.which == 13) 
            {
                checkLed();
            }
        });

        $('#bLed').on('keyup', function(e)
        {
            if (e.which == 13) 
            {
                checkLed();
            }
        });

        $('.btn-reset').on('click', function()
        {
            $('#selectMac').val(0).select2();
            $('#positionLed').val(0);
            $('#rLed').val(0);
            $('#gLed').val(0);
            $('#bLed').val(0);
            $('#statusLed').val(0);

            checkLed();
        });

    </script>
@endpush
