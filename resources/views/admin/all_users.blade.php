@extends('layouts.master')

@section('content')
    <h2>All Users - Admin</h2>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item">

                        <span class="badge badge-primary badge-pill pull-right">{{ $user->created_at->toDayDateTimeString() }}</span>

                        <h3><span class="badge badge-secondary">
                            @if($user->user_type == 1)
                                Seeker
                            @elseif($user->user_type == 2)
                                Company
                            @else
                                Admin
                            @endif
                            </span>
                            <strong>

                                {{ $user->type->name }}

                            </strong>
                        </h3>
                        
                        <p>{{ $user->email }}</p>
                        
                    </li>
                    
                @endforeach
            </ul>
        </div>
    </div>

    {{ $users->links('partials.pagination') }}

@endsection