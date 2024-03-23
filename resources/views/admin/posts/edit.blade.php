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
                    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" id="form-edit-post">
                        @csrf
{{--                        @todo @pobby lỗ hổng bảo mật chăng ??--}}
                        <input type="hidden" name="id" value="{{ $post->id }}">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>{{ __('adminpage.job_title') }}</label>
                                <input type="text" name="job_title" id="title" class="form-control"
                                       value="{{ $post->job_title }}">
                            </div>
                            <div class="form-group col-6">
                                <label>{{ __('adminpage.slug') }}</label>
                                <input type="text" class="form-control" name="slug" id="slug" value="{{ $post->slug }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <select class="form-control" name="company" id="select-company"></select>
                        </div>
                        <div class="form-group">
                            <label>Language (*)</label>
                            <select class="form-control" multiple name="languages[]" id="select-language"></select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('adminpage.level') }}</label>
                            <select name="levels[]" class="form-control" id="select-level" multiple>
                                @foreach($levels as $level => $value)
                                    <option value="{{ $value }}"
                                            @if(in_array($value, $arr['levels']))  selected @endif
                                    >
                                        {{ ucwords(strtolower($level)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row select-location">
                            <div class="form-group col-6">
                                <label>{{ __('adminpage.city') }} (*)</label>
                                <select type="text" name="city" id="select-city"
                                        class="form-control select-city"></select>
                            </div>
                            <div class="form-group col-6">
                                <label>{{ __('adminpage.district') }}</label>
                                <select type="text" name="district" id="select-district"
                                        class="form-control select-district"></select>
                            </div>
                        </div>
                        <div class="form-group">
{{--                            @todo @pobby append insert file word... job description--}}
                            <label>{{ __('adminpage.link_jd') }}</label>
                            <input type="hidden" name="file_id" value="{{ $file->id }}">
                            <input type="text" class="form-control" name="file_link"
                                   value="{{ $file->link }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label>Min Salary</label>
                                <input type="number" class="form-control" name="min_salary"
                                       value="{{ $post->min_salary}}">
                            </div>
                            <div class="form-group col-4">
                                <label>Max Salary</label>
                                <input type="number" class="form-control" name="max_salary"
                                       value="{{ $post->max_salary}}">
                            </div>
                            <div class="form-group col-4">
                                <label>Currency Salary</label>
                                <select class="form-control" name="currency_salary" id="select-district">
                                    @foreach($currencies as $currency => $value)
                                        <option value="{{ $value }}" @if($value == $post->currency_salary) { selected
                                                } @endif>{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-8">
                                <label>Requirement</label>
                                <textarea name="requirement" id="text-requirement">{{ $post->requirement }}</textarea>
                            </div>
                            <div class="form-group col-4">
                                <label>Number Applicant</label>
                                <input type="number" class="form-control" name="number_applicant"
                                       value="{{ $post->number_applicants }}">
                                <br>
                                <label>Remotables</label>
                                <select name="remotable" class="form-control">
                                    @foreach($remotables as $key => $value)
                                        <option value="{{ $value }}" @if($value == $post->remotable) { selected
                                                } @endif>
                                            {{ __('frontpage.'. strtolower($key)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <br>
                                <input type="checkbox" name="can_parttime" id="can_parttime" data-switch="info"
                                       @if($post->can_parttime) { checked } @endif/>
                                <label for="can_parttime" data-on-label="Can Part-time"
                                       data-off-label="No Part-time"></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ $post->start_date ? date('Y-m-d', strtotime($post->start_date)) : '' }}">
                            </div>
                            <div class="form-group col-6">
                                <label>End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ $post->end_date ? date('Y-m-d', strtotime($post->start_date)) : '' }}">
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
                    <button type="button" onclick="submitForm('create-company')" class="btn btn-success">Create</button>
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
        function submitForm(type) {
            const obj = $("#form-" + type) ;
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
                    console.log(response);
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

        async function checkCompany() {
            $.ajax({
                url: '{{ route('api.companies.check') }}/' + $("#select-company").val(),
                type: 'GET',
                dataType: 'json',
                success: async function (response) {
                    if (response.data) {
                        submitForm('edit-post');
                    } else {
                        $("#modal-company").modal("show");
                        $("#company").val($("#select-company").val())
                        $("#city").val($("#select-city").val()).trigger('change');
                        $("#district").val($("#select-district").val()).trigger();
                    }
                },
            });
        }

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

            const response = await fetch('{{ asset('locations/') }}' + path);
            const districts = await response.json();
            let string = '<option selected></option>';
            const selectedValue = '{{ $post->district }}';
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

        function loadLanguages(obj) {
            let stringIdLanguages = '{{ $stringIdLanguages }}';
            let arrSeletedLanguages = stringIdLanguages.split(", ");
            arrSeletedLanguages = arrSeletedLanguages.map(function (id) {
                return parseInt(id, 10);
            })

            $.ajax({
                url: '{{ route('api.languages') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;
                    data.forEach(function (each) {
                        let check = arrSeletedLanguages.includes(each.id);
                        obj.append($(`<option value="${each.id}">${each.name}</option>`).attr('selected', check));
                    })
                },
                // @todo @pobby code thêm in ra lỗi nếu không load được
                error: function (response) {

                }
            });
        }

        function loadCompanies(obj) {
            let selectedCompany = '{{ $post->company->name }}';

            $.ajax({
                url: '{{ route('api.companies') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;
                    data.forEach(function (each) {
                        let check = each.name === selectedCompany ? true : false ;
                        obj.append($(`<option>${each.name}</option>`).attr('selected', check));
                    })

                },
                // @todo @pobby code thêm in ra lỗi nếu không load được
                error: function (response) {

                }
            });
        }
        async function syncChangeCity(obj, syncCity) {
            let selectCity = obj.val();
            syncCity.val(selectCity).change();
            await obj.parents('.select-location').find(".select-district").empty();
            await syncCity.parents('.select-location').find(".select-district").empty();
            const path = obj.parents('.select-location').find(".select-city option:selected").data("path");
            if(!path){
                return;
            }

            const response = await fetch('{{ asset('locations/') }}' + path);
            const districts = await response.json();
            let string = '<option selected><option>';
            const selectedValue = '{{ $post->district }}';
            $.each(districts.district, function (index, each) {
                if (each.pre === 'Quận'|| each.pre === 'Huyện') {
                    string +=`<option`;
                    if(selectedValue === each.name ) {
                        string += ` selected `;
                    }
                    string += `>${each.name}</option>`;
                }
            })
            await obj.parents('.select-location').find(".select-district").append(string);
            await syncCity.parents('.select-location').find(".select-district").append(string);
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

        function checkSlug() {
            $('#btn-submit').attr('disabled', true);
            let value = $("#slug").val();
            $.ajax({
                url: '{{ route('api.posts.slug.check') }}',
                type: 'GET',
                dataType: 'json',
                data: { slug: value },
                success: function (response) {
                    if (response.success) {
                        $('#btn-submit').attr('disabled', false);
                    }
                }
            });
        }

        $(document).ready(async function () {
            $("#text-requirement").summernote();
            $(".select-district").select2({
                tags: true,
            });
            $(".select-city").select2({
                tags: true,
            });
            $("#select-language").select2();
            $("#select-company").select2({
                tags: true,
            });
            $("#select-level").select2({
                // tags: true,
            });
            const responseCity = await fetch('{{ asset('locations/index.json') }}');
            const cities = await responseCity.json();
            let citiesCurrent = `{{ $post->city }}`;


            // load languages
            loadLanguages($("#select-language"));

            // load companies
            loadCompanies($('#select-company'));

            // load Cities
            let arrCities = Object.entries(cities);
            arrCities.forEach(function (each) {
                let cityName = each[0];
                let city = each[1];
                let check = false;

                let str1 = cityName.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                let str2 = citiesCurrent.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (str1 === str2) {
                    check = true;
                }
                $("#select-city")
                    .append($(`<option data-path="${city.file_path}">${cityName}</option>`).attr('selected', check));
                $("#city")
                    .append($(`<option data-path="${city.file_path}">${cityName}</option>`).attr('selected', check));
            });

            // load district
            loadDistrict($("#select-city").parents('.select-location'));
            loadDistrict($("#city").parents('.select-location'));

            // load then change city
            $('#select-city').change(function () {
                syncChangeCity($(this), $("#city"));
            })

            // generate slug dont duplicate
            $(document).on('change', '#select-language , #select-company, #select-city', function () {
                generateTitle();
            })

            var timeOut = null;

            $("#slug").on("keyup change", function () {
                clearTimeout(timeOut);
                timeOut = setTimeout(checkSlug, 500);
            });

            // submit form edit post
            $("#form-edit-post").validate({
                rules: {
                    company: {
                        required: true,

                    },
                    languages: {
                        required: true,

                    }
                },
                submitHandler: function(form) {
                    checkCompany();
                    // form.submit();
                },
            });
        })
    </script>
@endpush
