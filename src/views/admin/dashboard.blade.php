@extends('cms::backend.layout.app',['title'=>'Dashboard'])
@section('content')
<div class="row ">
  <div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-dashboard"></i> Dashboard</h3>


</div>

@if(earsip_user()->is_operator() || earsip_user()->is_kasubag())

@include('earsip::admin.rekapsurat')

@else




@forelse(notifications()->get_unread_notifications() as $row)
<div class="col-lg-6  ">
  <div class="card mb-3 shadow-sm">
    <div class="card-body">
     

      <h5 class="card-title">{{ $row->title }}</h5>
      <p class="card-text">{{ $row->message }}</p>
      <p class="card-text text-muted"><i class="fa fa-clock"></i> {{ $row->created_at->diffForhumans() }}</p>
      <a href="{{ route('notifreader',$row->id) }}" class="btn btn-sm btn-primary pull-right"> <i class="fa fa-eye"></i> Lihat Selengkapnya</a>
    </div>
  </div>
  </div>
@empty
<div class="col-lg-12">
  <div class="alert alert-warning"><h3> <i class="fa fa-warning"></i> Beluma ada tugas</h3></div>
</div>
@endforelse

@endif
</div>

@endsection
