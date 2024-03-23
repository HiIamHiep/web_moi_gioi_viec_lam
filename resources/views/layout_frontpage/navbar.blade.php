<nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll=" "
     id="sectionsNav">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('applicant.index') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" style="cursor:pointer;">
                        <i class="material-icons">flag</i> Languages
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-with-icons">
                        <li>
                            <!-- item-->
                            <a href="{{ route('language', 'vi') }}" class="dropdown-item notify-item">
                                <img src="{{ asset('flag/vn.svg') }}" alt="user-image" class="mr-1" height="12">
                                <span class="align-middle">Tiếng Việt</span>
                            </a>
                        </li>
                        <li>
                            <!-- item-->
                            <a href="{{ route('language', 'en') }}" class="dropdown-item notify-item">
                                <img src="{{ asset('flag/us.svg') }}" alt="user-image" class="mr-1" height="12">
                                <span class="align-middle">English</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group form-white is-empty">
                            <input name="q" type="text" class="form-control" placeholder="Search"
                                   value="{{ request('q') ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-white btn-raised btn-fab btn-fab-mini"><i
                                class="material-icons">search</i></button>
                    </form>
                </li>

                <li class="button-container">
                    <a href="{{ route('login') }}" class="btn btn-rose btn-round">
                        <i class="fa fa-user"></i>Đăng nhập
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
