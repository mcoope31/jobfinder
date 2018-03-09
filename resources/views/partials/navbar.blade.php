<nav class="navbar navbar-expand-lg navbar-dark bg-dark pb-0 pt-0">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/storage/jf-logo_small.png" width="50" height="50">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ (Request::is('/') ? 'active' : '') }}">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item {{ (Request::is('job_openings*') ? 'active' : '') }}">
                    <a class="nav-link" href="{{ url('/job_openings') }}">Job Openings</a>
                </li>
                <li class="nav-item {{ (Request::is('companies*') ? 'active' : '') }}">
                    <a class="nav-link" href="{{ url('/companies') }}">Companies</a>
                </li>
                <li class="nav-item {{ (Request::is('about') ? 'active' : '') }}">
                    <a class="nav-link" href="{{ url('/about') }}">About</a>
                </li>
                <li class="nav-item {{ (Request::is('contact') ? 'active' : '') }}">
                    <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                </li>

            </ul>
            
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    @if(Auth::check())
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown07" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            @if(auth()->user()->user_type == 1)
                                <?php
                                $img_src_png = '/storage/seeker_images/seeker'.auth()->user()->id.'.png';
                                $img_src_jpg = '/storage/seeker_images/seeker'.auth()->user()->id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src='/storage/seeker_images/noimage.png'>
                                @endif
                            @elseif(auth()->user()->user_type == 2)
                                <?php
                                $img_src_png = '/storage/company_images/company'.auth()->user()->id.'.png';
                                $img_src_jpg = '/storage/company_images/company'.auth()->user()->id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img class="rounded-circle" style="width:40px;height:40px;padding-top:0px;padding-bottom:0px;" src='/storage/company_images/noimage.png'>
                                @endif
                            @endif

                            &nbsp;{{ Auth::user()->type->name }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown07">
                            <a class="dropdown-item" href="{{ url('/dashboard') }}">Dashboard</a>
                            @if(auth()->user()->user_type==3) <!-- ADMIN -->
                                <a class="dropdown-item" href="{{ url('/admin/all_users') }}">All Users</a>
                                <a class="dropdown-item" href="{{ url('/admin/seekers') }}">Seekers</a>
                                <a class="dropdown-item" href="{{ url('/admin/companies') }}">Companies</a>
                                <a class="dropdown-item" href="{{ url('/admin/skills') }}">Skills</a>
                                <a class="dropdown-item" href="{{ url('/admin/job_openings') }}">Job Openings</a>
                                <a class="dropdown-item" href="{{ url('/admin/applications') }}">Applications</a>
                                <a class="dropdown-item" href="{{ url('/admin/algorithms') }}">Algorithms</a>
                            @else
                                @if(auth()->user()->user_type==1) <!-- SEEKER -->
                                    <a class="dropdown-item" href="{{ url('/applications') }}">Your Applications</a>
                                @elseif(auth()->user()->user_type==2) <!-- COMPANY -->
                                    <a class="dropdown-item" href="{{ url('/company_jobs') }}">Your Job Openings</a>
                                @endif
                                <a class="dropdown-item" href="{{ url('/profile/'.Auth::user()->id) }}">Profile</a>
                                <a class="dropdown-item" href="{{ url('/profile/'.Auth::user()->id.'/account') }}">Account</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
                        </div>
                    @else
                        <a class="nav-link" href="/login">Login/Register</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>