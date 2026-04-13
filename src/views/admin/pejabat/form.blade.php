@extends('cms::backend.layout.app',['title'=>  (str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah') .' Pejabat' ])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal" class="pull-left"> <i class="fa fa-user"></i> {{ str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah' }} Pejabat </h3>
    <a href="{{ earsip_route('pejabat.index') }}" class=" btn btn-danger btn-sm pull-right"> <i class="fa fa-undo"></i> Kembali</a>
</div>

<div class="col-lg-12 mt-3">
<form action="{{ isset($data) ? earsip_route('pejabat.update',$data->id) : earsip_route('pejabat.store')}}" method="post" class="form" enctype="multipart/form-data">
    @if(isset($data))
    @method('PUT')
    @endif
    @csrf
        <small for="">NIP</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data->nip ?? null }}"  name="nip">
        <small for="">Nama</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data->nama ?? null }}"  name="nama">
        <small for="">Jabatan</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data->jabatan ?? null }}"  name="jabatan">
        <small for="">Nohp ( WA Aktif)</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data->nohp ?? null }}"  name="nohp">
        <small for="">Peran</small><br>
       @foreach(['OPERATOR','KASUBAGUMUM','KADIS','SEKRETARIS','KABID','STAFF'] as $row)
        <input  type="radio" value="{{ $row }}"  name="alias_jabatan" {{ $data && $data->alias_jabatan == $row ? 'checked' : '' }}> {{ $row }} <br>
       @endforeach
         <input  type="checkbox" value="1"  name="penerima_disposisi" {{ $data && $data->penerima_disposisi == '1' ? 'checked' : '' }}> Pejabat ini dapat menerima disposisi Kepala Dinas 
         <br>
         @if(!$data || $data && !$data->penerima_disposisi)
         <br>
        <small for="">Atasan</small>

         <select name="atasan_id" id="" class="form-control">
            <option value="">Pilih Atasan</option>
            @foreach($penerima_disposisi as $row)
            <option value="{{ $row->id }}" {{ $data && $data->atasan_id == $row->id ? 'selected' : '' }}>{{ $row->nama }} ({{ $row->alias_jabatan }})</option>
            @endforeach
         </select>
         @endif
         <br>
        <h4>Akun <sup>{!! help('Abaikan jika pejabat tidak butuh login ke Sistem') !!}</sup></h4>
        <small for="">Email</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data?->user->email ?? null }}"  name="email">
        <small for="">Username</small>
        <input  type="text" class="form-control form-control-sm " value="{{ $data?->user->username ?? null }}"  name="username">
        <small for="">Password</small>
        <input  type="password" class="form-control form-control-sm " value=""  name="password">
        <small for="">Status</small><br>
        <input  type="radio"  value="active"  name="status" {{ $data?->user->status=='active' ? 'checked':'' }}> Active<br>
        <input  type="radio"  value="blocked"  name="status"  {{ $data?->user->status=='blocked' ? 'checked':'' }}> Blocked
    <div class="form-group mt-4">
        <button class="pull-right btn btn-md btn-primary">Simpan</button>
    </div>
</form>
</div>
</div>
@push('scripts')
@include('cms::backend.layout.js')
@endpush

@endsection
