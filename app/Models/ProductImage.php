<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_name',
        'order_by',
        'image_extension'
     ];
 
   
     public function user()
     {
         return $this->belongsTo(User::class, 'created_by');
     }
    
      public function products()
     {
         return $this->belongsTo(Product::class);
     }
     public function getLogo()
     {
        if(!empty($this->image_name) && file_exists('upload/products/'.$this->image_name))
        {
            return url('upload/products/'.$this->image_name);
        }
        else
        {
            return "";
        }
        
     }

     public function getLogoAttribute()
     {
         if (!empty($this->image_name) && file_exists('upload/products/' . $this->image_name)) {
             return url('upload/products/' . $this->image_name);
         }
         
         return "";
     }
 
     /**
      * Get the sub-category associated with the product.
      */
     
 
     /**
      * Get the brand associated with the product.
      */
  
 
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
