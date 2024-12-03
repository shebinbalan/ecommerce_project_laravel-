<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductImage;
use Str;
use Auth;
class ProductController extends Controller
{
    public function list()
    {  
        $data['getRecord'] = Product::getRecord();
        $data['header_title'] ="Product";
        return view('admin.product.list',$data);
    }

    public function add()
    {
       
        $data['header_title'] ="Add New Products";
        return view('admin.product.add',$data);
    }

    // public function insert(Request $request)
    // {

    //     // $request->validate([
    //     //     'slug' => 'required|unique:categories',
            
    //     // ]);
    //     $title = trim($request->title);

    //     $product = new Product;
    //     $product->title = $title ;           
    //     $product->created_by =Auth::user()->id;
    //     $product->save();

    //     $slug = Str::slug($title, "-");  
    //    $checkSlug = Product::checkSlug($slug);
    //     if(empty($checkSlug)){
    //         $product->slug = $slug;
    //     }
    //     else
    //     {
    //         $new_slug = $slug.'-'.$product->id;
    //         $product->slug =  $new_slug;
    //         $product->save();
    //     }
       
    //    return redirect('admin/product/edit'.$product->id);
    // }

    public function insert(Request $request)
{
    // Validate the title if needed
    $title = trim($request->title);

    // Generate slug based on the title
    $slug = Str::slug($title, "-");  

    // Check if the slug is unique
    $checkSlug = Product::where('slug', $slug)->exists();
    
    if ($checkSlug) {
        // If not unique, append the product ID or some other unique identifier
        $slug = $slug . '-' . (Product::max('id') + 1); // Alternatively, use a timestamp or UUID
    }

    // Save the product with the slug
    $product = new Product;
    $product->title = $title;
    $product->slug = $slug;
    $product->created_by = Auth::user()->id;
    $product->save();

    // Redirect to the edit page of the newly created product
    return redirect('admin/product/edit/' . $product->id);
}

    public function edit($product_id)
    {
        $product = Product::getSingle($product_id);
        if(!empty($product))
        {
            $data['getCategory'] = Category::getCategoryActive();
            $data['getBrand'] = Brand::getRecordActive();
            $data['getColor'] = Color::getRecordActive();
            $data['product'] = $product;
            $data['get_sub_category'] =SubCategory::getRecordSubCategory($product->category_id);
            $data['header_title'] ="Edit Product";
        }
        
        return view('admin.product.edit',$data);
    }

    public function update(Request $request,$product_id)
    {
       //dd($request->all());
        $product = Product::getSingle($product_id);
        if(!empty($product))
        {

           
            $product->title = trim($request->title);
            $product->sku = trim($request->sku);
            $product->category_id = trim($request->category_id);
            $product->sub_category_id  = trim($request->sub_category_id);
            $product->brand_id = trim($request->brand_id);
            $product->old_price = trim($request->old_price);
            $product->price = trim($request->price);
            $product->short_description = trim($request->short_description);
            $product->description = trim($request->description);
            $product->additional_info = trim($request->additional_info);
            $product->shipping_returns = trim($request->shipping_returns);
            $product->status = trim($request->status);
            $product->save();

            ProductColor::DeleteRecord($product->id);
            if(!empty($request->color_id))
            {
                foreach($request->color_id as$color_id)
                {
                    $color = new ProductColor;
                    $color->color_id =$color_id;
                    $color->product_id =$product->id;
                    $color->save();

                }
            }
            ProductSize::DeleteRecord($product->id);

            if (!empty($request->size)) {
                foreach ($request->size as $sizeData) {
                    if (!empty($sizeData['name'])) {
                        $productSize = new ProductSize;
                        $productSize->name = $sizeData['name'];
                        $productSize->price = !empty($sizeData['price']) ? $sizeData['price'] : 0;
                        $productSize->product_id = $product->id;
                        $productSize->save();
                    }
                }
            }
            if(!empty($request->file('image')))
            {
                foreach($request->file('image') as $value)
                {
                    if($value->isValid())
                    {
                         $ext = $value->getClientOriginalExtension();
                         $randomStr = $product->id. Str::random(20);
                         $filename = strtolower($randomStr). '.'.$ext;
                         $value->move('upload/products/', $filename);

                         $imageUpload =new ProductImage;
                         $imageUpload->image_name = $filename;
                         $imageUpload->image_extension = $ext;
                         $imageUpload->product_id = $product->id;
                         $imageUpload->save();

                    }
                }
            }
            return redirect()->back()->with('success',"Product Successfully Updated");
        }
        else
        {
            abort(404);
        }
    }

    public function image_delete($id)
    {
        $image = ProductImage::getSingle($id);
        if(!empty($image->getLogo()))
        {
            unlink('upload/products/'.$image->image_name);
        }
        $image->delete();
        return redirect()->back()->with('success',"ProductImage Successfully Deleted");
    }


    public function product_image_sortable(Request $request)
    {
        if(!empty($request->photo_id))
        {
            $i=1;
            foreach($request->photo_id as $photo_id)
            {
                $image = ProductImage::getSingle($photo_id);  
                $image->order_by = $i;
                $image->save();

                $i++;
            }
        }
        $json['success'] =true;
        json_encode($json);
    }

}
