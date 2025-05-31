<?php
namespace Leazycms\EArsip\Controllers;
use App\Http\Controllers\Controller;
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
public function index(){
    $data = [

    ];
    return view('earsip::admin.dashboard',$data);
}

}
