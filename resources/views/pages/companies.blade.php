@extends('layouts.master')

@section('content')

    <h3>Companies</h3>
    <hr>

    <div class="row">
        <div class="col-md-8">
            <form method="GET" action="{{ url('/companies') }}">
                <div class="form-group row">
                    <div class="col-md-4 pr-0">
                        <input type="text" class="form-control" name="search" id="search" value="{{(app('request')->input('search'))}}">
                    </div>
                    <div class="col-md-2 pr-0">
                        <select class="form-control" name="search_field" id="search_field">
                            <option>Name</option>

                        </select>
                    </div>
                    <div class="col-md-4 pr-0">
                        <input type="submit" class="btn btn-primary" value="Search">
                        @if(app('request')->input('search'))
                            <a href="{{ url('/companies') }}" class="btn btn-danger">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ url('/companies') }}">
                <div class="form-group row">
                    <div class="col-md-5 pr-0">
                        <select class="form-control" id="order" name="order">
                            <option {{(app('request')->input('order')=="Joined") ? "selected" : "" }}>Joined</option>
                            <option {{(app('request')->input('order')=="Founded") ? "selected" : "" }}>Founded</option>
                            <option {{(app('request')->input('order')=="Employees") ? "selected" : "" }}>Employees</option>
                        </select>
                    </div>    
                    <div class="col-md-4 pr-0">
                        <select class="form-control" id="sort" name="sort">
                            <option {{(app('request')->input('sort')=="Desc") ? "selected" : "" }}>Desc</option>
                            <option {{(app('request')->input('sort')=="Asc") ? "selected" : "" }}>Asc</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-primary btn-block" value="Sort">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr class="mt-0">
    <div class="row">
        <div class="col-md-12">
            @if(count($companies)==0)
                <div class="alert alert-danger"><h4><strong>Oops!</strong> There are no companies to list.</h4></div>
            @endif
            @foreach($companies as $company)
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        @if(isset($company->created_at))
                                <!--<h3><span class="badge badge-light pull-right">{{ $company->created_at }}</span>-->
                        @endif
                        <span class="badge badge-light pull-right badge-pill">{{ $company->created_at->diffForHumans() }}</span>
                        
                        <h3 class="pb-0 mb-0"><strong>{{ $company->name }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">

                                <?php
                                $img_src_png = '/storage/company_images/company'.$company->user_id.'.png';
                                $img_src_jpg = '/storage/company_images/company'.$company->user_id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img style="width:120px;height:120px;" src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img style="width:120px;height:120px;" src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img style='width:120px;height:120px;' src='/storage/company_images/noimage.png'>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h3><span class="badge badge-secondary pull-right">{{ $company->city }}, {{ $company->state }}</span>
                                <h5>
                                    <div class="badge badge-secondary">{{$company->industry}}</div>
                                    <div class="badge badge-secondary">Founded {{$company->founded}}</div>
                                    <div class="badge badge-secondary">{{$company->size}} Employees</div>
                                </h5>
                                {{ $company->description }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('/profile/'.$company->user_id) }}" class="btn btn-sm btn-primary pull-right">View Company</a>
                        <a href="{{ url($company->user_id.'/report_user') }}" class="btn btn-sm btn-danger pull-right">Report Company</a>
                        <strong>Job Openings: </strong>
                        @if(Session::has('order') && Session::get('order')=='Job Openings')
                            {{ $company->job_openings }}
                        @else
                            {{ count($company->job_openings) }}
                        @endif
                    </div>
                </div>
                <br>
            @endforeach
        </div>
    </div>

    {{ $companies->links('partials.pagination') }}

@endsection

@section('page_js')

<script>
$("#order").change(function(){
    //var text = $('#order').find(":selected").text();
    //location.href = '?order=asc';
    //$('#order_form').submit();
});
</script>

@endsection