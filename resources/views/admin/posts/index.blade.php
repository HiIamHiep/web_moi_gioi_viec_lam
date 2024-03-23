@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route("admin.$table.create") }}" class="btn btn-primary">
                        Create
                    </a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalCSV">
                        Import CSV
                    </button>
                    <nav class="float-right">
                        <ul class="pagination pagination-rounded mb-0" id="pagination">

                        </ul>
                    </nav>
                </div>
                <div class="card-body table-responsive-sm">
                    <table class="table table-striped table-centered mb-0" id="table-data">
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal CSV -->
    <div id="modalCSV" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import CSV</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group">
                        <label>Level</label>
                        <select name="levels" class="form-control" id="select-level" multiple >
                            @foreach($levels as $level => $value)
                            <option value="{{ $value }}">
                                {{ ucwords(strtolower($level)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>File</label>
                        <input type="file"
                               name="csv"
                               id="csv"
                               class="form-control"
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn-import-csv">Import</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#select-level').select2();

            // crawl data
            $.ajax({
                url: '{{ route('api.posts') }}',
                dataType: 'json',
                data: {page: {{ request('page') ?? '1' }}},
                success: function (response) {
                    let table = $('#table-data tbody');
                    response.data.data.forEach(function (each) {
                        renderPostsData(each, table);
                    });
                    renderPagination(response.data.pagination);
                },
                error: function (response) {
                    notifyError(response.responseJSON.message);
                }
            });

            $(document).on('click', '#pagination a', function (event) {
                event.preventDefault();
                let page = $(this).text();
                const urlParams = new URLSearchParams(window.location.search);

                urlParams.set('page', page);
                window.location.search = urlParams;
            })

            //delete post
            $(document).on('click', '#delete-post', function (event) {
                event.preventDefault();
                let route = $('#delete-post').attr('action');
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function (response) {
                        if(response.success) {
                            notifySuccess("Delete success");
                            setTimeout(function () {
                                window.location.href = '{{ route('admin.posts.index') }}'; //will redirect to your blog page (an ex: blog.html)
                            }, 3000);

                        } else {
                            showError([response.message]);
                        }
                    },
                    error: function (response) {
                        let errors;
                        if(response.responseJSON.errors){
                            errors = Object.values(response.responseJSON.errors);
                        } else {
                            errors = response.responseJSON.message;
                        }
                        showError(errors);
                    }
                });

            })

            function showError(errors) {
                let string = '<ul>';
                if(Array.isArray(errors)) {
                    errors.forEach(function (each, index) {
                        each.forEach(function (error) {
                            string += `<li>${error}</li>` ;
                        });
                    });
                } else {
                    string += `<li>${errors}</li>` ;
                }
                string += '</ul>';

                $("#div-error").html(string);
                $("#div-error").removeClass("d-none").show();
                notifyError(string);
            }

            $("#btn-import-csv").click(function (event) {
                var formData = new FormData();
                formData.append('file', $('#csv')[0].files[0]);
                formData.append('levels', $('#select-level').val());
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
                        notifySuccess('Import file csv success');
                        $('#modalCSV').modal('hide');
                        setTimeout(function () {
                            window.location.href = '{{ route('admin.posts.index') }}'; //will redirect to your blog page (an ex: blog.html)
                        }, 3000);
                    },
                    error: function (response) {

                    }
                });
            });
        });
    </script>
@endpush

