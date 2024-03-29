@extends('layout_frontpage.master')
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="card card-pricing">
                <div class="card-content">
                    <ul>
                        <li><i class="material-icons text-success">check</i>{{ $post->remotable_name }}</li>
                        <li><i class="material-icons @if($post->can_parttime) text-success @else text-danger @endif ">
                                @if($post->can_parttime)
                                    {{ 'check' }}
                                @else
                                    {{ 'close' }}
                                @endif
                            </i>
                            Part Time
                        </li>
                        @isset($post->number_applicants)
                        <li><i class="material-icons text-danger"></i>Number applicants: {{ $post->number_applicants }}</li>
                        <li><i class="material-icons text-danger"></i>Available:
                            @if($post->is_not_available)
                                {{ __('frontpage.not_available') }}
                            @else
                                {{ __('frontpage.available') }}
                            @endif

                        </li>
                        @endisset
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <h2 class="title">
                {{ $post->job_title }}
            </h2>
            <h3 class="main-price">
                {{ $post->salary }}
            </h3>
            <h4>
                Location: {{ $post->location }} -
                <a href="#">
                    {{ $post->company->name }}
                </a>
            </h4>
            @isset($post->requirement)
            <div id="acordeon">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-border panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h4 class="panel-title">
                                    Description
                                    <i class="material-icons">keyboard_arrow_down</i>
                                </h4>
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <p>
                                    {{ $post->requirement }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--  end acordeon -->
            @endisset

            @isset($post->file)
            <div class="row text-right">
                <a href="{{ $post->file->link }}" target="_blank">
                    <button class="btn btn-rose btn-round">
                            Open File
                        <i class="fa fa-file"></i>
                    </button>
                </a>
            </div>
            @endisset
        </div>
    </div>
@endsection
