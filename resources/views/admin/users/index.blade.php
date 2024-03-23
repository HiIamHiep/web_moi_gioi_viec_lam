@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .change-min-width {
            min-width: 100px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 col-sm-6">

                        </div>
                        <div class="col-sm-12 col-sm-6">
                            <div class="text-sm-right">
                                <form>

                                    Search:
                                    <label>
                                        <input type="text" name="q" class="form-control form-control-sm" placeholder=""
                                               aria-controls="products-datatable"
                                               id="input-search"
                                        >
                                    </label>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="topnav shadow-sm" style="background: none;">
                        <form class="form-inline" id="form-filter">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle change-min-width" type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('frontpage.role') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="drop-down-item col-3">
                                        <select class="dropdown-menu form-control select-filter" name="role"
                                                id="select-role">
                                            <option value="">{{ ucfirst(__('frontpage.all')) }}</option>
                                            @foreach($roles as $role => $value)
                                                <option value="{{ $value }}"
                                                        @if((string)$value === $selectedRole) selected @endif
                                                >
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle change-min-width" type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('frontpage.city') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="drop-down-item col-3">
                                        <select class="dropdown-menu form-control select-filter" name="city"
                                                id="select-city">--}}
                                            <option value="">{{ ucfirst(__('frontpage.all')) }}</option>
                                            @foreach($cities as $city)
                                                <option
                                                    @if($city === $selectedCity) selected @endif
                                                >
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle change-min-width" type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('frontpage.company') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="drop-down-item col-3">
                                        <select class="dropdown-menu form-control select-filter" name="company"
                                                id="select-company">
                                            <option value="">{{ ucfirst(__('frontpage.all')) }}</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}"
                                                        @if($company->id === (int)$selectedCompany) selected @endif
                                                >
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive-sm">
                <table class="table table-hover table-centered mb-0" id="table-data">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Info</th>
                        <th>Role</th>
                        <th>City</th>
                        <th>Company</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $each)
                        <tr>
                            <td>
                                <a href="{{ route("admin.$table.show", $each) }}">
                                    {{ $each->id }}
                                </a>
                            </td>
                            <td>
                                <img src="{{ $each->avatar }}" height="100">
                            </td>
                            <td>
                                {{ $each->name }} - {{ $each->gender_name }}
                                <br>
                                <a href="mailto:{{ $each->email }}">
                                    {{ $each->email }}
                                </a>
                                <br>
                                <a href="tel:{{ $each->phone }}">
                                    {{ $each->phone }}
                                </a>
                            </td>
                            <td>
                                {{ $each->role_name }}
                            </td>
                            <td>
                                {{ $each->city }}
                            </td>
                            <td>
                                {{ optional($each->company)->name }}
                            </td>
                            <td>
                                <form action="{{ route("admin.$table.destroy", $each) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <nav class="float-right">
                    <ul class="pagination pagination-rounded mb-0" id="pagination">
                        {{ $data->appends(request()->all())->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{--    @todo @pobby bắt thêm trường hợp truyền lên trống--}}
    <script>
        var page_url = new URL(window.location.href);
        var filters = {};

        function getUserFromApi(filters) {
            $.ajax({
                url: '{{ route('admin.users.api') }}',
                type: 'GET',
                dataType: 'json',
                data: filters,
                success: function (response) {
                    let table = $('#table-data > tbody');
                    let pagination = $('#pagination');
                    let data = response.data.data;
                    let links = response.data.links
                    let request = page_url.search.replace('?', '&');

                    table.empty();
                    pagination.empty();

                    data.forEach(function (each) {
                        renderUsersData(each, table);
                    })
                    renderPagination(links, request);
                },
                errors: function (response) {
                    notifyError(response.responseJSON.message);
                }
            })
        }

        $(document).ready(function () {
            var timeOut = null;

            $(".select-filter").change(function (event) {
                let that = $(this);
                event.preventDefault();

                // đẩy tham số lên thanh điạ chỉ
                let name = that.attr('name');
                let value = that.val();
                page_url.searchParams.set(name, value);
                window.history.pushState({}, '', page_url);

                // lấy tham số trên thanh địa chỉ về
                let searchParams = new URLSearchParams(page_url.search);
                filters['role'] = searchParams.get('role');
                filters['city'] = searchParams.get('city');
                filters['company'] = searchParams.get('company');
                filters['q'] = searchParams.get('q');

                clearTimeout(timeOut);

                timeOut = setTimeout(getUserFromApi(filters), 500);
            })

            $("#input-search").on('keyup change', function () {
                clearTimeout(timeOut);

                let that = $(this);
                let value = that.val();
                let name = that.attr('name');

                page_url.searchParams.set(name, value);
                window.history.pushState({}, '', page_url);

                let searchParams = new URLSearchParams(page_url.search);
                filters['role'] = searchParams.get('role');
                filters['city'] = searchParams.get('city');
                filters['company'] = searchParams.get('company');
                filters['q'] = searchParams.get('q');

                timeOut = setTimeout(getUserFromApi(filters), 500);
            })
        })
    </script>
@endpush
