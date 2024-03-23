@extends('layout_frontpage.master')
@push('css')
    <style>
        @media only screen and (max-width: 600px) {
            .responsive-mobile {
                display: flex;
                justify-content: right ;
            }
            .fix-responsive-mobile {
            }
        }
    </style>
@endpush
@section('content')
    <form class="navbar-form navbar-right responsive-mobile" action="#" method="get">
        @csrf
        @method('GET')
        <div class="form-group form-white is-empty fix-responsive-mobile">
            <input type="text" class="form-control" placeholder="Search" id="input-search-posts">
            <span class="material-input"></span>
        </div>
    </form>
    <div class="row">
        <!-- sidebar -->

        @include('layout_frontpage.sidebar')

        <!-- end sidebar -->
        <div class="col-md-9">
            <!-- content -->
            @foreach($posts as $post)
                <x-post :post="$post"/>
            @endforeach

            <!-- end content -->
        </div>

    </div>
    <ul class="pagination pagination-info" style="float: right;">
        {{ $posts->appends(request()->all())->links() }}
    </ul>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function () {
            var slider2 = document.getElementById('sliderRefine');

            const min_salary = parseInt($('#input-min-salary').val());
            const max_salary = parseInt($('#input-max-salary').val());

            noUiSlider.create(slider2, {
                start: [min_salary, max_salary],
                connect: true,
                range: {
                    'min': [{{ $configs['filter_min_salary'] }} - 100],
                    'max': [{{ $configs['filter_max_salary'] }} + 500]
                },
                step: 50,
            });
            let val;
            slider2.noUiSlider.on('update', function (values, handle) {
                val = Math.round(values[handle]);
                if (handle) {
                    $('#span-max-salary').text(val);
                    $('#input-max-salary').val(val);
                } else {
                    $('#span-min-salary').text(val);
                    $('#input-min-salary').val(val);
                }
            });

            let timeOutSearchPosts = null;

            // @todo @pobby update search job title with ajax
            $('#input-search-posts').keyup(function() {
                let that = $(this);
                let inputText = that.val();
                let textLength = inputText.length;

                clearTimeout(timeOutSearchPosts);

                timeOutSearchPosts = setTimeout(function () {
                    if(textLength > 0) {
                        $.ajax({
                            url: '{{ route('applicant.index') }}',
                            type: 'GET',
                            dataType: 'json',
                            data: { q: inputText },
                            success: function(response) {

                            },
                            error: function (response) {

                            }
                        });
                    }
                }, 500)
            });

        });
    </script>
@endpush
