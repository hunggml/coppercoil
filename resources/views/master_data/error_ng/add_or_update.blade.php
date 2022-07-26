@extends('layouts.main')

@section('content')
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <span class="text-bold" style="font-size: 23px">
                            {{ __('Error') }}
                        </span>
                    </div>
                    <form role="from" method="post" action="{{ route('masterData.error.addOrUpdate') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4 hide">
                                    <label for="idError">{{ __('ID') }}</label>
                                    <input type="text" value="{{ old('ID') ? old('ID') : ($error ? $error->ID : '') }}"
                                        class="form-control" id="idError" name="ID" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nameError">{{ __('Name') }} {{ __('Error') }}</label>
                                    <input type="text" value="{{ old('ID') ? old('ID') : ($error ? $error->Name : '') }}"
                                        class="form-control" id="nameError" name="Name"
                                        placeholder="{{ __('Enter') }} {{ __('Name') }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="MaterialsProduct">{{ __('Handle') }}</label>
                                    <input type="Text" value="" class="form-control handle" id="Handle"
                                        name="Handle" placeholder="{{ __('Enter') }}">
                                </div>

                                <div class="col-1 ">
                                    <button type="button" class="btn btn-success btn-add"
                                        style="margin-top:22%">{{ __('Add') }}</button>
                                </div>
                                <div class="form-group col-md-3 quan">
                                    <label for="NoteError">{{ __('Note') }} {{ __('Error') }}</label>
                                    <input type="text"
                                        value="{{ old('ID') ? old('ID') : ($error ? $error->Note : '') }}"
                                        class="form-control" id="NoteError" name="Note"
                                        placeholder="{{ __('Enter') }} {{ __('Note') }}">
                                </div>
                                @if ($error)
                                    @foreach ($error->handle as $value)
                                        <div class="col-3 tr-{{ str_replace(' ', '', $value->Handle) }}">
                                            <label class="mater"> {{ __('Handle') }} ID</label>
                                            <input type="text" name="ListHandle[]" id="idCan66"
                                                value="{{ $value->Handle }}" class="form-control" readonly>
                                        </div>
                                        <div class="col-2 tr-{{ str_replace(' ', '', $value->Handle) }} ">
                                            <button type="button" id="{{ str_replace(' ', '', $value->Handle) }}"
                                                class="btn btn-danger btn-delete-box" style="margin-top:10%">
                                                {{ __('Delete') }}
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('masterData.error') }}" class="btn btn-info"
                                style="width: 80px">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-success float-right">{{ __('Save') }}</button>
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
        let arr = [];
        $('.btn-add').on('click', function() {
            let a = $('#Handle').val();


            if (a !== '' && $.inArray(a, arr) == '-1') {
                arr.push(a);
                $('.quan').after(`
					<div class="col-3 tr-` + a.replace(' ', '') + `">
						<label class="mater"> {{ __('Handle') }} ID</label>
						<input type="text" name="ListHandle[]" id="idCan66" value="` + a + `" class="form-control" readonly>
					</div>
					<div class="col-2 tr-` + a.replace(' ', '') + ` ">
						<button type="button" id="` + a.replace(' ', '') + `" class="btn btn-danger btn-delete-box" style="margin-top:10%">
								{{ __('Delete') }}
						</button>
					</div>
				`)
                $('.btn-delete-box').on('click', function() {
                    $('.tr-' + $(this).attr('id') + '').remove()
                    arr.splice($.inArray($(this).attr('id'), arr), 1);
                })
            }
        })
    </script>
@endpush
