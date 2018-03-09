@extends('layouts.master')

@section('content')
    <h1>Seeker Dashboard</h1>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>Info</strong>
                </div>
                <div class="card-body">
                    <p><strong>Applications Submitted: </strong>{{ count(auth()->user()->type->applications) }}</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    @if(count(auth()->user()->type->applications)>0)
                        <a href="/applications" class="btn btn-sm btn-primary pull-right">View All</a>
                    @endif
                    <strong>Your Recent Applications</strong>
                </div>
                <div class="card-body">
                    @if(count(auth()->user()->type->applications)==0)
                        <div class="alert alert-warning">You have not applied to any jobs yet!</div>
                    @endif
                    <ul class="list-group">
                        @foreach(auth()->user()->type->applications as $application)
                            <li class="list-group-item">
                                <strong>{{ $application->job->title }}</strong> at {{ $application->job->company->name }}
                                <span class="badge badge-secondary badge-pill pull-right">{{ $application->created_at->diffForHumans() }}</span>
                            </li>
                        
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>Suggested Jobs</strong>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                    @foreach($suggestedJobs as $key => $val)
                        <?php $job = App\JobOpening::find($key) ?>
                        <?php $app = auth()->user()->type->application(auth()->user()->id,$job->id) ?>
                        @if(isset($app) && count($app)==0)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php
                                        $img_src_png = '/storage/company_images/company'.$job->company->user_id.'.png';
                                        $img_src_jpg = '/storage/company_images/company'.$job->company->user_id.'.jpg';
                                        ?>
                                        @if(file_exists(public_path($img_src_png)))
                                            <img style="width:80px;height:80px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                        @elseif(file_exists(public_path($img_src_jpg)))
                                            <img style="width:80px;height:80px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                        @else
                                            <img style='width:80px;height:80px;' src='/storage/company_images/noimage.png'>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <strong>{{ $job->title }}</strong>
                                        <a href="{{ url('/job/'.$job->id) }}" class="btn btn-primary btn-sm pull-right">View</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="badge badge-pill badge-secondary">Total Match: {{ $val[0] }}%</span>
                                        <span class="badge badge-pill badge-secondary">Skills: {{ $val[4] }}%</span>
                                        <span class="badge badge-pill badge-secondary">Skills Matched: {{ $val[1] }}</span>
                                        <span class="badge badge-pill badge-secondary">Meets Education:
                                            @if($val[2] == 0)
                                                No
                                            @else
                                                Yes
                                            @endif
                                        </span>
                                        <span class="badge badge-pill badge-secondary">Meets Experience:
                                            @if($val[3] == 0)
                                                No
                                            @else
                                                Yes
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection