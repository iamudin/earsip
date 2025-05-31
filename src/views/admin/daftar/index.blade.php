@extends('cms::backend.layout.app',['title'=>'Daftar Domain'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-globe"></i> Datar Domain</h3>
</div>

<div class="col-lg-12 mt-4">
<table class="table table-striped table-bordered bg-white">
    <thead>
        <tr>
            <th width="10px">No</th>
            <th>DOMAIN</th>
            <th>PENGELOLA</th>
            <th>IP</th>
            <th>NAME SERVER</th>
            <th>Lampiran</th>
            <th>Dibuat</th>
            <th>Diupdate</th>
            <th style="width:10px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $k=>$row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->user->pengelola->nama }}<br><small>{{ $row->user->pengelola->jenis_pengelola }}</small></td>
            <td>{{ $row->ipv4 }}</td>
            <td>
            @foreach(['ns1','ns2','ns3','ns4'] as $ns)
            {{ str($ns)->upper() .' : '.($row->$ns ?? '-') }}<br>
            @endforeach
            </td>
            <td>
                @if($row->surat_permohonan && media_exists($row->surat_permohonan))
                <a href="{{ $row->surat_permohonan }}" class="btn btn-sm btn-success"><i class="fa fa-file"></i> SURAT PERMOHONAN </a>
                @endif

            </td>
            <td>{{ $row->created_at->format('d F Y H:i') }}</td>
            <td>{{ $row->updated_at->format('d F Y H:i') }}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ route(config('domain.route').'daftar.edit',$row->id) }}" class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" align="center">@if(!auth()->user()->isAdmin()) <a href="{{ route(config('domain.route').'daftar.create') }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> TAMBAH DOMAIN</a> @else Belum ada data @endif</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
</div>
@endsection
