@extends('cms::backend.layout.app',['title'=>'Pengelola Domain'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-user"></i> Pengelola Domain</h3>
</div>

<div class="col-lg-12 mt-4">
<table class="table table-striped table-bordered bg-white">
    <thead>
        <tr>
            <th width="10px">No</th>
            <th>NIP</th>
            <th>NAMA</th>
            <th>JENIS PENGELOLA</th>
            <th>Jabatan</th>
            <th>Nohp</th>
            <th>Lampiran</th>
            <th>Dibuat</th>
            <th>Diupdate</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $k=>$row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->nip }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->jenis_pengelola }}</td>
            <td>{{ $row->nohp }}</td>
            <td>{{ $row->jabatan }}</td>
            <td>
                @if($row->surat_keterangan && media_exists($row->surat_keterangan))
                <a href="{{ $row->surat_keterangan }}" class="btn btn-sm btn-success"><i class="fa fa-file"></i> SK </a>
                @endif
                @if($row->surat_kuasa && media_exists($row->surat_kuasa))
                <a href="{{ $row->surat_kuasa }}" class="btn btn-sm btn-success"><i class="fa fa-file"></i> Surat Kuasa</a>
                @endif
            </td>
            <td>{{ $row->created_at->format('d F Y H:i') }}</td>
            <td>{{ $row->updated_at->format('d F Y H:i') }}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ route(config('domain.route').'pengelola.edit',$row->id) }}" class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" align="center">Belum ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
</div>
@endsection
