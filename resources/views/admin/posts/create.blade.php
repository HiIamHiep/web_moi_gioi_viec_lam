@extends('layout.master')
@push('css')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/summernote-bs4.css') }}" rel="stylesheet"/>
    <style>
        .error {
            color: red;
        }
        input[data-switch]:checked + label:after {
            left: 90px;
        }
        input[data-switch] + label {
            width: 110px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div id="div-error" class="alert alert-danger d-none"></div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('admin.posts.store') }}" method="post" id="form-create-post">
                        @csrf
                        <div class="form-group">
                            <label>Company</label>
                            <select class="form-control" name="company" id="select-company"></select>
                        </div>
                        <div class="form-group">
                            <label>Language (*)</label>
                            <select class="form-control" multiple name="languages[]" id="select-language"></select>
                        </div>
                        <div class="form-row select-location">
                            <div class="form-group col-6">
                                <label>City</label>
                                <select class="form-control select-city" name="city" id="select-city"></select>
                            </div>
                            <div class="form-group col-6">
                                <label>District</label>
                                <select class="form-control select-district" name="district" id="select-district"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label>Min Salary</label>
                                <input type="number" class="form-control" name="min_salary">
                            </div>
                            <div class="form-group col-4">
                                <label>Max Salary</label>
                                <input type="number" class="form-control" name="max_salary">
                            </div>
                            <div class="form-group col-4">
                                <label>Currency Salary</label>
                                <select class="form-control" name="currency_salary" id="select-district">
                                    @foreach($currencies as $currency => $value)
                                        <option value="{{ $value }}">{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-8">
                                <label>Requirement</label>
                                <textarea name="requirement" id="text-requirement"></textarea>
                            </div>
                            <div class="form-group col-4">
                                <label>Number Applicant</label>
                                <input type="number" class="form-control" name="number_applicant">
                                <br>
                                <input type="checkbox" id="remote" name="remotables[]" checked data-switch="success"/>
                                <label for="remote" data-on-label="Can remote" data-off-label="No remote"></label>
                                <input type="checkbox" id="office" name="remotables[]" checked data-switch="success"/>
                                <label for="office" data-on-label="Office" data-off-label="No Office"></label>
                                <br>
                                <input type="checkbox" name="can_parttime" id="can_parttime" checked data-switch="info"/>
                                <label for="can_parttime" data-on-label="Can Part-time" data-off-label="No Part-time"></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Start Date</label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="form-group col-6">
                                <label>End Date</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Title</label>
                                <input type="text" class="form-control" name="job_title" id="title">
                            </div>
                            <div class="form-group col-6">
                                <label>Slug</label>
                                <input type="text" class="form-control" name="slug" id="slug">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" id="btn-submit" >Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="modal-company" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Company</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('admin.companies.store') }}" method="post" id="form-create-company" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Company</label>
                            <input readonly name="name" id="company" class="form-control">
                        </div>
                        <div class="form-row select-location">
                            <div class="form-group col46">
                                <label>Country (*)</label>
                                <select type="text" name="country" id="country" class="form-control">
                                    @foreach($countries as $value => $country)
                                        <option value="{{ $value }}">
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label>City (*)</label>
                                <select type="text" name="city" id="city" class="form-control select-city"></select>
                            </div>
                            <div class="form-group col-4">
                                <label>District</label>
                                <select type="text" name="district" id="district" class="form-control select-district"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>Address2</label>
                                <input type="text" name="address2" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Zipcode</label>
                                <input type="number" name="zipcode" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>Phone</label>
                                <input type="number" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>Logo</label>
                                <input type=file name="logo" oninput="pic.src=window.URL.createObjectURL(this.files[0])">
                                <img id="pic" height="100"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitForm('company')" class="btn btn-success">Create</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="{{ asset('js/summernote-bs4.min.js') }}"></script>
    <script>
        function generateTitle() {
            const city = $("#select-city :selected").text();
            const company = $("#select-company :selected").text();
            let languages = [];
            $("#select-language :selected").map(function (index, each) {
                languages.push($(each).text());
            });
            languages = languages.join(",");
            let title = `(${city}) ${languages}`;
            if (company) {
                title += "-" + company;
            }
            $("#title").val(title);
            generateSlug(title);
        }
        function generateSlug(title) {
            $.ajax({
                url: '{{ route('api.posts.slug.generate') }}',
                type: 'POST',
                dataType: 'json',
                data: { title },
                success: function(response) {
                    $("#slug").val(response.data);
                    $("#slug").trigger("change");
                },
                error: function (response) {

                }
            });
        }

        async function loadDistrict(parent) {
            parent.find(".select-district").empty();
            const path = parent.find(".select-city option:selected").data("path");
            if(!path){
                return;
            }
            const response = await fetch(' {{ asset('locations/') }} ' + path);
            const districts = await response.json();
            let string = '';
            const selectedValue = $("#select-district").val();
            $.each(districts.district, function (index, each) {
                if (each.pre === 'Quận'|| each.pre === 'Huyện') {
                    string +=`<option`;
                    if(selectedValue === each.name ) {
                        string += ` selected `;
                    }
                    string += `>${each.name}</option>`;
                }
            })
            parent.find(".select-district").append(string);

        }

        async function checkCompany() {
            $.ajax({
                url: '{{ route('api.companies.check') }}/' + $("#select-company").val(),
                type: 'GET',
                dataType: 'json',
                success: async function (response) {
                    if (response.data) {
                        submitForm('post');
                    } else {
                        $("#modal-company").modal("show");
                        $("#company").val($("#select-company").val())
                        $("#city").val($("#select-city").val()).trigger('change');
                        $("#district").val($("#select-district").val()).trigger();
                    }
                },
            });
        }

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

        function submitForm(type) {
            const obj = $("#form-create-" + type) ;
            var formData = new FormData(obj[0]);
            $.ajax({
                url: obj.attr("action"),
                type: 'POST',
                dataType: 'json',
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                async: true,
                cache: false,
                data:  formData,
                success: function (response) {
                    if(response.success) {
                        $("#div-error").hide();
                        $("#modal-company").modal("hide");
                        notifySuccess();
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
        }

        $(document).ready(async function () {
            $("#text-requirement").summernote();
            $("#select-city").select2({ tags: true});
            $("#city").select2({ tags: true});
            const response = await fetch('{{ asset('locations/index.json') }}');
            const cities = await response.json();

            $.each(cities, function (index, each) {
                $("#select-city")
                    .append($(`<option data-path="${each.file_path}">${index}</option>`));
            })
            $.each(cities, function (index, each) {
                $("#city")
                    .append($(`<option data-path="${each.file_path}">${index}</option>`));
            })

            $("#select-city, #city").change(function () {
                loadDistrict($(this).parents('.select-location'));
            })

            $("#select-district").select2();
            $("#district").select2();
            await loadDistrict($("#select-city").parents('.select-location'));

            $("#select-company").select2({
                tags: true,
                ajax: {
                    // delay: 250,
                    url: '{{ route('api.companies') }}',

                    data: function (param) {
                        var queryParameters = {
                            q: param.term
                        }

                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.name
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
                                    id: item.name
                                }
                            })
                        };
                    },
                }
            });

            $(document).on('change', '#select-language , #select-company, #select-city', function () {
                generateTitle();
            });

            $("#slug").change(function () {
                $('#btn-submit').attr('disabled', true);
                $.ajax({
                    url: '{{ route('api.posts.slug.check') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: { slug: $(this).val() },
                    success: function (response) {
                        if(response.success) {
                            $('#btn-submit').attr('disabled', false);
                        }
                    }
                });
            });

            $("#form-create-post").validate({
                rules: {
                    company: {
                        required: true,

                    }
                },
                submitHandler: function(form) {
                    checkCompany();
                }
            });

        });
    </script>
@endpush

