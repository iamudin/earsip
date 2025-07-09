@extends('cms::backend.layout.app', ['title' => 'Lihat Surat Masuk'])
@section('content')
    <div class="row">
      <div class="col-lg-12 ">
      <h3 style="font-weight:normal" class="pull-left"> <i class="fa fa-envelope"></i>
      Lihat Surat Masuk </h3>
      <a href="{{ earsip_route('surat-masuk.index') }}" class=" btn btn-danger btn-sm pull-right"> <i
      class="fa fa-undo"></i> Kembali</a>
      </div>

      <div class="col-lg-12 mt-4">
      @include('cms::backend.layout.error')
      <form action="{{ earsip_route('surat-masuk.disposisi', $data->id) }}" class="action" method="post" enctype="multipart/form-data">
      @method('PUT')
      @csrf
    <!-- Card Surat Masuk -->
    <div class="row">
    <div class="col-lg-12">
    <div class="card mb-3">
    <div class="card-header text-left"><h4 style="line-height: normal;margin-bottom:0">DISPOSISI SURAT 
     @if(earsip_user()->is_kasubag() || earsip_user()->is_operator())
      <button class="btn btn-sm btn-primary pull-right" name="cetak_disposisi" value="true"><i class="fa fa-print"></i> Cetak Arsip</button>
      @endif
    </h4></div>
    <div class="card-body">
      <div class="row">
      <div class="col-md-6">
      <table style="width:100%">
      <tr>
      <td style="width:30%">Surat Dari</td>
      <td style="width:2%">:</td>
      <td style="68%">{{ $data->surat_dari }}</td>
      </tr>
      <tr>
      <td>No. Surat</td>
      <td>:</td>
      <td>{{ $data->nomor_surat }}</td>
      </tr>
      <tr>
      <td>Tgl. Surat</td>
      <td>:</td>
      <td>{{ $data->tanggal_surat->translatedFormat('d F Y') }}</td>
      </tr>
      </table>
      </div>
      <div class="col-md-6">
      <table style="width:100%">
      <tr>
      <td style="width:30%">Diterima Tgl</td>
      <td style="width:2%">:</td>
      <td style="68%">{{ $data->tanggal_terima->translatedFormat('d F Y') }}</td>
      </tr>
      <tr>
      <td>No. Agenda</td>
      <td>:</td>
      <td>{{ $data->nomor_agenda }}</td>
      </tr>
      <tr>
      <td>Sifat</td>
      <td>:</td>
      <td>{{ $data->sifat }}</td>
      </tr>
      </table>
      </div>
      <div class="col-lg-12 border-top mt-3 pt-3">
      <p><strong>Hal :</strong><br>
      {{ $data->hal }}
      </p>

      </div>
      <div class="col-lg-12">


      <style>
      .timeline {
      position: relative;
      padding: 1rem 0;
      list-style: none;
      }

      .timeline:before {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      width: 3px;
      background: #4e4e4e;
      left: 30px;
      margin: 0;
      }

      .timeline-item {
      position: relative;
      margin-bottom: 2rem;
      padding-left: 60px;
      }

      .timeline-item:before {
      content: '';
      position: absolute;
      width: 15px;
      height: 15px;
      border-radius: 50%;
      background: #eb282e;
      left: 23px;
      top: 0;
      }

      .timeline-item .time {
      font-size: 0.85rem;
      color: #999;
      }
      </style>


      @if(earsip_user()->is_kasubag() || earsip_user()->is_operator())
      <h4 class="mb-4">Tindak lanjut</h4>

      <ul class="timeline">'
      @if($data->sudah_diteruskan_kekadis())
      <li class="timeline-item">
      <div class="card">
      <div class="card-body">

      @if($data->disposisis->count())
      <h6 class="card-title">Disposisi ke kadis</h6>
      <p class="card-text mb-1">Kadis sudah mendisposisikan surat ke :
      <ul>
      @foreach($data->disposisis as $row)
      <li>
      <strong>{{ $row->pejabat->jabatan }} @if(earsip_user()->pejabat->id == $row->pejabat->id) <sup class="badge badge-danger">Anda</sup> @endif <small class="pull-right text-danger">{{ $row->created_at->diffForhumans() }}</small></strong>
      @if(earsip_user()->pejabat->id == $row->pejabat->id)
      @if($row->belum_dibalas())<br>
      <code>Belum ada balasan</code>
      <br>
      <div class="balas" style="display: none">
      <textarea placeholder="Tulis catatan disini" name="catatan" class="form-control form-control-sm"></textarea>
      <button name="respon_disposisi" value="true" class="btn btn-sm btn-primary mt-2"><i class="fa fa-reply"></i> Kirim Balasan</button>
      </div>
      <span onclick="$('.balas').show();$(this).hide()" class="mt-2 btn btn-sm btn-outline-primary"> <i class="fa fa-pencil"></i> Tulis Balasan</span>
      @else
      <p class="alert alert-info"><i class="fa fa-reply"></i> {{ $row->catatan }} <br><small><i class="fa fa-clock-o "></i> {{ $row->dibalas_pada->diffForhumans() }}</small></p>
      @endif
      @else
      @if(!$row->belum_dibalas())
      <p class="alert alert-info"><small><i class="fa fa-reply"></i>  Balasan : {{ $row->dibalas_pada->diffForhumans() }}</small><br>"{{ $row->catatan }}"</p>
      @else
      <br>
      <code>Belum ada balasan</code>
      @endif
      @endif

      </li>
      @endforeach
      </ul>
      </p>
       <p class="card-text mb-1">Dengan harapan :
      <ul>
      @foreach($data->harapan as $row)
      <li>{{ $row }}</li>
      @endforeach
      </ul>
      </p>
       <p class="card-text mb-1">Catatan :
      <ul>
       <li>{{ $data->catatan ?? '-' }}</li>
      </ul>
      </p>
      <small class="time text-danger">{{ $data->diteruskan_ke_kadis->diffForhumans() }}</small>
      @else

      @if(earsip_user()->is_kadis())
      <h6 class="card-title">Teruskan surat ini kepada :</h6>

      <div class="form-group ">
      @foreach($pejabat as $row)
      <div class="animated-checkbox">
      <label>
      <input  type="checkbox" name="pejabat_id[]" value="{{ $row->id }}">
      <span class="label-text">{{ $row->nama }} ({{ $row->jabatan }})</span>
      </label>
      </div>
      @endforeach
      </div>
      <h6 class="card-title">Dengan Hormat harap :</h6>

      <div class="form-group ">
      @foreach(['Tanggapan dan Saran', 'Proses Lebih lanjut', 'Koordinasi / Konfirmasikan'] as $row)
      <div class="animated-checkbox">
      <label>
      <input type="checkbox" name="harapan[]" value="{{ $row }}">
      <span class="label-text">{{ $row }}</span>
      </label>
      </div>
      @endforeach
      </div>
      <h6 class="card-title">Catatan :</h6>
      <div class="form-group ">
      <textarea name="catatan" class="form-control" style="font-size:15px" id="" rows="3" placeholder="Tuliskan catatan disini.."></textarea>
      </div>
      <button name="kadis_meneruskan" value="1" class="btn btn-sm btn-primary">
      Proses dipsosisi <i class="fa fa-mail-forward"></i>
      </button>
      @else
      <h6 class="card-title"> <i class="fa fa-spinner"></i> Menunggu persetujuan disposisi Kepala Dinas</h6>
      @endif


      @endif
      </div>
      </div>
      </li>
      @endif

      <li class="timeline-item">
      <div class="card">
      <div class="card-body">
      @if($data->sudah_paraf())
       <h6 class="card-title">Paraf Kasubag Umum</h6>
      <p class="card-text mb-1">Kasubag memeriksa dan memberikan paraf persetujuan disposisi.</p>
      <small class="time text-danger">{{ $data->paraf_kasubagumum_pada->diffForhumans()}}</small>
      @else
      @if(earsip_user()->is_kasubag())

      <div class="form-group ">
      <div class="animated-checkbox">
      <label>
      <input type="checkbox" name="paraf_kasubag" value="1" id="parafSurat" onchange="this.checked ? $('#btnTeruskanWrapper').show() : $('#btnTeruskanWrapper').hide() ">
      <span class="label-text">Paraf Surat Masuk</span>
      </label>
      </div>
      </div>

      <div id="btnTeruskanWrapper" style="display: none;">
      <button name="teruskan_ke_kadis" value="1" class="btn btn-sm btn-outline-primary">
      Teruskan ke Kepala Dinas <i class="fa fa-mail-forward"></i>
      </button>
      </div>
      @else
      <h6 class="card-title"> <i class="fa fa-spinner"></i> Menunggu Paraf Kasubag</h6>
      @endif
      @endif
      </div>
      </div>
      </li>
      <li class="timeline-item">
      <div class="card">
      <div class="card-body">
      <h6 class="card-title">Surat Diterima & Diinput</h6>
      <p class="card-text mb-1">Operator menginput surat ke sistem.</p>
      <small class="time text-danger">{{ $data->created_at->diffForhumans() }}</small>
      </div>
      </div>
      </li>



      </ul>
    @endif

      </div>
      </div>

    </div>
    </div>
    </div>
    @if(earsip_user()->is_kabid())
    <div class="col-lg-12">
    <div class="alert alert-warning" style="border:4px dashed brown">
      <i class="fa fa-reply pull-right"></i>
    <p><strong>Harap :</strong><br>
     @foreach($data->harapan as $row)
     - {{ $row }}<br>
     @endforeach
    </p>
    <p><strong>Catatan :</strong><br>
     {{$data->catatan}}
     </p>
    </div>
    </div>
    @endif
    <div class="col-lg-12 mb-3">
    <div class="card">
    <div class="card-header bg-danger text-white"> <i class="fa fa-file-pdf-o"></i> FILE SURAT</div>
    <div class="card-body p-0">
      @if(is_local())
      <iframe src="{{$data->file_surat}}" type="application/pdf" width="100%" height="600px"></iframe>
      @else
      <iframe src="https://docs.google.com/gview?url={{url($data->file_surat)}}&embedded=true" type="application/pdf" width="100%" height="600px"></iframe>

      @endif
      @if(earsip_user()->is_kabid())
      @php
    $teruskan = $data->disposisis->where('pejabat_id', earsip_user()->pejabat->id)->first();
      @endphp
      @if($teruskan->teruskan_ke_whatsapp_pada)
      <div class="p-3">
      <p class="card-text mb-1">Surat ini sudah diteruskan ke whatsapp <b> {{ $teruskan->wa_pejabat->nama }} ({{ $teruskan->wa_pejabat->jabatan }})</b><strong>{{ $teruskan->teruskan_ke_whatsapp_pada->diffForhumans() }}</strong></p>
      <p class="card-text mb-1">Pesan : <strong>{{ $teruskan->catatan }}</strong></p>
      </div>
      @else

       <div class="p-3">
      <div class="form-group">
      <label for="">Teruskan ke whatsapp :</label>
      <select name="pejabat_id" id="" class="form-control form-control-sm form-control-select">
      <option value="">-- Pilih Staff --</option>
      @foreach($staff as $row)
      <option value="{{ $row->id }}">{{ $row->nama }}<br>{{ $row->jabatan }}</option>
      @endforeach
      </select>
       </div>
       <div class="form-group">
      <label for="">Catatan :</label>
      <textarea name="pesan" class="form-control form-control-sm" id="" rows="3" placeholder="Tulis pesan disini.."></textarea>
       </div>
       <div class="form-group">

      <button class="btn btn-sm btn-primary" name="kirim_wa" value="1"><i class="fa fa-send"></i> Kirim ke Whatsapp</button>
       </div>
       </div>
       @endif
    @endif
      @if(earsip_user()->is_kadis())
      <div class="p-3">
      @if($data->disposisis->count())
      <p class="card-text mb-1">Sudah diteruskan kepada :
      <ul>
      @foreach($data->disposisis as $row)
      <li>
      <strong>{{ $row->pejabat->jabatan }}</strong>
      </li>
      @endforeach
      </ul>
      </p>
      <p class="card-text mb-1">Dengan harapan :
      <ul>
      @foreach($data->harapan as $row)
      <li>{{ $row }}</li>
      @endforeach
      </ul>
      </p>
       <p class="card-text mb-1">Catatan :
      <ul>
       <li>{{ $data->catatan ?? '-' }}</li>
      </ul>
      </p>
      <p>
      <button name="perbarui_dipsosisi" value="true" class="btn btn-sm btn-warning" onclick="return confirm('Anda yakin untuk disposisi ulang?')"> <i class="fa fa-edit"></i>  Disposisi Ulang</button>
      </p>
      @else
      <h6 class="card-title">Teruskan surat ini kepada :</h6>

      <div class="form-group ">
      @foreach($pejabat as $row)
      <div class="animated-checkbox">
      <label>
      <input  type="checkbox" name="pejabat_id[]" value="{{ $row->id }}">
      <span class="label-text">{{ $row->jabatan }}</span>
      </label>
      </div>
      @endforeach
      </div>
      <h6 class="card-title">Dengan Hormat harap :</h6>

      <div class="form-group ">
      @foreach(['Tanggapan dan Saran', 'Proses Lebih lanjut', 'Koordinasi / Konfirmasikan'] as $row)
      <div class="animated-checkbox">
      <label>
      <input type="checkbox" name="harapan[]" value="{{ $row }}">
      <span class="label-text">{{ $row }}</span>
      </label>
      </div>
      @endforeach
      </div>
      <h6 class="card-title">Catatan :</h6>
      <div class="form-group ">
      <textarea name="catatan" class="form-control" style="font-size:15px" id="" rows="3" placeholder="Tuliskan catatan disini.."></textarea>
      </div>
      <button name="kadis_meneruskan" value="1" class="btn btn-sm btn-primary">
      Proses dipsosisi <i class="fa fa-mail-forward"></i>
      </button>
      @endif

      </div>
      @endif
    </div>
    </div>
    </div>
    </form>
      </div>
    </div></div>
    @push('scripts')
      @include('cms::backend.layout.js')
    @endpush
@endsection
