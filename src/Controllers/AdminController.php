<?php
namespace Leazycms\EArsip\Controllers;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Leazycms\EArsip\Models\Arsip;

class AdminController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }
    function pengaturan(){
        admin_only();

        if(request()->isMethod('PUT')){
            $data = request()->validate([
                'url' => 'required|url',
                'logo' => 'required|url',
                'api_url' => 'required|url',
                'api_session' => 'required|string',
            ]);
            rewrite_env([
                'APP_URL_EARSIP' => $data['url'],
                'APP_LOGO_EARSIP' => $data['logo'],
                'WA_SENDER_URL' => $data['api_url'],
                'WA_SENDER_SESSION' => $data['api_session'],
            ]);

            Artisan::call('config:cache');
            return back()->with('success','Pengaturan berhasil disimpan');
        }
        return view('earsip::admin.appconfig');
    }
    function judulPeriode($tanggalMulai, $tanggalAkhir)
    {
        Carbon::setLocale('id');
    
        $mulai = Carbon::parse($tanggalMulai);
        $akhir = Carbon::parse($tanggalAkhir);
    
        // Tukar urutan jika perlu
        if ($mulai->gt($akhir)) {
            [$mulai, $akhir] = [$akhir, $mulai];
        }
    
        // 1. Jika tanggal mulai dan akhir sama
        if ($mulai->eq($akhir)) {
            return $mulai->translatedFormat('j F Y');
        }
    
        // 2. Jika dalam bulan dan tahun yang sama
        if ($mulai->format('Y-m') === $akhir->format('Y-m')) {
    
            // ➕ Cek jika satu bulan penuh (1 sampai akhir bulan)
            if ($mulai->day === 1 && $akhir->day === $mulai->copy()->endOfMonth()->day) {
                return $mulai->translatedFormat('F Y'); // contoh: Juni 2025
            }
    
            // Jika tanggal berbeda tapi masih 1 bulan
            return $mulai->format('j') . ' s/d ' . $akhir->translatedFormat('j F Y');
        }
    
        // 3. Jika tahun sama, tapi bulan beda
        if ($mulai->year === $akhir->year) {
            return $mulai->translatedFormat('j F') . ' s/d ' . $akhir->translatedFormat('j F Y');
        }
    
        // 4. Jika tahun berbeda
        return $mulai->translatedFormat('j F Y') . ' s/d ' . $akhir->translatedFormat('j F Y');
    }
public function index(){
    $data = [

    ];
    if(earsip_user()->is_operator() || earsip_user()->is_kasubag()){
        $data['periode'] = $this->judulPeriode(
            request('tanggal_mulai',date('Y-m-d')),
            request('tanggal_akhir',date('Y-m-d'))
        );
        if(request('cetak_rekap') && request('tanggal_mulai')){
        $query = Arsip::with('user.pejabat', 'disposisis.pejabat')
        ->whereBetween('tanggal_terima', [request('tanggal_mulai',date('Y-m-d')),request('tanggal_akhir',date('Y-m-d'))])
        ->orderBy('nomor_agenda')->get();
        $html = View::make('earsip::pdf.rekap', [
                'rekap' => $query,
                'periode' => $data['periode'],
        ])->render();
            $pdf = PDF::loadHTML($html)->setOption('page-width', '330')->setPaper('a4', 'landscape');
            return $pdf->stream('rekap-'.date('Y-m-d').'.pdf');
            
    }
}
  

    return view('earsip::admin.dashboard',$data);
}

}
