<?php

namespace Leazycms\EArsip\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EArsipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && !is_main_domain()){
            config(['earsip.user'=> \Leazycms\EArsip\Models\User::with('pejabat')->find(auth()->id())]);
        }
        if(is_main_domain()){
            config(['earsip.route'=>'panel.earsip.']);
            config(['earsip.path_url'=>'earsip']);
        }
          if($request->getHost()==parse_url(config('earsip.url'), PHP_URL_HOST)){
            View::share('logo',config('earsip.logo'));
            View::share('app_name',config('earsip.name'));
        }
        if(auth()->check() && !$request->user()->isAdmin() && $request->user()->level != 'earsip'){
            return to_route($request->user()->level .'.dashboard');
        }
        $response =  $next($request);
        if ($response->headers->get('Content-Type') == 'text/html; charset=UTF-8') {
            $content = $response->getContent();
            $content = preg_replace_callback('/<img\s+([^>]*?)src=["\']([^"\']*?)["\']([^>]*?)>/', function ($matches) {
                $attributes = $matches[1] . 'data-src="' . $matches[2] . '" ' . $matches[3];
                if (strpos($attributes, 'class="') !== false) {
                    $attributes = preg_replace('/class=["\']([^"\']*?)["\']/', 'class="$1 lazyload" ', $attributes);
                } else {
                    $attributes .= ' class="lazyload"';
                }
                return '<img ' . $attributes . ' src="/shimmer.gif">';
            }, $content);

            $content = preg_replace('/\s+/', ' ', $content);
            $response->setContent($content);
        }
        return $response;
    }
}
