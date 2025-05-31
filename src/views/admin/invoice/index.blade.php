@extends('cms::backend.layout.app',['title'=>'Daftar Invoice'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-dollar"></i> Daftar Invoice</h3>
</div>

<div class="col-lg-12 mt-4">
<table class="table table-striped table-bordered bg-white">
    <thead>
        <tr>
            <th width="10px">No</th>
            <th>Tanggal</th>
            <th>Domain</th>
            <th>Periode</th>
            <th>Lampiran</th>
            <th>Dibuat</th>
            <th>Diupdate</th>
            <th>Status</th>
            <th style="width:10px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $k=>$row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->tanggal_invoice }}</td>
            <td>{{ $row->domain->nama }}</td>
            <td>{{ $row->perpanjangan_tahun }}</td>
            <td>
                @if($row->file_invoice && media_exists($row->file_invoice))
                <a href="{{ $row->file_invoice }}" class="btn btn-sm btn-danger"><i class="fa fa-file"></i> INVOICE</a>
                @endif
                @if($row->bukti_pembayaran && media_exists($row->bukti_pembayaran))
                <a href="{{ $row->bukti_pembayaran }}" class="btn btn-sm btn-success"><i class="fa fa-file"></i> BUKTI BAYAR</a>
                @endif

                @if($row->surat_permohonan && media_exists($row->surat_permohonan))
                <a href="{{ $row->surat_permohonan }}" class="btn btn-sm btn-success"><i class="fa fa-file"></i> SURAT PERMOHONAN </a>
                @endif

            </td>

            <td>{{ $row->created_at->format('d F Y H:i') }}</td>
            <td>{{ $row->updated_at->format('d F Y H:i') }}</td>
            <td>
                @if($row->bukti_confirmed && $row->bukti_pembayaran && media_exists($row->bukti_pembayaran))
                <span class="badge badge-success">Lunas</span>

                @else
                <span class="badge badge-danger">Belum bayar</span>
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <a href="{{ route(config('domain.route').'invoice.edit',$row->id) }}" class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a>
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
    @if(!auth()->user()->isAdmin() && !$data)
    <tfoot>
        <tr>
            <td colspan="9" align="center">Belum ada data</td>
        </tr>
    </tfoot>
    @endif
    @if(auth()->user()->isAdmin())

    <tfoot>
        <tr>
            <td colspan="9" align="center"><a href="{{ route(config('domain.route').'invoice.create') }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> TAMBAH INVOICE</a></td>
        </tr>
    </tfoot>
    @endif
</table>
</div>
</div>
@endsection
