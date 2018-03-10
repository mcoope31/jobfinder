@extends('layouts.master')

@section('content')

    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Contact Us</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/contact_sent') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        @include('partials.errors')

                        @if(auth()->check())
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name: </label>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" readonly class="form-control-plaintext" value="{{ auth()->user()->type->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email: </label>
                                <div class="col-md-9">
                                    <input type="text" name="email" id="email" readonly class="form-control-plaintext" value="{{ auth()->user()->email }}">
                                </div>
                            </div>
                        @else
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name: </label>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email: </label>
                                <div class="col-md-9">
                                    <input type="text" name="email" id="email" class="form-control">
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