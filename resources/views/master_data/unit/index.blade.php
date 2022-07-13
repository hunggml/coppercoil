@extends('layouts.main')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
    @endif

    @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
        @include('basic.modal_import', ['route' => route('masterData.unit.importFileExcel')])
        @include('basic.modal_table_error')
    @endif
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="text-bold" style="font-size: 23px">
                            {{ __('Unit') }}
                        </span>
                        <div class="card-tools">
                            @if (Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
                                <a href="{{ route('masterData.unit.show') }}" class="btn btn-warning">
                                    {{ __('Create') }} {{ __('Unit') }}
                                </a>
                            @endif

                            @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                                <button class="btn btn-success btn-import" style="width: 180px">
                                    {{ __('Import By File Excel') }}
                                </button>
                            @endif
                            @if (Auth::user()->checkRole('export_master') || Auth::user()->level == 9999)
                                <a href="{{ route('masterData.unit.export_file', ['Name' => $request->Name, 'Symbols' => $request->Symbols]) }}"
                                    class="btn btn-info " style="width: 180px">
                                    {{ __('Export By File Excel') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('masterData.unit.filter') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>{{ __('Choose') }} {{ __('Symbols') }} {{ __('Unit') }}</label>
                                    <select class="custom-select select2" name="Symbols">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Symbols') }}
                                        </option>
                                        @foreach ($units as $value)
                                            <option value="{{ $value->Symbols }}"
                                                {{ $request ? ($request->Symbols == $value->Symbols ? 'selected' : '') : '' }}>
                                                {{ $value->Symbols }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>{{ __('Choose') }} {{ __('Name') }} {{ __('Unit') }}</label>
                                    <select class="custom-select select2" name="Name">
                                        <option value="">
                                            {{ __('Choose') }} {{ __('Name') }}
                                        </option>
                                        @foreach ($units as $value)
                                            <option value="{{ $value->Name }}"
                                                {{ $request ? ($request->Name == $value->Name ? 'selected' : '') : '' }}>
                                                {{ $value->Name }}
                                            </option>
                                        @endforeach
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
                                <th>{{ __('Name') }} {{ __('Unit') }}</th>
                                <th>{{ __('Symbols') }} {{ __('Unit') }}</th>
                                <th>{{ __('Note') }}</th>
                                <th>{{ __('User Created') }}</th>
                                <th>{{ __('Time Created') }}</th>
                                <th>{{ __('User Updated') }}</th>
                                <th>{{ __('Time Updated') }}</th>
                                <th>{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($unit as $value)
                                    <tr>
                                        <th colspan="1">{{ $value->ID }}</th>
                                        <td>{{ $value->Name }}</td>
                                        <td>{{ $value->Symbols }}</td>
                                        <td>{{ $value->Note }}</td>
                                        <td>
                                            {{ $value->user_created ? $value->user_created->name : '' }}
                                        </td>
                                        <td>{{ $value->Time_Created }}</td>
                                        <td>
                                            {{ $value->user_updated ? $value->user_updated->name : '' }}
                                        </td>
                                        <td>{{ $value->Time_Updated }}</td>
                                        <td>
                                            @if (Auth::user()->checkRole('update_master') || Auth::user()->level == 9999)
                                                <a href="{{ route('masterData.unit.show', ['ID' => $value->ID]) }}"
                                                    class="btn btn-success" style="width: 80px">
                                                    {{ __('Edit') }}
                                                </a>
                                            @endif
                                            @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
                                                <button id="del-{{ $value->ID }}" class="btn btn-danger btn-delete"
                                                    style="width: 80px">
                                                    {{ __('Delete') }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2()

        $('#tableUnit').DataTable({
            language: __languages.table,
            scrollX: '100%',
            scrollY: '100%'
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).attr('id');
            let name = $(this).parent().parent().children('td').first().text();

            $('#modalRequestDel').modal();
            $('#nameDel').text(name);
            $('#idDel').val(id.split('-')[1]);
        });

        $('.btn-import').on('click', function() {
            $('#modalImport').modal();
            $('#importFile').val('');
            $('.input-text').text(__input.file);
            $('.error-file').hide();
            $('.btn-save-file').prop('disabled', false);
            $('#product_id').val('');

        });

        $('#importFile').on('change', function() {
            check_file = false;
            let val = $(this).val();
            let name = val.split('\\').pop();
            let typeFile = name.split('.').pop().toLowerCase();
            $('.input-text').text(name);
            $('.error-file').hide();

            if (typeFile != 'xlsx' && typeFile != 'xls' && typeFile != 'txt') {
                $('.error-file').show();
                $('.btn-save-file').prop('disabled', true);
            } else {
                $('.btn-save-file').prop('disabled', false);
                check_file = true;
            }
        });

        $('.btn-save-file').on('click', function() {
            $('.error-file').hide();

            if (check_file) {
                $('.btn-submit-file').click();
            } else {
                $('.error-file').show();
            }
        });
    </script>
@endpush
