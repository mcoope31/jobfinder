@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Report Job</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/'.$job_id.'/report_job_sent') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        @include('partials.errors')

                        @if(auth()->check())
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name: </label>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" readonly class="form-control-plaintext" value="{{ $info->title }}">
                                </div>
                            </div>
                        @else
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name: </label>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" readonly class="form-control-plaintext" value="{{ $info->title }}">
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="message" class="col-sm-3 col-form-label">Message: </label>
                            <div class="col-md-9">
                                <textarea name="message" id="message" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-md-9">
                                <input type="submit" class="btn btn-primary btn-block" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection