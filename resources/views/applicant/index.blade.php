@extends('layout_frontpage.master')
@section('content')
    <h2 class="section-title">{{ __('frontpage.title') }}</h2>
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
        {{ $posts->links() }}
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
        });
    </script>
@endpush
