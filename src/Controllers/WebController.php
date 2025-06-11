<?php
namespace Leazycms\EArsip\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebController extends Controller
{
public function home(Request $request){
    return to_route('login');
}
}
