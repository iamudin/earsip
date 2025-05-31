<?php

if(!function_exists('earsip_user')){
function earsip_user(){
    return config('earsip.user');
}
}
if(!function_exists('earsip_route')){
function earsip_route($route,$data=[]){
    return route(config('earsip.route').$route,$data);
}
}
