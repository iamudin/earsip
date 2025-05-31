<?php
namespace Leazycms\EArsip\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disposisi extends Model
{

    use SoftDeletes,HasUuids;
    protected $fillable = [
        'user_id',
        'pejabat_id',
        'arsip_id',
        'dibaca_pada',
        'diarsip_pada',
        'teruskan_ke_whatsapp_pada'
        ];
    public function pejabat(){
        return $this->belongsTo(Pejabat::class);
    }
    public function arsip(){
        return $this->belongsTo(Arsip::class);
    }
      public function getDibacaAttribute($key)
      {
        return $this->dibaca_pada != null ? '<code>( dilihat pada '.$this->dibaca_pada.' <i class="fa fa-check text-success"></i>)</code>': null;
    }
      public function user(){
        return $this->belongsTo(User::class);
    }
}
