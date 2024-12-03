<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'product_id',
       'color_id'
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
   
     public function products()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the sub-category associated with the product.
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the brand associated with the product.
     */
       static public function checkSlug($slug)
    {
        return self::where('slug','=',$slug)->count();
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }
    static public function  DeleteRecord($product_id)
    {
        self::where('product_id','=',$product_id)->delete();
    }
  
   
    static public function getRecord()
    {
        return self::select('products.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->paginate(50);
    }
}
