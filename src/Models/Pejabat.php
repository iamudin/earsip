<?php
namespace Leazycms\EArsip\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leazycms\FLC\Traits\Fileable;

class Pejabat extends Model
{

    use Fileable,SoftDeletes,HasUuids;
    protected $fillable = [
        'nama',
        'nip',
        'nohp',
        'jabatan',
        'atasan_id',
        'alias_jabatan',
        'penerima_disposisi',
        'urutan'
        ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function staff(){
        return $this->hasMany(Pejabat::class,'atasan_id');
    }
  public function disposisis(){
        return $this->hasMany(Disposisi::class);
    }
}
