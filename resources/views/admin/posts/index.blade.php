@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route("admin.$table.create") }}" class="btn btn-primary">
                        Create
                    </a>
                    <label for="csv" class="btn btn-info mb-0">
                        Import csv
                    </label>
                    <input type="file" name="csv" id="csv" class="d-none"
                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    <nav class="float-right">
                        <ul class="pagination pagination-rounded mb-0" id="pagination">

                        </ul>
                    </nav>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table-data">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Job title</th>
                            <th>Location</th>
                            <th>Remote</th>
                            <th>Is Part-time</th>
                            <th>Range Salary</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th>Is Pinned</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {

            // crawl data
            $.ajax({
                url: '{{ route('api.posts') }}',
                dataType: 'json',
                data: { page: {{ request('page') ?? '1' }}},
                success: function (response) {
                    response.data.data.forEach(function (each) {
                        let location = each.district + ' - ' + each.city;
                        let remotable = each.remotable ? 'x' : '';
                        let is_parttime = each.is_parttime ? 'x' : '';
                        let range_salary = (each.min_salary && each.max_salary) ? each.min_salary + ' - ' + each.max_salary : '';
                        let range_date = (each.start_date && each.end_date) ? each.start_date + ' - ' + each.end_date : '';
                        let is_pinned = each.is_pinned ? 'x' : '';
                        let created_at = convertDateToDateTime(each.created_at);
                        $('#table-data').append($('<tr>')
                            .append($('<td>').append(each.id))
                            .append($('<td>').append(each.job_title))
                            .append($('<td>').append(location))
                            .append($('<td>').append(remotable))
                            .append($('<td>').append(is_parttime))
                            .append($('<td>').append(range_salary))
                            .append($('<td>').append(range_date))
                            .append($('<td>').append(each.status))
                            .append($('<td>').append(is_pinned))
                            .append($('<td>').append(created_at))
                        )
                    });
                    response.data.pagination.forEach(function (each) {
                        $("#pagination").append($('<li>').attr('class', `page-item ${each.active ? 'active' : ''}`)
                            .append(`<a href="${each.url}" class="page-link">${each.label}</a>`)
                        )
                    })
                },
                error: function (response) {
                    $.toast({
                        heading: 'Error',
                        text: response.responseJSON.message,
                        showHideTransition: 'slide',
                        position: 'bottom-right',
                        icon: 'error'
                    });
                }
            });

            $(document).on('click', '#pagination a', function (event) {
                event.preventDefault();
                let page = $(this).text();
                console.log(page);
                const urlParams = new URLSearchParams(window.location.search);

                urlParams.set('page', page);
                window.location.search = urlParams;
            })

            $("#csv").change(function (event) {
                var formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                $.ajax({
                    url: '{{ route('admin.posts.import_csv') }}',
                    type: 'POST',
                    dataType: 'json',
                    enctype: 'multipart/form-data',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Import file csv success',
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'success'
                        });
                    },
                    error: function (response) {

                    }
                });
            });
        });
    </script>
@endpush

