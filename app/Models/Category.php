<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table ="categories";
    protected $fillable = [
        'name',
        'slug',
        'status',
        'meta_title',
        'meta_description',
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

    static public function getSingleSlug($slug)
    {
        return self::where('slug','=',$slug)->where('categories.status','=',0)->first();
    }
    static public function getCategory()
    {
        return Category::select('categories.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->get();
    }
    static public function getCategoryActive()
    {
        return Category::select('categories.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->get();
    }

    static public function getCategoryMenu()
    {
        return Category::select('categories.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->get();
    }

    public function getSubCategory()
    {
        return $this->hasMany(SubCategory::class,"category_id")->where('sub_categories.status','=',0);
    }
}
