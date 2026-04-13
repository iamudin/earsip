@extends('cms::backend.layout.app', ['title' => 'Pengaturan'])
@section('content')

        <div class="row">
            <div class="col-lg-12">
                <h3 style="font-weight:normal">
                    <i class="fa fa-gears" aria-hidden="true"></i> Pengaturan
                    <div class="btn-group pull-right">
            @if(!app()->configurationIsCached())

                           <button type="button" onclick="$('.btn-submit').click()" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
                @endif
                        <a href="{{ route('panel.dashboard') }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-undo" aria-hidden="true"></i> Kembali
                        </a>
                    </div>
                </h3>
            </div>
        </div>

        <div class="row mt-4">

         <div class="col-lg-12">
             @include('cms::backend.layout.error')
     
            <form method="POST" action="{{ route('panel.earsip.pengaturan.index') }}" class="form-profile" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                     
                    <label for="">URL Aplikasi</label>
                    <input placeholder="contoh : earsip.domainkamu.com" type="text" class="form-control" name="url" value="{{ config('earsip.url') }}" required>
                </div>
                  <div class="form-group">
                    <label for="">Logo</label>
                    <input placeholder="contoh : https://domainkamu.com/logo.png" type="text" class="form-control" name="logo" value="{{ config('earsip.logo') }}" required>
                </div>
                  <div class="form-group">
                    <label for="">API Whatsapp Endpoint</label>
                    <input placeholder="contoh : https://api.whatsapp.com " type="text" class="form-control" name="api_url" value="{{ config('earsip.api.wa_sender.url') }}" required>
                </div>
                 <div class="form-group">
                    <label for="">Whatsapp Session</label>
                    <input placeholder="contoh : earsip" type="text" class="form-control" name="api_session" value="{{ config('earsip.api.wa_sender.session') }}" required>
                </div>
                @if(config('earsip.api.wa_sender.url') && config('earsip.api.wa_sender.session') )
                <div class="form-group">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalSession">
                        Login Whatsapp
                    </button>
                </div>

                <div class="modal fade" id="modalSession" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        
                        <div class="modal-header">
                          <h5 class="modal-title">QR WhatsApp</h5>
                          <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                          </button>
                        </div>
                  
                        <div class="modal-body text-center">
                            <p id="statusBadge" class="font-weight-bold text-warning">🟡 Waiting QR</p>

    <div id="qrContainer"></div>
                        </div>
                  
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                          <button type="button" class="btn btn-success" id="btnKirimSession">Refresh</button>
                        </div>
                  
                      </div>
                    </div>
                  </div>
                  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
                  <script>
                    let intervalQR = null;
                    
                    document.getElementById('btnKirimSession').addEventListener('click', function () {
                    
                        let session = document.querySelector('input[name="api_session"]').value;
                        let url = document.querySelector('input[name="api_url"]').value;
                    
                        // hentikan loop sebelumnya
                        if (intervalQR) clearInterval(intervalQR);
                    
                        // jalankan pertama kali
                        getQR(url, session);
                    
                    
                    
                    });
                    
                    function setStatus(text, colorClass = 'text-warning') {
                        let el = document.getElementById('statusBadge');
                        el.className = 'font-weight-bold ' + colorClass;
                        el.innerText = text;
                    }
                    
                    function getQR(url, session) {
                    
                        let qrContainer = document.getElementById('qrContainer');
                    
                        fetch(url+'/session/start', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                session: session
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                    
                            // 🟢 jika sudah connected
                            if (data.status && data.status === 'connected') {
                                setStatus('🟢 Connected', 'text-success');
                                qrContainer.innerHTML = '<p>Device sudah terhubung</p>';
                    
                                // stop looping
                                clearInterval(intervalQR);
                                return;
                            }
                    
                            // 🟡 jika ada QR
                            if (data.qr) {
                    
                                setStatus('🟡 Waiting QR', 'text-warning');
                    
                                let qrString = data.qr.split(',')[0];
                    
                                QRCode.toCanvas(qrString, { width: 250 }, function (err, canvas) {
                                    if (err) return;
                    
                                    qrContainer.innerHTML = '';
                                    qrContainer.appendChild(canvas);
                                });
                    
                            } else {
                    
                                setStatus('QR tidak ditemukan, reset session...', 'text-danger');
                    
                                // logout lalu retry
                                logoutSession(url, session, () => {
                                   
                                        getQR(url, session);
                                  
                                });
                    
                            }
                    
                        })
                        .catch(err => {
                            setStatus('Error koneksi...', 'text-danger');
                            console.error(err);
                        });
                    }
                    
                    
                    // 🔥 LOGOUT + CALLBACK
                    function logoutSession(baseUrl, session, callback = null) {
                    
                        let logoutUrl = baseUrl.replace(/\/$/, '') + '/session/logout';
                    
                        fetch(logoutUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                session: session
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            console.log('Logout:', res);
                    
                            if (callback) callback();
                        })
                        .catch(err => {
                            console.error('Logout gagal:', err);
                        });
                    
                    }
                    </script>
                @endif
                <button class="type d-none btn-submit" type="submit" ></button>
            </form>
         </div>
         </div>

@endsection