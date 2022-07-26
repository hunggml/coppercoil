@extends('layouts.main')

@section('content')
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <span class="text-bold" style="font-size: 23px">
                            {{ __('Machine') }}
                        </span>
                    </div>
                    <form role="from" method="post" action="{{ route('masterData.machine.addOrUpdate') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="idMachine">{{ __('ID') }}</label>
                                    <input type="text"
                                        value="{{ old('ID') ? old('ID') : ($machines ? $machines->ID : '') }}"
                                        class="form-control" id="idMachine" name="ID" readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="nameMachine">{{ __('Name') }} {{ __('Machine') }}</label>
                                    <input type="text"
                                        value="{{ old('Name') ? old('Name') : ($machines ? $machines->Name : '') }}"
                                        class="form-control" id="nameMachine" name="Name"
                                        placeholder="{{ __('Enter') }} {{ __('Name') }}"
                                        @if ($show == null) { readonly } @endif required>
                                    @if ($errors->any())
                                        <span role="alert">
                                            <strong style="color: red">{{ $errors->first('Name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="symbolsMachine">{{ __('Symbols') }} {{ __('Machine') }}</label>
                                    <input type="text"
                                        value="{{ old('Symbols') ? old('Symbols') : ($machines ? $machines->Symbols : '') }}"
                                        class="form-control" id="symbolsMachine" name="Symbols"
                                        placeholder="{{ __('Enter') }} {{ __('Symbols') }}"
                                        @if ($show == null) { readonly } @endif required>
                                    @if ($errors->any())
                                        <span role="alert">
                                            <strong style="color: red">{{ $errors->first('Symbols') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="
				                    noteMachine">{{ __('Note') }}</label>
                                    <input type="text"
                                        value="{{ old('Note') ? old('Note') : ($machines ? $machines->Note : '') }}"
                                        class="form-control" id="
				                    noteMachine" name="Note"
                                        placeholder="{{ __('Enter') }} {{ __('Note') }}">
                                    @if ($errors->any())
                                        <span role="alert">
                                            <strong style="color: red">{{ $errors->first('Note') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('masterData.machine') }}" class="btn btn-info">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-success float-right"
                                @if ($show == null) { hidden="hidden" } @endif>{{ __('Save') }}</button>
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
    </script>
@endpush
