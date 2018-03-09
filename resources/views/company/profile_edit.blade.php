@extends('layouts.master')

@section('page_css')

    <link href="{{ url('css/image_upload.css') }}" rel="stylesheet" type="text/css">

@endsection

@section('content')

    <form method="POST" action="{{ url('/profile/'.Auth::user()->id.'/edit') }}" enctype="multipart/form-data">
    
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('partials.errors')
        
        <div class="row">   

            <div class="col-sm-6">

                <?php
                $img_src_png = '/storage/company_images/company'.Auth::user()->id.'.png';
                $img_src_jpg = '/storage/company_images/company'.Auth::user()->id.'.jpg';
                ?>
                @if(file_exists(public_path($img_src_png)))
                    <img style="width:250px;height:250px;" id='img-upload' src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                @elseif(file_exists(public_path($img_src_jpg)))
                    <img style="width:250px;height:250px;" id='img-upload' src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                @else
                    <img style='width:250px;height:250px;' id='img-upload' src='/storage/company_images/noimage.png'>
                @endif

                <hr>
                <div class="form-group">
                    <label>Upload Image</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-primary btn-file">
                                Browse… <input type="file" id="imgInp" name="image_file">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>

            </div>
            <div class="col-sm-6">
                
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Company Name: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name" value='{{ Auth::user()->type->name }}' required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="industry" class="col-sm-3 col-form-label">Industry: </label>
                    <div class="col-sm-9">
                        <select class="form-control" id="industry" name="industry">
                            <option {{ (auth()->user()->type->industry=="Basic Industries")?'selected':'' }}>Basic Industries</option>
                            <option {{ (auth()->user()->type->industry=="Capital Goods")?'selected':'' }}>Capital Goods</option>
                            <option {{ (auth()->user()->type->industry=="Consumer Durables")?'selected':'' }}>Consumer Durables</option>
                            <option {{ (auth()->user()->type->industry=="Consumer Non-Durables")?'selected':'' }}>Consumer Non-Durables</option>
                            <option {{ (auth()->user()->type->industry=="Consumer Services")?'selected':'' }}>Consumer Services</option>
                            <option {{ (auth()->user()->type->industry=="Energy")?'selected':'' }}>Energy</option>
                            <option {{ (auth()->user()->type->industry=="Finance")?'selected':'' }}>Finance</option>
                            <option {{ (auth()->user()->type->industry=="Healthcare")?'selected':'' }}>Healthcare</option>
                            <option {{ (auth()->user()->type->industry=="Miscellaneous")?'selected':'' }}>Miscellaneous</option>
                            <option {{ (auth()->user()->type->industry=="Public Utilities")?'selected':'' }}>Public Utilities</option>
                            <option {{ (auth()->user()->type->industry=="Technology")?'selected':'' }}>Technology</option>
                            <option {{ (auth()->user()->type->industry=="Transportation")?'selected':'' }}>Transportation</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Description: </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description" id="description" rows="5">{{ auth()->user()->type->description }}
                        </textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="phone" class="col-sm-3 col-form-label">Phone: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone" id="phone" value='{{ Auth::user()->type->phone }}' placeholder="e.g. 555-555-5555" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="contact_email" class="col-sm-3 col-form-label">Contact Email: </label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" name="contact_email" id="contact_email" value='{{ Auth::user()->type->contact_email }}'>
                        <small id="emailHelp" class="form-text text-muted">Email used for contact only. If left blank, login email will be used.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city" class="col-sm-3 col-form-label">City: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="city" id="city" value='{{ Auth::user()->type->city }}' required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="state" class="col-sm-3 col-form-label">State: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="state" id="state" value='{{ Auth::user()->type->state }}' required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="zip" class="col-sm-3 col-form-label">ZipCode: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="zip" id="zip" value='{{ Auth::user()->type->zipcode }}' required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="founded" class="col-sm-3 col-form-label">Year Founded: </label>
                    <div class="col-sm-9">
                        <input type="number" min="1500" max="2018" class="form-control" name="founded" id="founded" value='{{ Auth::user()->type->founded }}' placeholder="e.g. 2001" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="company_size" class="col-sm-3 col-form-label">Company Size: </label>
                    <div class="col-sm-9">
                        <input type="number" min="1" class="form-control" name="company_size" id="company_size" value='{{ Auth::user()->type->size }}' placeholder="e.g. 5" required>
                        <small id="sizeHelp" class="form-text text-muted">Number of employees.</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="website" class="col-sm-3 col-form-label">Website: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="website" id="website" value='{{ Auth::user()->type->website }}' placeholder="e.g. http://www.website.com">
                    </div>
                </div>
            </div>

        </div>
        <hr>
        <div class="row">
        
            <div class="col-md-6">
                <div class="form-group">
                    <a href="{{ url('/profile/'.Auth::user()->id) }}" class="btn btn-danger btn-block">Back</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </div>
            </div>
        </div>
    
    </form>

@endsection

@section('page_js')

    <script src="{{ asset('js/image_upload.js') }}"></script>

@endsection