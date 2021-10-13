@extends('layouts.admin')
@section('content')
@section('overall_rating_select','active') 
<h5 class="text-left pl-3">Edit Overall Rating</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('overall-rating.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
    @if($overallRating)
    <form action="{{route('overall-rating.update',$overallRating->id)}}" method="post">
        @csrf
        @method('put')
        <div class="form-group">
            <label>Grade</label>
            <input type="text" name="grade" class="form-control" value="{{$overallRating->grade}}" readonly>
            @error('grade')
                <span class="text-danger" role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label>Min Score</label>
            <input type="text" name="min_score" class="form-control" value="{{$overallRating->min_score}}">
            @error('min_score')
                <span class="text-danger" role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label>Performance</label>
            <input type="text" name="performance" class="form-control" value="{{$overallRating->performance}}">
            @error('performance')
                <span class="text-danger" role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" class="form-control">{{$overallRating->message}}</textarea>
            @error('message')
                <span class="text-danger" role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror
        </div>
        <div class="text-left">
            <button type="submit" class="btn btn-success">Update</button>
        </div>

    </form> 
    @endif          

</div>

@endsection
