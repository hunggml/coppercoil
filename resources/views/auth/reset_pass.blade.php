@extends('layouts.main')

@section('content')

	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Reset') }} {{ __('Password')}}
	                	</span>
	                </div>
	                <form action="{{ route('account.resetNewMyPassword') }}" method="post">
	                	@csrf
	                	<div class="card-body">
	                	
	                		<div class="row">
		                		<div class="form-group col-md-12">
		                        	<label>{{__('Password') }} {{__('Old')}}</label>
		                        	<input type="password" id="passOld" class="form-control" placeholder="{{__('Password') }} {{__('Old')}}" autocomplete="off">
		                        	<span class="text-bold hide old-pass text-danger">{{__('Password')}} {{__('Incorrect')}}</span>
	                      		</div>

	                      		<div class="form-group col-md-12">
		                        	<label>{{__('Password') }} {{__('New')}}</label>
		                        	<input type="password" id="passNew" class="form-control" placeholder="{{__('Password') }} {{__('New')}}" autocomplete="off" name="Password">
	                      		</div>

	                      		<div class="form-group col-md-12">
		                        	<label>{{__('Confirm Password')}}</label>
		                        	<input type="password" id="passConfirm" class="form-control" placeholder="{{__('Confirm Password')}}" autocomplete="off">
		                        	<span class="text-bold hide pass-confirm text-danger">{{__('Password')}} {{__('Incorrect')}}</span>
	                      		</div>
                      		</div>
	                	</div>
	                	<div class="card-footer">
	                      	<a href="{{ route('home') }}" type="button" class="btn btn-info" style="width: 100px">{{__('Back')}}</a>
	                      	<button type="submit" class="btn btn-success btn-submit" style="float: right; width: 100px">{{__('Save')}}</button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
@push('scripts')
	<script>
		$('.select2').select2();

		let checkPass;

		$('#passOld').on('input', function()
		{
			$('.old-pass').hide();

			clearTimeout(checkPass);

			checkPass = setTimeout(function()
			{
				$.ajax({
					method: 'get',
					url   : window.location.origin +'/setting-account/user/check-password',
					data  : {
						'__token' : $('meta[name="csrf-token"]').attr('content'),
						'Password': $('#passOld').val()
					},
					dataType : 'json',
					success : function(data)
					{
						$('.btn-submit').prop('disabled', false);

						if (!data.status) 
						{
							$('.old-pass').show();
							$('.btn-submit').prop('disabled', true);
						}
					}
				})

			}, 300)
		});

		$('#passNew').on('input', function()
		{
			$('.btn-submit').prop('disabled', false);
		});

		$('#passConfirm').on('input', function()
		{
			$('.btn-submit').prop('disabled', false);
		});

		$('.btn-submit').on('click', function()
		{
			$('.btn-submit').prop('disabled', false);
			$('.pass-confirm').hide();
			// console.log($('#passNew').val() != $('#passConfirm').val())
			if ($('#passNew').val() != $('#passConfirm').val() || $('#passNew').val() == '' || $('#passConfirm').val() == '' || $('.old-pass:hidden').length != 0) 
			{
				$('.pass-confirm').show();

				$('.btn-submit').prop('disabled', true);

				return false;
			}
		});

	</script>
@endpush