@extends('cms::backend.layout.app',['title'=>  (str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah') .' Pengelola' ])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-user"></i> {{ str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah' }} Pengelola </h3>
</div>

<div class="col-lg-12 mt-4">
<form action="{{ $data ? route(config('domain.route').'pengelola.update',$data->id)  : route(config('domain.route').'pengelola.store')}}" method="post" class="form" enctype="multipart/form-data">
    @if($data)
    @method('PUT')
    @endif
    @csrf
    <div class="form-group">
        <label for="">NIP</label>
        <input type="text" class="form-control " value="{{ $data->nip ?? null }}"  name="nip">
    </div>
    <div class="form-group">
        <label for="">Nama</label>
        <input type="text" class="form-control" value="{{ $data->nama ?? null }}" name="nama">
    </div>
    <div class="form-group">
        <label for="">Jabatan</label>
        <input type="text" class="form-control " value="{{ $data->jabatan ?? null }}" name="jabatan">
    </div>

    <div class="form-group">
        <label for="">No Hp / WA Aktif</label>
        <input type="number" class="form-control " value="{{ $data->nohp ?? null }}" name="nohp">
    </div>
    <div class="form-group">
        <label for="">Jenis Pengelolaan</label><br>
        <input type="radio" value="Desa" name="jenis_pengelola" @if($data && $data->jenis_pengelola == 'Desa') checked @endif> Desa<br>
        <input type="radio"  value="Badan / Dinas / Kecamatan / Kelurahan" name="jenis_pengelola" @if($data && $data->jenis_pengelola == 'Badan / Dinas / Kecamatan / Kelurahan') checked @endif > Badan / Dinas / Kecamatan / Kelurahan
    </div>

    <div class="form-group">
        <label for="" >Surat Kuasa</label>
        @if($data && $data->surat_kuasa && media_exists($data->surat_kuasa))
        <br>
        <a href="{{ $data->surat_kuasa }}" class="btn btn-info btn-sm">{{ basename($data->surat_kuasa) }}</a>
        <br>
        <br>
        @endif
        <input type="file" class="form-control-file" name="surat_kuasa">
    </div>
    <div class="form-group">
        <label for="">Surat Keterangan Jabatan</label>
        @if($data && $data->surat_keterangan && media_exists($data->surat_keterangan))
        <br>
        <a href="{{ $data->surat_keterangan }}" class="btn btn-info btn-sm">{{ basename($data->surat_keterangan) }}</a>
        <br>
        <br>
        @endif
        <input type="file" class="form-control-file" name="surat_keterangan">
    </div>
    <div class="form-group">
        <button class="pull-right btn btn-md btn-primary">Simpan</button>
    </div>
</form>
</div>
</div>
@endsection
