@extends('layouts.master')

@section('content')

<?php
        $new_users = [];
        $new_users_header = "";
        $not_auth_or_is_seeker = false;
        if(!auth()->check() || auth()->user()->user_type == 1){
            $new_users = $new_companies;
            $new_users_header = "Newest Companies";
            $not_auth_or_is_seeker = true;
        } else {
            $new_users = $new_seekers;
            $new_users_header = "Newest Job Seekers";
        }
?>



    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
                <h1 class="text-center">Welcome to JobFinder</h1>
                @if(!auth()->check())
                    <a href="{{ url('/login') }}" class="btn btn-lg btn-primary pull-right">Sign up Now!</a>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{$new_users_header}}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($new_users as $user)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php $img_src = '/storage/seeker_images/seeker'.$user->user_id.'.png'; ?>
                                        @if(file_exists(public_path($img_src)))
                                            <img style="width:100px;height:100px;" src="{{$img_src}}?={{ File::lastModified(public_path().'/'.$img_src) }}">
                                        @elseif($not_auth_or_is_seeker)
                                            <img style="width:100px;height:100px;" src='/storage/company_images/noimage.png'>
                                        @else
                                                <img style="width:100px;height:100px;" src='/storage/seeker_images/noimage.png'>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h4>{{ $user->name }}</h4>
                                        <a href="{{ url('profile/'.$user->user_id) }}" class="btn btn-primary btn-sm pull-right">View Profile</a>
                                        <p>{{ $user->city }}, {{ $user->state }}</p>

                                    </div>
                                </div>
                                @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Newest Job Openings</h4>
                </div>
                <div class="card-body">
                    
                    @foreach($new_jobs as $job)
                        <div class="card mb-2">
                            <div class="card-header bg-dark text-white">
                                <strong>{{ $job->title }}</strong> at {{ $job->company->name }}
                                <span class="badge badge-light badge-pill pull-right">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Description: </strong> {{ $job->description }}
                                </p>
                                <span class="badge badge-secondary">Openings: {{ $job->openings }}</span>
                                <a href="{{ url('/job/'.$job->id) }}" class="btn btn-sm btn-primary pull-right">View Job</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection