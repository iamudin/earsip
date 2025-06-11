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
        'dibalas_pada',
        'catatan',
        'balasan',
        'whatsapp_pejabat',
        'teruskan_ke_whatsapp_pada'
        ];
            protected $casts = [
            'dibalas_pada' => 'datetime',
            'diarsip_pada' => 'datetime',
            'teruskan_ke_whatsapp_pada' => 'datetime',
        ];
    public function pejabat(){
        return $this->belongsTo(Pejabat::class);
    }
    public function arsip(){
        return $this->belongsTo(Arsip::class);
    }
    public function wa_pejabat(){
        return $this->belongsTo(Pejabat::class, 'whatsapp_pejabat');
    }
      public function getDibacaAttribute($key)
      {
        return $this->dibaca_pada != null ? '<code>( dilihat pada '.$this->dibaca_pada.' <i class="fa fa-check text-success"></i>)</code>': null;
    }
    public function belum_dibalas(){
        return $this->dibalas_pada == null;
    }
      public function user(){
        return $this->belongsTo(User::class);
    }
}
