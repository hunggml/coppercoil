@extends('layouts.main')

@section('content')

	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif

	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('warehousesystem.productivity.import_file')])
	@include('basic.modal_table_error')
	@endif
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
						{{__('Print')}} {{__('Label')}}
	                	</span>
	                </div>
	                <div class="card-body">
	                	<form action="{{ route('warehousesystem.report') }}" method="get">
	                		@csrf
	                		<div class="row">
								<div class="form-group ty col-md-2">
		                        	<label>{{__('Choose')}} {{__('Type')}}</label>
		                        	<select class="custom-select select2 type" name="Type">
                                        <option value="" >
                                            {{__('Choose')}} {{__('Type')}}
		                          		</option>
		                          		<option value="1" >
		                          			{{__('Box')}}
		                          		</option>
										<option value="2">
		                          			{{__('Text')}}
		                          		</option>
		                        	</select>
	                      		</div>
                                </div>
                            <div class="row">
                                <div class="form-group col-md-2 box hide">
		                        	<label>{{__('Choose')}} {{__('Box')}}</label>
		                        	<select class="custom-select input-box select2" >
		                          		<option value="0" >
                                          {{__('Choose')}} {{__('Box')}}
		                          		</option>
                                        @foreach($list_box->GroupBy('Box_ID') as $key => $value)
										<option value="{{$key}}">
		                          			{{$key}}
		                          		</option>
                                        @endforeach
		                        	</select>
	                      		</div>
                                <div class="form-group col-md-2 box hide">
		                        	<label>{{__('Materials')}}</label>
		                        	<input type="text" name="Text" id="Materials" class="form-control">
	                      		</div>
                                <div class="form-group col-md-1 box hide">
		                        	<label>{{__('Quantity')}}(kg)</label>
		                        	<input type="text" name="Text" id="Quantity" class="form-control">
	                      		</div>
                                <div class="form-group col-md-2 box hide">
		                        	<label>{{__('PO')}}</label>
		                        	<input type="text" name="Text" id="PO" class="form-control">
	                      		</div>
                                <div class="form-group col-md-2 box hide">
		                        	<label>{{__('Lot')}}</label>
		                        	<input type="text" name="Text" id="Lot" class="form-control">
	                      		</div>
                                <div class="form-group col-md-1box hide">
		                        	<label>{{__('Spool')}}</label>
		                        	<input type="text" name="Text" id="Spool" class="form-control">
	                      		</div>
                                <div class="form-group col-md-1 box hide">
		                        	<label>{{__('NSX')}}</label>
		                        	<input type="text" name="Text" id="NSX" class="form-control">
	                      		</div>
                                <div class="form-group col-md-2 text hide">
		                        	<label>{{__('Enter')}} {{__('Text')}}</label>
		                        	<input type="text" name="Text" id="Text" class="form-control">
	                      		</div>
	                      		<div class="col-md-2 " style="margin-top: 33px">
	                      			<button type="button" class="btn btn-info btn-save">{{__('Save')}} {{__('Label')}}</button>
                                      <button type="button" class="btn btn-info btn-print">{{__('Print')}} {{__('Label')}}</button>
	                      		</div>
                            </div>
	                	</form>
                        <br>
                        <div class="row label" >    

                        </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
@push('scripts')
	<script>
		$('.select2').select2()
        
        $('.type').on('input',function(){
            let a = $(this).val()
            if(a == 1)
            {
                $('.box').show()
                $('.text').hide()
            }
            else
            {
                $('.box').hide()
                $('.text').show()  
            }
        })  
		$('.input-box').on('input',function()
        {
            let a = $(this).val()
            console.log(a)
            $.ajax({
                type: "GET",
                url: "{{route('warehousesystem.import.print_label.detail_box')}}",
                data: { 
                    Box_ID:a,
                },
                success: function(data) 
                {
                    console.log(data.data)
                    $('#Materials').val(data.data.materials.Symbols)
                    $('#Quantity').val(parseFloat(data.data.Quantity))
                    $('#PO').val(data.data.materials.Wire_Type)
                    $('#Lot').val(data.data.Lot_No)
                    $('#Spool').val(data.data.Case_No)
                    $('#NSX').val(data.data.Packing_Date)
                },
                error: function() {
                }
			});
        })
        $('.btn-save').on('click',function(){
            let a = $('.type :selected').val()
            let c = $('.input-box :selected').val()
            let b = $('.table-label').length
            $('.label').append(`
            <table style="border: 1px solid black; margin-left :10px;margin-top:10px" class=" table-label col-1">
                <tbody>
                    <tr>
                        <td id="qrcode-`+b+`"></td>
                    </tr>
                    <tr>
                        <td id="text1-`+b+`"></td>
                    </tr>
                    <tr>
                        <td id="text2-`+b+`"></td>
                    </tr>
                </tbody>
            </table>
            `)
            let text_box  
            if(a == 1)
            {
                $('#text1-'+b).text(c)
                $('#text2-'+b).text($('#Quantity').val())
                text_box  = `[)>[1E]06[1D]V001[1D]1ASHI[1D]S7527[1D]`+ $('#Materials').val()+`[1D]7Q`+parseFloat($('#Quantity').val())+`kg[1D]1PU1`+$('#PO').val()+`[1D]1T2`+$('#Lot').val()+`[1D]15D28092024[1D]1BWINM[1D]17D`+$('#NSX').val()+`[1D]10E`+$('#Spool').val()+`[1D]Z`+c+`[1E][04]`
            }
            else
            {
                $('#text1-'+b).text($('#Text').val())
                text_box  = $('#Text').val()
            }
            var qrcode = new QRCode(document.getElementById("qrcode-"+b), 
            {
                text: text_box,
                width : 190,
                height : 190
            });
        })
        $('.btn-print').on('click',function(){
            $('.card-header').hide()
            $('.main-footer').hide()
            $('.ty').hide()
            $('.box').hide()
            $('.text').hide() 
            $('.btn-print').hide()
            $('.btn-save').hide()
            window.print();
        })
        window.onafterprint = function() {
                $('.card-header').show()
                $('.main-footer').show()
                $('.ty').show()
                let a = $('.type').val()
                if(a == 1){
                    $('.box').show() 
                }
                else
                {
                    $('.text').show()
                }
                $('.btn-print').show()
                $('.btn-save').show()
                $('.table-label').remove()
            }

	</script>
@endpush