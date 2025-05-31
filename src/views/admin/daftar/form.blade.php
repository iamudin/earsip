@extends('cms::backend.layout.app',['title'=>  (str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah') .' Domain' ])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-globe"></i> {{ str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah' }} Domain </h3>
</div>

<div class="col-lg-12 mt-4">
<form action="{{ $data ? route(config('domain.route').'daftar.update',$data->id)  : route(config('domain.route').'daftar.store')}}" method="post" class="form" enctype="multipart/form-data">
    @if($data)
    @method('PUT')
    @endif
    @csrf
    <div class="form-group">
        <label for="">Nama Domain</label>
        <input placeholder="domain.go.id / domain.desa.id" type="text" class="form-control " value="{{ $data->nama ?? null }}"  name="nama">
    </div>
    <div class="form-group">
        <label for="">Name Server</label>
        <div class="row">
            @foreach(['ns1','ns2','ns3','ns4'] as $row)
            <div class="col-lg-3 mb-2">
                <input placeholder="{{ str($row)->upper() }}" type="text" class="form-control form-control-sm" value="{{ $data->$row ?? null }}" name="{{ $row }}">
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="">Atau Menggunkan IP Server</label>
        <input placeholder="192.168.000.111" type="text" class="form-control " value="{{ $data->ipv4 ?? null }}"  name="ipv4">
    </div>
    <div class="form-group">
        <label for="" >Surat Permohonan</label>
        @if($data && $data->surat_permohonan && media_exists($data->surat_permohonan))
        <br>
        <a href="{{ $data->surat_permohonan }}" class="btn btn-info btn-sm">{{ basename($data->surat_permohonan) }}</a>
        <br>
        <br>
        @endif
        <input type="file" class="form-control-file" name="surat_permohonan">
    </div>

    <div class="form-group">
        <button class="pull-right btn btn-md btn-primary">Simpan</button>
    </div>
</form>
</div>
</div>
@endsection
