@extends('cms::backend.layout.app',['title'=>  (str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah') .' Invoice' ])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-dollar"></i> {{ str_contains(URL::full(),'edit') ? 'Edit' : 'Tambah' }} Invoice </h3>
</div>

<div class="col-lg-12 mt-4">
<form action="{{ $data ? route(config('domain.route').'invoice.update',$data->id)  : route(config('domain.route').'invoice.store')}}" method="post" class="form" enctype="multipart/form-data">
    @if($data)
    @method('PUT')
    @endif
    @csrf
    <div class="form-group">
        <label for="">Nama Domain</label>
        @if($data)
        <input  disabled class="form-control " placeholder="{{ $data->domain->nama }}">
        <input type="hidden" name="domain_id" value="{{ $data->domain->id }}">
        @else
        <select name="domain_id" id="" class="form-control" required>
            <option value="">--pilih domain--</option>
            @foreach($domains as $domain)
           <option value="{{ $domain->id }}">{{ $domain->nama }}</option>
            @endforeach
        </select>
        @endif
    </div>
    <div class="form-group">
        <label for="">Perpanjangan tahun</label>
        <input  type="text" class="form-control " value="{{ $data->perpanjangan_tahun ?? null }}"  name="perpanjangan_tahun" placeholder="Contoh : 2024,2025,2026">
    </div>
    <div class="form-group">
        <label for="">Tanggal Invoice</label>
        <input  type="date" class="form-control " value="{{ $data->tanggal_invoice ?? null }}"  name="tanggal_invoice" required>
    </div>
    <div class="form-group">
        <label for="" >File Invoice</label>
        @if($data && $data->file_invoice && media_exists($data->file_invoice))
        <br>
        <a href="{{ $data->file_invoice }}" class="btn btn-info btn-sm">{{ basename($data->file_invoice) }}</a>
        <br>
        @endif
        @if(auth()->user()->isAdmin() && $data && !$data->bukti_confirmed || !$data)
        <br>

        <input type="file" class="form-control-file" name="file_invoice">
        @endif
    </div>
    <div class="form-group">
        <label for="" >Bukti Pembayaran Invoice</label>
        @if($data && $data->bukti_pembayaran && media_exists($data->bukti_pembayaran))
        <br>
        <a href="{{ $data->bukti_pembayaran }}" class="btn btn-info btn-sm">{{ basename($data->bukti_pembayaran) }}</a>
        <br>
        <br>
        @if(auth()->user()->isAdmin() &&  $data && !$data->bukti_confirmed)
        <input type="checkbox" name="bukti_confirmed" {{ $data && $data->bukti_confirmed ? 'checked': '' }}> Tandai sebagai sudah lunas
        @endif
        @else
        <div class="alert alert-warning">Belum ada bukti Pembayaran di upload</div>
        @endif
        @if(!auth()->user()->isAdmin() && !$data->bukti_confirmed)
        <input type="file" class="form-control-file" name="bukti_pembayaran">
        @endif
        @if($data && $data->bukti_confirmed && !auth()->user()->isAdmin())
        <div class="alert alert-success">Pembayaran sudah dikonfirmasi. Mohon tunggu 1x24 Jam untuk proses aktivasi domain <b> {{ $data->domain->nama }}</b> oleh domain.go.id</div>
        @endif
    </div>
    @if(!$data || $data && !$data->bukti_confirmed)
    <div class="form-group">
        <button class="pull-right btn btn-md btn-primary">Simpan</button>
    </div>
    @endif
</form>
</div>
</div>
@endsection
