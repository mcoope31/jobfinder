@extends('layouts.master')

@section('content')

    @if(session()->has('success'))
        <div class="alert alert-success">
            <h4><strong>Success!</strong> {{ session()->get('success') }}</h4>
        </div>
    @elseif(session()->has('error_skills'))
        <div class="alert alert-danger clearfix">
            <a href="{{ url('/profile/'.auth()->user()->id.'/edit_skills') }}" class="btn btn-success pull-right">Add Skills</a>
            <h4><strong>Oops!</strong> {{ session()->get('error_skills') }}</h4>
        </div>
    @endif

    <div class="row">

        <div class="col-md-3">

            <?php
            $img_src_png = '/storage/company_images/company'.$job->company_id.'.png';
            $img_src_jpg = '/storage/company_images/company'.$job->company_id.'.jpg';
            ?>
            @if(file_exists(public_path($img_src_png)))
                <img style="width:250px;height:250px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
            @elseif(file_exists(public_path($img_src_jpg)))
                <img style="width:250px;height:250px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
            @else
                <img style='width:250px;height:250px;' src='/storage/company_images/noimage.png'>
            @endif

            <a href="{{ url('/profile/'.$job->company_id) }}" class="btn btn-primary btn-block" style="margin-top:10px;">View Company</a>
        </div>
        <div class="col-md-9">

            <p style="font-size:2em;">
                {{ $job->title }}
                <span class="float-right badge badge-primary" style="font-size:0.5em;">Created: {{ $job->created_at->diffForHumans() }}</span>
            </p>
            <p style="font-size:1.5em;">
                <span class="badge badge-secondary">{{ $job->type }}</span>
                <span class="badge badge-secondary">Education:
                    @if($job->education == 0)
                        Not Necessary
                    @else
                        {{ $job->education_level->education }}
                    @endif
                </span>
                <span class="badge badge-secondary">Experience: {{ $job->experience }}</span>
            </p>
            <p>
                <strong>Description: </strong> {{ $job->description }}
            </p>
            <p>
                <strong>Openings: </strong> {{ $job->openings }}
            </p>
            <p>
                <strong>Salary: </strong>
                @if(empty($job->salary) || $job->salary == 0)
                    N/A
                @else
                    {{ '$'.number_format($job->salary,0) }}
                @endif
            </p>
            <p>
                <strong>Company: </strong> {{ $job->company->name }}
            </p>
            <p>
                <strong>Location: </strong>
                @if(!empty($job->company->city) && !empty($job->company->state))
                    {{ $job->company->city }}, {{ $job->company->state }} {{ $job->company->zipcode }}
                @else
                    N/A
                @endif
            </p>
            <p>
                <strong>Contact Email: </strong>
                @if(empty($job->company->contact_email))
                    {{ $job->company->user->email }}
                @else
                    {{ $job->company->contact_email }}
                @endif
            </p>
            <p>
                <strong>Phone: </strong>
                @if(!empty($job->company->phone))
                    {{ $job->company->phone }}
                @else
                    N/A
                @endif
            </p>
            <a href="{{ url($job->id.'/report_job') }}" class="pull-right btn btn-sm btn-danger">Report Job</a>
        </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <a href="{{ URL::previous() }}" class="btn btn-danger btn-block">Back</a>
            </div>
        </div>
        <div class="col-md-6">
            @if(auth()->check() && auth()->user()->user_type == 1)
                @if(count(auth()->user()->type->application(auth()->user()->id,$job->id)) == 0)
                    <div class="form-group">
                        <a href="{{ url('/job/'.$job->id.'/apply') }}" class="btn btn-success btn-block">Apply</a>
                    </div>
                @else
                    <h3><span class="badge badge-success pull-right">You have applied to this Job!</span></h3>
                @endif
            @elseif(!auth()->check())
                <div class="form-group">
                    <a href="{{ url('/login') }}" class="btn btn-success btn-block">Sign up to Apply</a>
                </div>
            @endif
        </div>

    </div>

@endsection
