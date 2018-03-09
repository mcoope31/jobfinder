@extends('layouts.master')

@section('content')

    <h2>Applications - Admin</h2>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                @foreach($applications as $application)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-1">
                            <?php
                            $img_src_png = '/storage/company_images/company'.$application->seeker->user_id.'.png';
                            $img_src_jpg = '/storage/company_images/company'.$application->seeker->user_id.'.jpg';
                            ?>
                            @if(file_exists(public_path($img_src_png)))
                                <img style="width:80px;height:80px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                            @elseif(file_exists(public_path($img_src_jpg)))
                                <img style="width:80px;height:80px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                            @else
                                <img style='width:80px;height:80px;margin:0 auto;display:block;' src='/storage/seeker_images/noimage.png'>
                            @endif
                        </div>
                        <div class="col-md-11">
                            <span class="badge badge-secondary pull-right">Applied: {{$application->created_at->diffForHumans()}}</span>
                            <h5>
                                <p class="mb-0 mt-0"><strong>Seeker: </strong>
                                    <a href="{{ url('/profile/'.$application->seeker_id) }}">{{$application->seeker->name}}</a>
                                </p>
                                <p class="mb-0 mt-1"><strong>Job: </strong>
                                    <a href="{{ url('/job/'.$application->job_id) }}">{{$application->job->title}}</a> 
                                    at 
                                    <em>
                                        <a href="{{ url('/profile/'.$application->job->company->user_id) }}">{{$application->job->company->name }}</a>
                                    </em>
                                </p>
                            </h5>
                            <p class="mb-0">
                                <a href="" class='btn btn-sm btn-danger pull-right'>Remove</a>
                            </p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    {{ $applications->links('partials.pagination') }}
@endsection