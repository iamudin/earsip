<?php

namespace Leazycms\EArsip\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Leazycms\EArsip\Models\Pejabat;
use Leazycms\EArsip\Models\User;

class PejabatController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

        ];
    }
    public function update(Request $request,Pejabat $pejabat)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $pejabat->update(array_merge($request->all(),['penerima_disposisi'=>$request->penerima_disposisi ?? 0]));
        $pejabat->user()->update([
            'username'=>$request->username,
            'password'=>$request->password ? bcrypt($request->password) : $pejabat->user->password,
            'name'=> $request->nama,
            'email'=> $request->email,
            'status'=> $request->status,
            'slug'=> str($request->nama)->slug(),
        ]);
        return redirect(earsip_route('pejabat.edit',$pejabat->id))->with('success','Berhasil di perbarui');
    }
    public function edit(Pejabat $pejabat)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        return view('earsip::admin.pejabat.form', ['data' => $pejabat->load('user'),
            'penerima_disposisi' => Pejabat::wherePenerimaDisposisi(1)->whereNotIn('id',[$pejabat->id])->get(),
        ]);
    }
    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $user = User::create([
            'username'=>$request->username,
            'password'=>bcrypt($request->password),
            'name'=> $request->nama,
            'email'=> $request->email,
            'status'=> 'active',
            'level'=> 'earsip',
            'slug'=> str($request->nama)->slug(),
        ]);
        $create = $user->pejabat()->create(array_merge($request->all(),['penerima_disposisi'=>$request->penerima_disposisi ?? 0]));
        return redirect(earsip_route('pejabat.edit',$create->id))->with('success','Berhasil ditambah');;
    }
    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('earsip::admin.pejabat.form', 
        [
            'data' => null,
            'penerima_disposisi' => Pejabat::wherePenerimaDisposisi(1)->get(),
            ]
    );
    }
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = [];
        return view('earsip::admin.pejabat.index', $data);
    }
    public function datatable(Request $request)
    {
        $data = Pejabat::withWhereHas('user');
        return DataTables::of($data)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {})
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group">';
                $btn .= '<a href="'.earsip_route('pejabat.edit',$row->id).'" class="btn btn-sm btn-warning fa fa-edit"></a>';
                $btn .= '<a href="" class="btn btn-sm btn-danger fa fa-trash-o"></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->addColumn('alias', function ($row) {
                return $row->alias_jabatan;
            })
            ->addColumn('status', function ($row) {
                $status = '<span class="badge badge-'.($row->user->isActive() ? 'success' : 'danger').'"> ACCOUNT IS '.str($row->user->status)->upper().'</span><br>';
                $status .= '<code>Terakhir Login: '.$row->user->last_login_at?->diffForhumans().'</code><br>';
                $status .= '<code>IP : '.$row->user->last_login_ip.'</code>';
                return $status;
            })

            ->rawColumns(['nama', 'action','status','alias'])

            ->toJson();
    }
}
