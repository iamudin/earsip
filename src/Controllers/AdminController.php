<?php
namespace Leazycms\EArsip\Controllers;
use Carbon\Carbon;
use Leazycms\EArsip\Models\Arsip;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
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
        $query = Arsip::with('user.pejabat', 'disposisis.pejabat')->orderBy('nomor_agenda')->get();
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
