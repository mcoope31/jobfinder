@extends('layouts.master')

@section('content')

    <h2>Seekers - Admin</h2>
    <hr>
    @if(Session::has('freeze'))
        <div class="alert alert-success">{{ Session::get('freeze') }}</div>
    @elseif(Session::has('delete'))
        <div class="alert alert-warning">{{ Session::get('delete') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                @foreach($seekers as $seeker)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                $img_src_png = '/storage/seeker_images/seeker'.$seeker->user_id.'.png';
                                $img_src_jpg = '/storage/seeker_images/seeker'.$seeker->user_id.'.jpg';
                                ?>
                                @if(file_exists(public_path($img_src_png)))
                                    <img style="width:120px;height:120px;margin:0 auto;display:block;" id='img-upload' src="{{$img_src_png}}?={{ File::lastModified(public_path().'/'.$img_src_png) }}">
                                @elseif(file_exists(public_path($img_src_jpg)))
                                    <img style="width:120px;height:120px;margin:0 auto;display:block;" id='img-upload' src="{{$img_src_jpg}}?={{ File::lastModified(public_path().'/'.$img_src_jpg) }}">
                                @else
                                    <img style='width:120px;height:120px;margin:0 auto;display:block;' src='/storage/seeker_images/noimage.png'>
                                @endif
                            </div>
                            <div class="col-md-5">
                                
                                <h4>{{ $seeker->name }}</h4>
                                <p>
                                <strong>Phone: </strong>{{ $seeker->phone }}<br>
                                <strong>Location: </strong>{{ $seeker->city }}, {{ $seeker->state }} {{ $seeker->zipcode }}<br>
                                <strong>Age: </strong>{{ $seeker->age }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h6>{{ $seeker->user->email }}</h6>
                                <strong>Applications: </strong>{{ count($seeker->applications) }}
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-secondary">{{ $seeker->created_at->toDayDateTimeString() }}</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{ url('/profile/'.$seeker->user_id) }}" class="btn btn-sm btn-primary btn-block mt-1 mb-1">View</a>
                                <div class="btn-group" style="width:100%;" role="group">
                                    @if($seeker->user->status == 0)
                                        <form style="width:50%;" method="POST" action="{{ url('/admin/'.$seeker->user_id.'/freeze') }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-warning" style="width:100%;">Freeze</button>
                                        </form>
                                    @elseif($seeker->user->status == 1)
                                        <form style="width:50%;" method="POST" action="{{ url('/admin/'.$seeker->user_id.'/unfreeze') }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-warning" style="width:100%;">Unfreeze</button>
                                        </form>
                                    @endif
                                    <form style="width:50%;" method="POST" action="{{ url('/admin/'.$seeker->user_id.'/delete_user') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-danger" style="width:100%;">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h5>
                                    @foreach($seeker->seeker_skills as $skill)
                                        <span class="badge badge-primary badge-pill">{{ $skill->skill->name }}</span>
                                    @endforeach
                                </h5>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
 
    {{ $seekers->links('partials.pagination') }}

@endsection