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

                <?php $img_src = '/storage/company_images/company'.Auth::user()->id.'.png'; ?>
                @if(file_exists(public_path($img_src)))
                    <img style="width:250px;height:250px;" id='img-upload' src="{{$img_src}}?={{ File::lastModified(public_path().'/'.$img_src) }}">
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
                    <!--<img id='img-upload'/>-->
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
                    <label for="description" class="col-sm-3 col-form-label">Description: </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="phone" class="col-sm-3 col-form-label">Phone: </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone" id="phone" value='{{ Auth::user()->type->phone }}' required>
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