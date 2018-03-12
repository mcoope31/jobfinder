@extends('layouts.master')

@section('content')

    <div class="row">
        
        <div class="col-md-3">

            <?php
            $img_src_png = '/storage/seeker_images/seeker'.$info->user_id.'.png';
            $img_src_jpg = '/storage/seeker_images/seeker'.$info->user_id.'.jpg';
            ?>
            @if(file_exists(public_path($img_src_png)))
                <img style="width:250px;height:250px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
            @elseif(file_exists(public_path($img_src_jpg)))
                <img style="width:250px;height:250px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
            @else
                <img style="width:250px;height:250px;" src='/storage/seeker_images/noimage.png'>
            @endif

        
        </div>
        <div class="col-md-9">
            
            <p style="font-size:2em;">
                {{ $info->name }}
                @if(auth()->check() && $info->user_id == auth()->user()->id)
                    <a href="{{ url('/profile/'.$info->user_id.'/edit') }}" class="btn btn-sm btn-danger">Edit Profile</a>
                @endif
                <span class="float-right badge badge-primary" style="font-size:0.5em;">Joined: {{ $info->created_at->diffForHumans() }}</span>
            </p>
            <p>
                <strong>Age: </strong> 
                @if(!empty($info->age))
                    {{ $info->age }}
                @else
                    N/A
                @endif
            </p>
            <p>
                <strong>Location: </strong> 
                @if(!empty($info->city) && !empty($info->state))
                    {{ $info->city }}, {{ $info->state }}
                @else
                    N/A
                @endif
            </p>
            <p>
                <strong>Email: </strong> 
                {{ $info->user->email }}
            </p>
            <p>
                <strong>Phone: </strong> 
                @if(!empty($info->phone))
                    {{ $info->phone }}
                @else
                    N/A
                @endif
            </p>
            @if(auth()->check() && $info->user_id == auth()->user()->id)
            @else
                <a href="{{ url($info->user_id.'/report_user') }}" class="pull-right btn btn-sm btn-danger">Report Job Seeker</a>
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
        
            <div class="card">
                <div class="card-header bg-dark text-white">
                    @if(auth()->check() && $info->user_id == auth()->user()->id)
                        <a href="{{ url('/profile/'.$info->user_id.'/edit_skills') }}" class="pull-right btn btn-sm btn-danger">Edit Skills</a>
                    @endif
                    <h5><strong>Skills</strong></h5>
                </div>
                <div class="card-body">
                    @if(count($skills)==0)
                        <div class="alert alert-danger">
                            <h5 style="text-align: center;">Use has not added Skills</h5>
                        </div>
                    @endif
                    <ul class="list-group">
                        @foreach($skills as $skill)
                            <li class="list-group-item">
                                <span class="badge badge-secondary pull-right">{{ $skill->skill->category }}</span>
                                {{ $skill->skill->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    @if(auth()->check() && $info->user_id == auth()->user()->id)
                        <a href="{{ url('/profile/'.$info->user_id.'/edit_experience') }}" class="pull-right btn btn-sm btn-danger">Edit Experience</a>
                    @endif
                    <h5><strong>Work Experience</strong></h5>
                </div>
                <div class="card-body">
                    @if(count($experiences)==0)
                        <div class="alert alert-danger">
                            <h5 style="text-align: center;">User has not added any Experience</h5>
                        </div>
                    @endif
                    @foreach($experiences as $experience)
                    <div class="card">
                        <div class="card-header">
                            <strong>{{$experience->job_title}}</strong> at {{$experience->company}}
                        </div>
                        <div class="card-body">
                            <h5><span class="badge badge-info">
                                @if($experience->present)
                                    <strong>{{date('M d, Y', strtotime($experience->started))}}</strong> to <strong>Present</strong>
                                @else
                                    <strong>{{date('M d, Y', strtotime($experience->started))}}</strong> to <strong>{{date('M d, Y', strtotime($experience->ended))}}</strong>
                                @endif
                            </span></h5>
                            {{$experience->description}}
                        </div>
                    </div>
                    <br>
                    @endforeach
                </div>
                
            </div>
            <hr>
            <div class="card">
                <div class="card-header bg-dark text-white">
                    @if(auth()->check() && $info->user_id == auth()->user()->id)
                        <a href="{{ url('/profile/'.$info->user_id.'/edit_education') }}" class="pull-right btn btn-sm btn-danger">Edit Education</a>
                    @endif
                    <h5><strong>Education</strong></h5>
                </div>
                <div class="card-body">
                    @if(count($educations)==0)
                        <div class="alert alert-danger">
                            <h5 style="text-align: center;">User has not added any Education</h5>
                        </div>
                    @endif
                    @foreach($educations as $education)
                    <div class="card">
                        <div class="card-header">
                            
                            <strong>{{$education->education->education}}</strong> at {{$education->university}}
                        </div>
                        <div class="card-body">
                            <span class="badge badge-info pull-right">
                                    <strong>{{date('M d, Y', strtotime($education->achieved))}}</strong>
                            </span>
                            {{$education->title}}
                        </div>
                    </div>
                    <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        
        <div class="col-md-6">
            <div class="form-group">
                <a href="{{ URL::previous() }}" class="btn btn-danger btn-block">Back</a>
            </div>
        </div>

    </div>

@endsection