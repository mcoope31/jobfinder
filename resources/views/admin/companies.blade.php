@extends('layouts.master')

@section('content')

    <h2>Companies - Admin</h2>
    <hr>
    @if(Session::has('freeze'))
        <div class="alert alert-success">{{ Session::get('freeze') }}</div>
    @elseif(Session::has('delete'))
        <div class="alert alert-warning">{{ Session::get('delete') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12">
            @foreach($companies as $company)
                <div class="card mb-1">
                    <div class="card-header bg-dark text-white">
                        <span class="badge badge-light pull-right" style="font-size:1em;">Joined: {{ $company->created_at->toDayDateTimeString() }}</span>
                        <h4>
                            <span class="badge badge-light badge-pill mr-1">ID: {{ $company->user_id }}</span>
                            {{ $company->name }}</h4>    
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                $img_src_png = '/storage/company_images/company'.$company->user_id.'.png';
                                $img_src_jpg = '/storage/company_images/company'.$company->user_id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img style="width:120px;height:120px;margin:0 auto;display:block;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img style="width:120px;height:120px;margin:0 auto;display:block;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img style='width:120px;height:120px;margin:0 auto;display:block;' src='/storage/company_images/noimage.png'>
                                @endif
                                <a href="{{ url('/profile/'.$company->user_id) }}" class="btn btn-sm btn-primary btn-block">View Company</a>
                                
                                @if($company->user->status == 0)
                                    <form method="POST" action="{{ url('/admin/'.$company->user_id.'/freeze') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-outline-warning btn-block mt-1 mb-1">Freeze Account</button>
                                    </form>
                                @elseif($company->user->status == 1)
                                    <form method="POST" action="{{ url('/admin/'.$company->user_id.'/unfreeze') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-outline-warning btn-block mt-1 mb-1">Unfreeze Account</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ url('/admin/'.$company->user_id.'/delete_user') }}">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-block">Delete</button>
                                </form>
                            </div>
                            <div class="col-md-10">
                                <h5>
                                    <span class="badge badge-secondary">{{ $company->industry }}</span>
                                    <span class="badge badge-secondary">{{ $company->size }} Employees</span>
                                    <span class="badge badge-secondary">Founded {{ $company->founded }}</span>
                                    <span class="badge badge-secondary">{{ $company->city }}, {{ $company->state }} {{ $company->zipcode }}</span>
                                </h5>
                                <p>{{ $company->description }}</p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Login Email: </strong> {{ $company->user->email }}</p>
                                        <p class="mb-0"><strong>Contact Email: </strong> 
                                            @if(!empty($company->contact_email))
                                                {{ $company->contact_email }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Phone: </strong>{{ $company->phone }}</p>
                                        <p class="mb-0"><strong>Website: </strong>
                                            @if(!empty($company->website))
                                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Job Openings: </strong> {{ count($company->job_openings) }}</p>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            @endforeach
        </div>
    </div>
    {{ $companies->links('partials.pagination') }}
@endsection