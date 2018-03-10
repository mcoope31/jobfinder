@extends('layouts.master')

@section('content')
    <h1>Company Dashboard</h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3>Newest Job Seekers</h3>
                </div>
                <div class="card-body">
                    @foreach($new_seekers as $seeker)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-3">            
                                    <?php
                                    $img_src_png = '/storage/seeker_images/seeker'.$seeker->user_id.'.png';
                                    $img_src_jpg = '/storage/seeker_images/seeker'.$seeker->user_id.'.jpg';
                                    ?>
                                    @if(file_exists(public_path($img_src_png)))
                                        <img style="width:100px;height:100px;" id='img-upload' src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                    @elseif(file_exists(public_path($img_src_jpg)))
                                        <img style="width:100px;height:100px;" id='img-upload' src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                    @else
                                        <img style="width:100px;height:100px;" src='/storage/seeker_images/noimage.png'>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h4>{{ $seeker->name }}</h4>
                                    <a href="{{ url('profile/'.$seeker->user_id) }}" class="btn btn-primary btn-sm pull-right">View Profile</a>
                                    <p>{{ $seeker->city }}, {{ $seeker->state }}</p>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3>Job Skills Ranked by Average Rating</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item bg-light">
                            <div class="row">
                                <div class="col-md-7">
                                    <strong>Skill</strong>
                                </div>
                                <div class="col-md-3">
                                    <strong>Jobs</strong>
                                </div>
                                <div class="col-md-2">
                                    <strong>Rating</strong>
                                </div>
                            </div>
                        </li>
                        @foreach($job_skills as $job_skill)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <strong>{{$job_skill->name}}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="badge badge-primary badge-pill ml-3">{{$job_skill->num_seekers}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge badge-dark">{{$job_skill->average_rating}}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection