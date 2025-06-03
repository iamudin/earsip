<?php
namespace Leazycms\EArsip\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leazycms\FLC\Traits\Fileable;

class Arsip extends Model
{

    use Fileable,SoftDeletes,HasUuids;
    protected $fillable = [
        'surat_dari',
        'hal',
        'tanggal_surat',
        'tanggal_terima',
        'nomor_agenda',
        'file_surat',
        'nomor_surat',
        'sifat',
        'harapan',
        'catatan',
        'paraf_kasubagumum_pada',
        'diteruskan_ke_kadis',
        'user_id'
        ];
        protected $casts = [
            'harapan' => 'array',
            'diteruskan_ke_kadis' => 'datetime',
            'paraf_kasubagumum_pada' => 'datetime',

        ];
    public function user(){
        return $this->belongsTo(User::class);
    }
     public function disposisis(){
        return $this->hasMany(Disposisi::class);
    }
    public function sudah_paraf(){
        return $this->paraf_kasubagumum_pada != null;
    }
    public function sudah_diteruskan_kekadis(){
        return $this->diteruskan_ke_kadis != null;
    }

}
