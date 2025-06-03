<?php

namespace Leazycms\EArsip\Controllers;

use Illuminate\Http\Request;
use Leazycms\EArsip\Models\Arsip;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Leazycms\EArsip\Models\Pejabat;

class SuratMasukController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }
    public function create()
    {

        return view('earsip::admin.surat-masuk.form', ['data' => null]);
    }
    public function edit(Arsip $surat_masuk)
    {
        return view('earsip::admin.surat-masuk.form', ['data' => $surat_masuk]);
    }
    public function disposisi(Request $request, Arsip $arsip)
    {
        if ($request->paraf_kasubag && $request->teruskan_ke_kadis) {
            $arsip->update([
                'paraf_kasubagumum_pada' => now(),
                'diteruskan_ke_kadis' => now()
            ]);
            return back()->with('success', 'Surat berhasil di paraf dan diteruskan ke Kepala Dinas');
        }

        if($request->respon_disposisi){
            $arsip->disposisis()->whereBelongsTo(earsip_user()->pejabat)->update([
                'catatan'=>$request->catatan,
                'dibalas_pada'=>now(),
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
                'catatan' => $request->catatatn,
                'harapan' => $request->harapan
            ]);
            foreach ($request->pejabat_id as $row) {
                $arsip->disposisis()->updateOrCreate([
                    'arsip_id' => $arsip->id,
                    'pejabat_id' => $row,
                ]);
            }

            return back()->with('success', 'Surat berhasil di disposisi');
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


        return view(
            'earsip::admin.surat-masuk.show',
            [
                'data' => $surat,
                'pejabat' => Pejabat::whereIn('alias_jabatan', ['SEKRETARIS', 'KABID'])->orderBy('alias_jabatan', 'desc')->get()
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
        }

        return back()->with('success', 'Berhasil disimpan');
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
        $data = Arsip::with('user.pejabat','disposisis.pejabat')->latest();
        if (earsip_user()->pejabat->alias_jabatan == 'KADIS') {
            $data = $data->where('diteruskan_ke_kadis', '!=', null);
        }
        if (in_array(earsip_user()->pejabat->alias_jabatan,['KABID','SEKRETARIS'])) {
            $data = $data->whereHas('disposisis',function($q){
                $q->where('pejabat_id',earsip_user()->pejabat->id);
            });

        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return 'status';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group">';
                $btn .= '<a href="' . earsip_route('surat-masuk.show', $row->id) . '" class="btn btn-sm btn-primary fa fa-eye"></a>';
                $btn .= earsip_user()->pejabat->alias_jabatan == 'OPERATOR' ? '<a href="' . earsip_route('surat-masuk.edit', $row->id) . '" class="btn btn-sm btn-warning fa fa-edit"></a>' : null;
                $btn .= earsip_user()->pejabat->alias_jabatan == 'OPERATOR' ? '<a href="" class="btn btn-sm btn-danger fa fa-trash-o"></a>' : null;
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
}
