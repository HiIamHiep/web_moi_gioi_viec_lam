@extends('layout.master')
@push('css')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label>Company</label>
                            <select class="form-control" name="company" id="select-company">

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Language</label>
                            <select class="form-control" multiple name="language" id="select-language">

                            </select>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <select class="form-control" name="city" id="select-city">

                            </select>
                        </div>
                        <div class="form-group">
                            <label>District</label>
                            <select class="form-control" name="district" id="select-district">

                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        async function loadDistrict() {
            $('#select-district').empty();
            const path = $("#select-city option:selected").data("path");
            $("#select-district").select2();
            const response = await fetch(' {{ asset('locations/') }} ' + path );
            const districts = await response.json();

            $.each(districts.district, function (index, each) {
                if(each.pre === 'Quận') {
                    $("#select-district")
                        .append($(`<option>${each.name}</option>`));
                }
            })
        }

        $(document).ready(async function () {
            $("#select-city").select2();
            const response = await fetch('{{ asset('locations/index.json') }}');
            const cities = await response.json();

            $.each(cities, function (index, each) {
                $("#select-city")
                    .append($(`<option value="${each.code}" data-path="${each.file_path}">${index}</option>`));
            })

            $("#select-district").select2();
            $("#select-city").change(function () {
                loadDistrict();
            })
            loadDistrict();

            $("#select-company").select2({
                ajax: {
                    // delay: 250,
                    url: '{{ route('api.companies') }}',

                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }

                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                }
            });

            $("#select-language").select2({
                ajax: {
                    // delay: 250,
                    url: '{{ route('api.languages') }}',

                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }

                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                }
            })
        });
    </script>
@endpush

