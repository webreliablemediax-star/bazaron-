@extends('backend.layouts.master')

@section('title')
Video Manager
@endsection

@section('contents')

<div class="container-fluid">

<h4 class="mb-4">🎬 Video Manager</h4>

{{-- RECENT VIDEOS --}}
<div class="mb-4">
<h5>Recently Uploaded Videos</h5>

<div class="row">

@foreach($videos->take(4) as $video)

<div class="col-md-3 mb-3">

<div class="card">

<video width="100%" controls>
<source src="{{ asset('uploads/videos/'.$video->video) }}" type="video/mp4">
</video>

<div class="card-body text-center">

<p class="mb-1">{{$video->title}}</p>

<form action="{{ route('admin.video.delete',$video->id) }}" method="POST">
@csrf
<button class="btn btn-danger btn-sm">
Delete
</button>
</form>

</div>

</div>

</div>

@endforeach

</div>
</div>


{{-- UPLOAD AREA --}}
<div class="card p-4 mb-4">

<form action="{{ route('admin.video.upload') }}" method="POST" enctype="multipart/form-data">

@csrf

<div class="mb-3">
<label>Video Title</label>
<input type="text" name="title" class="form-control" placeholder="Enter video title">
</div>

<div class="upload-box mb-3">

<input type="file" name="video" class="form-control">

</div>

<button type="submit" class="btn btn-success">
Upload Video
</button>

</form>

</div>


{{-- PREVIOUS VIDEOS --}}
<div>

<h5>Previously Uploaded Videos</h5>

<div class="row">

@foreach($videos as $video)

<div class="col-md-3 mb-3">

<div class="card">

<video width="100%" controls>
<source src="{{ asset('uploads/videos/'.$video->video) }}" type="video/mp4">
</video>

<div class="card-body text-center">

<button type="button"
class="btn btn-success btn-sm mb-2"
onclick="window.open('{{ asset('uploads/videos/'.$video->video) }}')">
Select Video
</button>

<p class="text-muted small">
MP4 | {{$video->size}} KB
</p>

<form action="{{ route('admin.video.delete',$video->id) }}" method="POST">
@csrf
@method('DELETE')
<button class="btn btn-danger btn-sm">
Delete
</button>
</form>

</div>

</div>

</div>

@endforeach

</div>

</div>

</div>

@endsection

<script>
function selectVideo(videoUrl){

    alert("Selected video: " + videoUrl);

    // example: input field me set
    document.getElementById("selected_video").value = videoUrl;

}
</script>