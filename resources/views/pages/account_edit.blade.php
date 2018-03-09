@extends('layouts.master')

@section('content')

    <h1>Edit Account</h1>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    Account Details
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/profile/'.Auth::user()->id.'/edit_account_email') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        @include('partials.errors')

                        <p>
                            <strong>Change Email</strong>
                        </p>
                        <hr>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">Account Email</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email" id="email" value='{{ Auth::user()->email }}'>
                            </div>
                        </div>
                        <div class="col-md-3 offset-9">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Update Email</button>
                            </div>
                        </div>
                    </form>
                    <form method="POST" action="{{ url('/profile/'.Auth::user()->id.'/edit_account_password') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <p>
                            <strong>Change Password</strong>
                        </p>
                        <hr>
                        <input type="text" class="form-control text-hide" name="email" id="email" value='{{ Auth::user()->email }}'>
                        <div class="form-group row">
                            <label for="current_password" class="col-sm-4 col-form-label">Current Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="current_password" id="current_password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_pw" class="col-sm-4 col-form-label">New Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="new_password" id="new_password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_pw_confirmation" class="col-sm-4 col-form-label">Confirm New Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-md-3 offset-md-9">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Update Password</button>
                            </div>
                        </div>

                        <div class="form-group row">

                        </div>

                        <div class="form-group row">

                        </div>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="form-group">
                                <a href="{{ url('/profile/'.$user_id.'/account') }}" class="btn btn-danger btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection