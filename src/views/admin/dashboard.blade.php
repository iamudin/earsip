@extends('cms::backend.layout.app',['title'=>'Dashboard'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-dashboard"></i> Dashboard</h3>
</div>

</div>
<div class="row mt-4">
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

</div>
@endsection
