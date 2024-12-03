<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'sub_category_id',
        'brand_id',
        'old_price',
        'price',
        'short_description',
        'description',
        'additional_info',
        'shipping_returns',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_price' => 'decimal:2',
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Get the category associated with the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
   
     public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sub-category associated with the product.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Get the brand associated with the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getColor()
    {
        return $this->hasMany(ProductColor::class,"product_id");
    }
    public function getSize()
    {
        return $this->hasMany(ProductSize::class,"product_id");
    }
    public function getImage()
    {
        return $this->hasMany(ProductImage::class,"product_id")->orderBy('order_by','asc');
    }

    static public function getImageSingle($product_id)
    {
        return ProductImage::where('product_id', '=', $product_id)->orderBy('order_by','asc')->first();
    }

    static public function checkSlug($slug)
    {
        return self::where('slug','=',$slug)->count();
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        return self::select('products.*')
                //->where('deleted_at','=',0)
                ->orderBy('id','desc')
                ->paginate(50);
    }
    static public function getProduct($category_id ='',$subcategory_id='')
    {
        $return = Product::select('products.*');
        //->where('deleted_at','=',0)
        if(!empty($category_id))
        {
            $return=  $return->where('products.category_id','=',$category_id);
        }
        if(!empty($subcategory_id))
        {
            $return=  $return->where('products.sub_category_id','=',$category_id);

        }
        if(!empty(Request::get('sub_category_id') ))
        {
            $sub_category_id =rtrim(Request::get('sub_category_id'),',');
            $sub_category_id_array = explode(",",$sub_category_id);
            $return=  $return->whereIn('products.sub_category_id',$sub_category_id_array);
        }
        else
        {
            if(!empty(Request::get('old_category_id') ))
            {
                $return=  $return->where('products.category_id','=',Request::get('old_category_id') );
            }
            if(!empty(Request::get('old_sub_category_id') ))
            {
                $return=  $return->where('products.sub_category_id','=',Request::get('old_sub_category_id') );
    
            }
        }
        if(!empty(Request::get('brand_id') ))
        {
            $brand_id =rtrim(Request::get('brand_id'),',');
            $brand_id_array = explode(",",$brand_id);
            $return=  $return->whereIn('products.brand_id',$brand_id_array);
        }

        if(!empty(Request::get('color_id') ))
        {
            $color_id =rtrim(Request::get('color_id'),',');
            $color_id_array = explode(",",$color_id);
            $return=  $return->whereIn('product_colors.color_id',$color_id_array);
        }
        if(!empty(Request::get('start_price')) && !empty(Request::get('end_price')))
        {  
              $start_price = str_replace('$','',Request::get('start_price'));
              $end_price = str_replace('$','',Request::get('end_price'));

              $return=  $return->where('products.price','>=',$start_price );
              $return=  $return->where('products.price','>=',$end_price );
        }

        $return=  $return->orderBy('id','desc')
        ->where('products.status','=',0)
       ->groupBy('products.id')
        
        ->paginate(1);

        return $return;
    }
}
