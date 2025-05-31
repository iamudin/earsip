@extends('cms::backend.layout.app', ['title' => (str_contains(URL::full(), 'edit') ? 'Edit' : 'Tambah') . ' Pejabat'])
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3 style="font-weight:normal" class="pull-left"> <i class="fa fa-envelope"></i>
                {{ str_contains(URL::full(), 'edit') ? 'Edit' : 'Tambah' }} Surat Masuk </h3>
            <a href="{{ earsip_route('surat-masuk.index') }}" class=" btn btn-danger btn-sm pull-right"> <i
                    class="fa fa-undo"></i> Kembali</a>
        </div>

        <div class="col-lg-12 mt-4">
            <form class="form-horizontal" method="post"
                action="{{ isset($data) ? earsip_route('surat-masuk.update', $data->id) : earsip_route('surat-masuk.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($data)
                    @method('PUT')
                @endisset
                <div class="row">
                    <div class="col-lg-6">
                        <div class="tile">
                            <h3 class="tile-title">
                                Info Surat
                            </h3>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Nomor</label>
                                <div class="col-md-9">
                                    <input class="form-control form-control-sm" name="nomor_surat" type="text"
                                        placeholder="Nomor Surat"
                                        value="{{ $data->nomor_surat??null }}"
                                        >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Asal</label>
                                <div class="col-md-9">
                                    <input name="surat_dari" class="form-control form-control-sm" type="text"
                                        placeholder="Instansi / Organisasi Pengirim"
                                        value="{{ $data->surat_dari??null }}"
                                        >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Tanggal</label>
                                <div class="col-md-9">
                                    <input name="tanggal_surat" class="form-control form-control-sm" type="date"
                                        placeholder="Enter email address"
                                        value="{{ $data->tanggal_surat??null }}"
                                        >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Hal</label>
                                <div class="col-md-9">
                                    <textarea name="hal" class="form-control form-control-sm" placeholder="Hal surat">{{ $data->hal??null }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="tile">
                            <h3 class="tile-title">
                                Penerimaan
                            </h3>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Tgl Terima</label>
                                <div class="col-md-9">
                                    <input class="form-control col-md-12" name="tanggal_terima" type="date"
                                        placeholder="Enter email address"
                                        value="{{ $data->tanggal_terima??null }}"
                                        >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">No Agenda</label>
                                <div class="col-md-9">


                                    <span style="font-size:20px;font-weight: bold">@isset($data) {{ $data->nomor_agenda ??null }} @else 1  @endisset</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Sifat</label>
                                <div class="col-md-9">
                                    @foreach (['Sangat Segera', 'Segera', 'Rahasia'] as $row)
                                        <input type="radio" value="{{ str($row)->upper() }}" name="sifat" {{ $data && $data->sifat == str($row)->upper() ? 'checked' : null  }}>
                                        {{ str($row)->upper() }} &nbsp;&nbsp;&nbsp;
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">File Surat</label>
                                <div class="col-md-9">
                                    <input accept="application/pdf" class="form-control-sm" name="file_surat"
                                        type="file" >
                                    @if($data && $data->file_surat && media_exists($data->file_surat))
                                    <br>
                                    <a href="{{ $data->file_surat }}" class="btn btn-sm btn-outline-danger"> <i class="fa fa-file-pdf-o "></i> LIHAT SURAT</a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <button class="btn btn-sm btn-primary pull-right"> <i class="fa fa-save"></i> Simpan Data</button>
            </form>
        </div>
    </div>
    @push('scripts')
        @include('cms::backend.layout.js')
    @endpush
@endsection
