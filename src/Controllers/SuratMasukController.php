<?php

namespace Leazycms\EArsip\Controllers;

use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Leazycms\FLC\Models\File;
use Illuminate\Http\UploadedFile;
use Leazycms\EArsip\Models\Arsip;
use Leazycms\EArsip\Jobs\WaSender;
use Illuminate\Support\Facades\Log;
use Leazycms\EArsip\Models\Pejabat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
class SuratMasukController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function merge_pdf($pdf1,$pdf2)
    {

    
        $pdf = new Fpdi();
    
        $files = [
            $pdf1,$pdf2
        ];
        try{

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
    
            for ($page = 1; $page <= $pageCount; $page++) {
                $template = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($template);
    
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($template);
            }
        }
    
        // Output PDF langsung ke browser (tanpa simpan ke disk)
        return $pdf->Output('S'); // 'S' artinya string
    }catch(\Exception $e){
        return $e->getMessage();
    }

    }
    public function arsip_surat($request,$surat)
    {
        $surat = Arsip::with('disposisis.pejabat')->find($surat);
        $penerima = Pejabat::select('id','jabatan')->wherePenerimaDisposisi(1)->orderBy('jabatan','desc')->get();
       $pdf = PDF::loadView('earsip::pdf.disposisi',[
            'data' => $surat,
            'penerima'=>$penerima
        ]);
        try{

        
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $pdfdisposisi = $tempDir.'/disposisi-'.$surat->id.'.pdf';
        $filesurat = Storage::disk('local')->path(File::whereFileName(basename($surat->file_surat))->first()?->file_path);
        file_put_contents($pdfdisposisi,$pdf->output());

        $pdfString = $this->merge_pdf($pdfdisposisi,$filesurat);
        $tmpPath = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tmpPath, $pdfString);
    
        // 3. Buat instance UploadedFile dari file temp
        $uploaded = new UploadedFile(
            $tmpPath,
            'arsip-'.$surat->id.'.pdf',
            'application/pdf',
            null,
            true// Set to true so it's treated as a test file
        );
        $newRequest = Request::createFromBase(new SymfonyRequest(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            ['surat' => $uploaded], // ⬅️ DI SINI FILE DITAMBAHKAN
            $request->server->all(),
            $request->getContent()
        ));
        $fname = $surat->addFile([
            'file' => $newRequest->file('surat'),
            'mime_type'=>['application/pdf'],
            'purpose'=>'Arsip Surat '.$surat->id
        ]);

        $surat->update([
            'file_arsip'=>$fname
        ]);
        unlink($pdfdisposisi);
        return url($fname);
    }catch(\Exception $e){
        return back()->with('warning','Proses gagal '.$e->getMessage());
    }
        
    }
    public function create()
    {

        return view('earsip::admin.surat-masuk.form', ['data' => null, 'noagenda' => Arsip::count() + 1]);
    }
    public function edit(Arsip $surat_masuk)
    {
        return view('earsip::admin.surat-masuk.form', ['data' => $surat_masuk]);
    }
    public function disposisi(Request $request, Arsip $arsip)
    {

        if($request->kirim_wa && $request->pejabat_id){
            $pejabat = Pejabat::find($request->pejabat_id);
            $d = $arsip->disposisis()->updateOrCreate([
                'arsip_id' => $arsip->id,
                'pejabat_id' => earsip_user()->pejabat->id,
            ], [
                'whatsapp_pejabat' => $pejabat->id,
                'teruskan_ke_whatsapp_pada' => now(),
                'catatan' => strip_tags($request->pesan),
            ]);
           $filearsip =  $this->arsip_surat($request,$arsip->id);
            WaSender::dispatch([
                'to' => $pejabat->nohp,
                'text' => "Disposisi surat masuk untuk ditindak lanjuti, klik tautan berikut untuk mengunduh surat :\n". $filearsip,
            ]);
       
            return redirect(earsip_route('surat-masuk.index'))->with('success', 'Surat berhasil diteruskan ke ke '.$pejabat->jabatan);


        }

        if ($request->paraf_kasubag && $request->teruskan_ke_kadis) {
            $arsip->update([
                'paraf_kasubagumum_pada' => now(),
                'diteruskan_ke_kadis' => now()
            ]);
            $pejabat = Pejabat::select('user_id', 'nohp')->whereAliasJabatan('KADIS')->first();

            $notif = $arsip->addNotification([
                'to_user' => $pejabat->user_id,
                'title' => 'Surat Baru NO. ' . $request->nomor_surat,
                'message' => 'Ada surat Terbaru untuk anda',
                'url' => earsip_route('surat-masuk.show', $arsip->id),
            ]);
            WaSender::dispatch([
                'to' => $pejabat->nohp,
                'text' => "Surat masuk dari ".$arsip->surat_dari." untuk di disposisi di link berikut :\n" . $notif,
            ]);
            return redirect(earsip_route('surat-masuk.index'))->with('success', 'Surat berhasil di paraf dan diteruskan ke Kepala Dinas');
        }

        if ($request->respon_disposisi) {
            $arsip->disposisis()->whereBelongsTo(earsip_user()->pejabat)->update([
                'catatan' => $request->catatan,
                'dibalas_pada' => now(),
            ]);
            return back()->with('success', 'Balasan dikirim');
        }
        if ($request->kadis_meneruskan) {
            $request->validate([
                'pejabat_id' => 'array|required',
                'harapan' => 'array|required',
                'catatan' => 'string|max:200|nullable',
            ], [
                'pejabat_id.required' => 'Silahkan ceklis pejabat yang ingin diteruskan',
                'harapan.required' => 'Silahkan ceklis salah satu harapan dipsosisi',
            ]);
            $arsip->update([
                'catatan' => $request->catatan,
                'harapan' => $request->harapan,
                'disposisi_pada' => now(),
            ]);
            $pejabat = Pejabat::whereIn('id', $request->pejabat_id)->get();
            foreach ($request->pejabat_id as $row) {
                $dp = $pejabat->where('id', $row)->first();
            
                $notif = $arsip->addNotification([
                    'to_user' => $dp->user_id,
                    'title' => 'Surat Baru NO. ' . $arsip->nomor_surat,
                    'message' => 'Ada surat Terbaru untuk anda',
                    'url' => earsip_route('surat-masuk.show', $arsip->id),
                ]);
                $arsip->disposisis()->updateOrCreate([
                    'arsip_id' => $arsip->id,
                    'pejabat_id' => $row,
                ]);
                WaSender::dispatch([
                    'to' => $dp->nohp,
                    'text' => "Disposisi Kepala Dinas surat masuk dari ".$arsip->surat_dari.".\n Klik tautan berikut untuk melihat.\n" . $notif,
                ]);
            }

            return redirect(earsip_route('surat-masuk.index'))->with('success', 'Surat berhasil di disposisi');
        }
    }
    public function show($surat)
    {
        $surat = Arsip::with('disposisis.pejabat')->find($surat);
        if (empty($surat)) {
            return redirect(earsip_route('surat-masuk.index'))->with('danger', 'Surat Tidak ditemukan');
        }
        if (!in_array(earsip_user()->pejabat->alias_jabatan, ['KASUBAGUMUM', 'KADIS', 'OPERATOR'])) {
            $cek = $surat->disposisis->where('pejabat_id', earsip_user()->pejabat->id)->first();
            if (empty($cek)) {
                return redirect(earsip_route('surat-masuk.index'))->with('danger', 'Surat Tidak ditemukan');
            }
            if (is_null($cek->dibaca_pada)) {
                $cek->update([
                    'dibaca_pada' => now(),
                ]);
            }
        }

        if(earsip_user()->is_kabid()){
            $staff = Pejabat::whereAtasanId(earsip_user()->pejabat->id)->get();
        }
        $surat->notificationCleaner();

        return view(
            'earsip::admin.surat-masuk.show',
            [
                'data' => $surat,
                'pejabat' => Pejabat::wherePenerimaDisposisi(1)->whereNotIn('alias_jabatan', ['OPERATOR', 'KASUBAG', 'KADIS'])->orderBy('alias_jabatan', 'desc')->get(),
                'staff'=> $staff ?? [],
            ]
        );
    }
    public function store(Request $request)
    {
        $data = earsip_user()->arsips()->create([
            'tanggal_surat' => $request->tanggal_surat,
            'nomor_surat' => $request->nomor_surat,
            'nomor_agenda' => Arsip::count() + 1,
            'surat_dari' => $request->surat_dari,
            'hal' => $request->hal,
            'sifat' => $request->sifat,
            'tanggal_terima' => $request->tanggal_terima,
        ]);
        if ($request->hasFile('file_surat')) {
            $fname = $data->addFile([
                'file' => $request->file('file_surat'),
                'mime_type' => ['application/pdf'],
                'purpose' => 'file-surat-' . $data->id,
            ]);
            $data->update(['file_surat' => $fname]);
            if (!empty($fname)) {

                $pejabat = Pejabat::select('user_id', 'nohp')->whereAliasJabatan('KASUBAGUMUM')->first();
                $notif = $data->addNotification([
                    'to_user' => $pejabat->user_id,
                    'title' => 'Surat Baru NO. ' . $request->nomor_surat,
                    'message' => 'Ada surat Terbaru untuk anda',
                    'url' => earsip_route('surat-masuk.show', $data->id),
                ]);
                WaSender::dispatch([
                    'to' => $pejabat->nohp,
                    'text' => "Surat masuk dari ".$data->surat_dari.".\nKlik tautan berikut untuk melihat\n" . $notif,
                ]);
            }
        }

        return redirect(earsip_route('surat-masuk.index'))->with('success', 'Berhasil dibuat');
    }

    public function update(Request $request, Arsip $surat_masuk)
    {
        $surat_masuk->update([
            'tanggal_surat' => $request->tanggal_surat,
            'nomor_surat' => $request->nomor_surat,
            'nomor_agenda' => Arsip::count() + 1,
            'surat_dari' => $request->surat_dari,
            'hal' => $request->hal,
            'sifat' => $request->sifat,
            'tanggal_terima' => $request->tanggal_terima,
        ]);

        if ($request->hasFile('file_surat')) {
            $fname = $surat_masuk->addFile([
                'file' => $request->file('file_surat'),
                'mime_type' => ['application/pdf'],
                'purpose' => 'file_surat_' . $surat_masuk->id,
            ]);

            $surat_masuk->update(['file_surat' => $fname]);
        }
        return back()->with('success', 'Berhasil di perbaharui');
    }
    public function index()
    {
        $data = [];
        return view('earsip::admin.surat-masuk.index', $data);
    }
    public function datatable(Request $request)
    {
        $user = earsip_user();
        $data = Arsip::with('user.pejabat', 'disposisis.pejabat');
        if(earsip_user()->is_kasubag()){
            if (strpos($request->header('referer'), 'selesai') === false) {
                $data = $data->whereNull('paraf_kasubagumum_pada')->whereNull('diteruskan_ke_kadis')->latest('diteruskan_ke_kadis');
            } else {
                $data = $data->whereNotNull('paraf_kasubagumum_pada')->whereNotNull('diteruskan_ke_kadis');
            }
        }

        if($user->is_operator()){
            if (strpos($request->header('referer'), 'selesai') === false) {
                $data = $data->whereNull('paraf_kasubagumum_pada')->whereNull('diteruskan_ke_kadis')->doesntHave('disposisis');
            } else {
                $data = $data->whereNotNull('paraf_kasubagumum_pada')->whereNotNull('diteruskan_ke_kadis')->latest('diteruskan_ke_kadis');
            }
        }
        if (earsip_user()->is_kadis()) {
            if (strpos($request->header('referer'), 'selesai') === false) {
                $data = $data->whereNotNull('diteruskan_ke_kadis')->doesntHave('disposisis');
            } else {
                $data = $data->whereNotNull('diteruskan_ke_kadis')->whereNotNull('disposisi_pada')->latest('disposisi_pada');
            }
        }

        if (earsip_user()->is_kabid()) {
            if (strpos($request->header('referer'), 'selesai') === false) {
                $data = $data->whereHas('disposisis', function ($q) {
                    $q->where('pejabat_id', earsip_user()->pejabat->id)->whereNull('teruskan_ke_whatsapp_pada');
                });
            }
            else{

            $data = $data->whereHas('disposisis', function ($q) {
                $q->where('pejabat_id', earsip_user()->pejabat->id)->whereNotNull('dibaca_pada')->WhereNotNull('teruskan_ke_whatsapp_pada')->orWhereNotNull('diarsip_pada');
            });
        }
    }
        return DataTables::of($data)

            ->addIndexColumn()
            ->addColumn('status', function ($row) use($user) {
                $status = null;
                if($user->is_kabid()){
                    $up = $row->disposisis->where('pejabat_id', $user->pejabat->id)->first();
                    if ($up?->dibaca_pada && $up?->teruskan_ke_whatsapp_pada) {
                        $status = '<span class="badge badge-success">Sudah Dibaca dan Diteruskan ke Whatsapp</span>';
                    } elseif ($up?->dibaca_pada && !$up?->teruskan_ke_whatsapp_pada) {
                        $status = '<span class="badge badge-success">Sudah Dibaca</span>';
                    }
                   else {
                        $status = '<span class="badge badge-warning">Belum Dibaca</span>';
                    }
                
                } elseif ($user->is_kadis()) {
                    $status = '<span class="badge badge-info">Belum Disposisi</span>';
                    if ($row->disposisi_pada) {
                    $status = '<code>Diteruskan kepada </code><br>';

                        $status .= collect($row->disposisis)->map(function($item) {
                return strtoupper($item->pejabat->jabatan); 
            })
            ->join(', ');
                        $status .= '<br><code>'.$row->disposisi_pada?->diffForHumans().'</code>';
                        
                    }
                } elseif ($user->is_kasubag()) {
                    $status = '<span class="badge badge-info">Belum Diparaf</span>';
                    if ($row->sudah_paraf()) {
                        $status = '<span class="badge badge-success">Sudah Diparaf</span>';
                    }
                } elseif($user->is_operator()) {
                    $status = '<span class="badge badge-warning">Belum Diproses</span>';
                    if ($row->paraf_kasubagumum_pada && $row->diteruskan_ke_kadis) {
                        $status = '<span class="badge badge-success">Sudah Diproses</span>';
                    }
                }else{

                }
                return $status;
            })

            ->addColumn('tanggal_terima', function ($row) {
                return $row->tanggal_terima->format('d F Y');
            })
            ->addColumn('tanggal_surat', function ($row) {
                return $row->tanggal_surat->format('d F Y');
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group">';
                $btn .= '<a href="' . earsip_route('surat-masuk.show', $row->id) . '" class="btn btn-md btn-primary fa fa-eye"></a>';
                $btn .= earsip_user()->pejabat->alias_jabatan == 'OPERATOR' ? '<a href="' . earsip_route('surat-masuk.edit', $row->id) . '" class="btn btn-md btn-warning fa fa-edit"></a>' : null;
                $btn .= earsip_user()->pejabat->alias_jabatan == 'OPERATOR' ? '<a href="" class="btn btn-md btn-danger fa fa-trash-o"></a>' : null;
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
