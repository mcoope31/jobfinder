@extends('layouts.master')

@section('content')

    <h1>Account</h1>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    Account Details
                    <a href="{{ url('/profile/'.$user_id.'/edit_account') }}" class="btn btn-sm btn-danger pull-right">Edit Account</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <strong class="col-sm-3">Account Email: </strong>

                        <span class="col-sm-9">{{ Auth::user()->email }}</span>

                    </div>
                    <hr>
                    <div class="row">
                        <small class="offset-sm-3"> Want to change your password? Click "Edit Account"</small>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection