@extends('layouts.master')

@section('content')

    @include('partials.errors')

    <div class="row">   
        
        <div class="col-sm-6">
            
            <h1>Login</h1>

            <form method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
            </form>
            
        </div>
        <div class="col-sm-6">
            
            <h1>Registration</h1>

            <form method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <div class="form-group">
                    <label for="typeradio" class="mr-5">Type of Account: </label>
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active" style="min-width:150px;">
                            <input type="radio" name="type" id="seekertype" value="seeker" checked> Seeker
                        </label>
                        <label class="btn btn-secondary" style="min-width:150px;">
                            <input type="radio" name="type" id="companytype" value="company"> Company
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
                
            </form>
        </div>
    </div>
@endsection