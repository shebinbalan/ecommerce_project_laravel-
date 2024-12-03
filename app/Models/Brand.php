<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Brand extends Model
{
    use HasFactory, SoftDeletes;
  //protected $table ="brands";
    protected $fillable = [
        'name',
        'slug',
        'status',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

   

    static public function getSingle($id)
    {
        return self::find($id);
    }
    static public function getRecord()
    {
        return Brand::select('brands.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->get();
    }

    static public function getRecordActive()
    {
        return Brand::select('brands.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->get();
    }
}
