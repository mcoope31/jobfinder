@extends('layouts.master')

@section('content')

    <h3>Job Openings</h3>
    <hr>

    <div class="row">
        <div class="col-md-8">
            <form method="GET" action="{{ url('/job_openings') }}">
                <div class="form-group row">
                    <div class="col-md-4 pr-0">
                        <input type="text" class="form-control" name="search" id="search" value="{{(app('request')->input('search'))}}">
                    </div>
                    <div class="col-md-2 pr-0">
                        <select class="form-control" name="search_field" id="search_field">
                            <option>Title</option>

                        </select>
                    </div>
                    <div class="col-md-4 pr-0">
                        <input type="submit" class="btn btn-primary" value="Search">
                        @if(app('request')->input('search'))
                            <a href="{{ url('/job_openings') }}" class="btn btn-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ url('/job_openings') }}">
                <div class="form-group row">
                    <div class="col-md-5 pr-0">
                        <select class="form-control" id="order" name="order">
                            <option {{(app('request')->input('order')=="Created") ? "selected" : "" }}>Created</option>
                            <option {{(app('request')->input('order')=="Salary") ? "selected" : "" }}>Salary</option>
                            <option {{(app('request')->input('order')=="Openings") ? "selected" : "" }}>Openings</option>
                        </select>
                    </div>    
                    <div class="col-md-4 pr-0">
                        <select class="form-control" id="sort" name="sort">
                            <option {{(app('request')->input('sort')=="Desc") ? "selected" : "" }}>Desc</option>
                            <option {{(app('request')->input('sort')=="Asc") ? "selected" : "" }}>Asc</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-md btn-primary btn-block" value="Sort">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr class="mt-0">
    <div class="row">
        <div class="col-md-12">
            @if(count($jobs)==0)
                <div class="alert alert-danger"><h4><strong>Oops!</strong> There are no job openings to list.</h4></div>
            @endif
            
            @foreach($jobs as $job)
        
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0"><span class="badge badge-light pull-right">{{ $job->created_at->diffForHumans() }}</span>
                        <strong>{{ $job->title }}</strong> at {{ $job->company->name }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                $img_src_png = '/storage/company_images/company'.$job->company_id.'.png';
                                $img_src_jpg = '/storage/company_images/company'.$job->company_id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img style="width:150px;height:150px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img style="width:150px;height:150px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img style='width:150px;height:150px;' src='/storage/company_images/noimage.png'>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <p><strong>Job Description: </strong>{{ $job->description }}</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Job Type: </strong>{{ $job->type }}</p>
                                        
                                        <p><strong>Education: </strong>
                                            @if($job->education == 0)
                                                Not Necessary
                                            @else
                                                {{ $job->education_level->education }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        @if(empty($job->salary))
                                            <p><strong>Salary: </strong>N/A</p>
                                        @else
                                            <p><strong>Salary: </strong>${{ number_format($job->salary,0) }}</p>
                                        @endif
                                        <p><strong>Experience: </strong>{{ $job->experience }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('/job/'.$job->id) }}" class="btn btn-sm btn-primary pull-right">View Job</a>
                        <h5><span class="badge badge-secondary"><strong>Openings: </strong>{{ $job->openings }}</span></h5>
                    </div>
                </div>
                <br>
            @endforeach
        </div>
    </div>

    {{ $jobs->links('partials.pagination') }}

@endsection