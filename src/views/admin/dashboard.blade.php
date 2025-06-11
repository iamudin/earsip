@extends('cms::backend.layout.app',['title'=>'Dashboard'])
@section('content')
<div class="row ">
  <div class="col-lg-12 pb-2"><h3 style="font-weight:normal"> <i class="fa fa-dashboard"></i> Dashboard</h3>

  </div>
@if(earsip_user()->pejabat->alias_jabatan=='OPERATOR')

<div title="Klik untuk selengkapnya" onclick="location.href=''" class="pointer col-md-6 col-lg-6">
  <div class="widget-small primary coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
    <div class="info pl-3">
      <p class="mt-2 text-muted">Surat Masuk</p>
      <h2><b>200</b></h2>
    </div>
  </div>

</div>
<div title="Klik untuk selengkapnya" onclick="location.href=''"  class="pointer col-md-6 col-lg-6">
<div class="widget-small info coloured-icon"><i class="icon fa fa-mail-forward fa-3x"></i>
<div class="info pl-3">
  <p class="mt-2 text-muted">Disposisi</p>
  <h2><b>100</b></h2>
</div>
</div>
</div>

@else
</div>

<div class="row">


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
</div>

@endif
@endsection
