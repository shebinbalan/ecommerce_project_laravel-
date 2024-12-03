<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCategory extends Model
{
    use HasFactory,SoftDeletes;
    //protected $table ="categories";
    protected $fillable = [
        'category_id',
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
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getSingleSlug($slug)
    {
        return self::where('slug','=',$slug)->where('sub_categories.status','=',0)->first();
    }
    static public function getRecord()
    {
        return self::select('sub_categories.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->paginate(50);
    }

    static public function getRecordSubCategory($category_id)
    {
        return self::select('sub_categories.*')
                ->where('sub_categories.category_id','=',$category_id)
                ->orderBy('id','desc')
                ->get();
    }


    public function TotalProduct()
    {
        return $this->hasMany(Product::class,'sub_category_id')
       // ->where('products.is_delete','=',0)
        ->where('products.status','=',0)
        ->count();
    }
}
