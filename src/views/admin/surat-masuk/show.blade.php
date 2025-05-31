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
<!-- Card Surat Masuk -->
<div class="card mb-4">
    <div class="card-header text-left"><h4 style="line-height: normal;margin-bottom:0">DISPOSISI SURAT</h4></div>
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
                    <td>{{ $data->tanggal_surat }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table style="width:100%">
                <tr>
                    <td style="width:30%">Diterima Tgl</td>
                    <td style="width:2%">:</td>
                    <td style="68%">{{ $data->tanggal_terima }}</td>
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

                <h4 class="mb-4">Tindak lanjut</h4>
                <form action="{{ earsip_route('surat-masuk.disposisi',$data->id) }}" class="action" method="post">
                    @method('PUT')
                    @csrf
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
                                <li>{{ $row->pejabat->jabatan }} {!! $row->dibaca !!}</li>
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
                            <small class="time">{{ $data->paraf_kasubagumum_pada}}</small>
                            @else

                            @if(earsip_user()->is_kadis())
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
                                  @foreach(['Tanggapan dan Saran','Proses Lebih lanjut','Koordinasi / Konfirmasikan'] as $row)
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
                            <small class="time">{{ $data->paraf_kasubagumum_pada}}</small>
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
                        <small class="time">2025-05-30 08:30</small>
                      </div>
                    </div>
                  </li>



                </ul>
            </form>
          </div>
      </div>

    </div>
  </div>
  <div class="card">
    <div class="card-header bg-danger text-white"> <i class="fa fa-file-pdf-o"></i> FILE SURAT</div>
    <div class="card-body p-0">
        <embed src="{{$data->file_surat}}" type="application/pdf" width="100%" height="600px" />
    </div>
  </div>
        </div>
    </div>
    @push('scripts')
        @include('cms::backend.layout.js')
    @endpush
@endsection
