<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Color;
use App\Models\Brand;
class ProductController extends Controller
{
   public function getCategory($slug,$subslug='')
   {
    $getCategory = Category::getSingleSlug($slug);
    $getSubCategory = SubCategory::getSingleSlug($subslug);
    $data['getColor']= Color::getRecordActive();
    $data['getBrand']= Brand::getRecordActive();
    if(!empty($getCategory) && !empty($getSubCategory))
    {
        $data['meta_title']= $getSubCategory->meta_title;
        $data['meta_keywords']= $getSubCategory->meta_keywords;
        $data['meta_description']= $getSubCategory->meta_description;
        $data['getSubCategory']= $getSubCategory;
        $data['getCategory']= $getCategory;

        $getProduct = Product::getProduct($getCategory->id,$getSubCategory->id);

        $data['getProduct']= $getProduct;

        $data['getSubCategoryFilter']= SubCategory::getRecordSubCategory( $getCategory->id);
        return view('product.list',$data);
    }

     else if(!empty($getCategory))
     {
        $data['getSubCategoryFilter']= SubCategory::getRecordSubCategory( $getCategory->id);
        $data['getCategory']= $getCategory;

        $data['meta_title']= $getCategory->meta_title;
        $data['meta_keywords']= $getCategory->meta_keywords;
        $data['meta_description']= $getCategory->meta_description;

        $getProduct = Product::getProduct($getCategory->id);

        $page ='0';
        if(!empty($getProduct->nextPageUrl())){
         $parse_url =parse_url($getProduct->nextPageUrl());
         if(!empty($parse_url['query']))
         {
            parse_str($parse_url['query'],$get_array);
            $page = !empty($get_array['page']) ? $get_array['page'] : 0;
         }
        }
dd($page);
        $data['getProduct']= $getProduct;


        return view('product.list',$data);
     }
     else
     {
        abort(404);
     }
   
   }


   public function getFilterProductAjax(Request $request)
   {
      $getProduct = Product::getProduct();
      return response()->json([
         "status" =>true,
         "success" =>view("product._list",[
            "getProduct"=>$getProduct,
         ])->render(),
        
         ],200);
   }
}
