<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Auth;
class BrandController extends Controller
{
    public function list()
    {  
        $data['getRecord'] = Brand::getRecord();
        $data['header_title'] ="Brand";
        return view('admin.brand.list',$data);
    }

    public function add()
    {
       $data['header_title'] ="Add New Brand";
        return view('admin.brand.add',$data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:brands',
            
        ]);
        $brand = new Brand;
        $brand->name =$request->name;
        $brand->slug =$request->slug;
        $brand->status =$request->status;
        $brand->meta_title =$request->meta_title;
        $brand->meta_description =$request->meta_description;
        $brand->meta_keywords =$request->meta_keywords;
        $brand->created_by =Auth::user()->id;
        $brand->save();
       return redirect('admin/brand/list')->with('success',"Brand Successfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = Brand::getSingle($id);
        $data['header_title'] ="Edit Brand";
        return view('admin.brand.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
       'slug' => 'required|unique:brands,slug,' . $id,
        ]);
        $brand = Brand::getSingle($id);
        $brand->name =$request->name;
        $brand->slug =$request->slug;
        $brand->status =$request->status;
        $brand->meta_title =$request->meta_title;
        $brand->meta_description =$request->meta_description;
        $brand->meta_keywords =$request->meta_keywords;
        $brand->save();
       return redirect('admin/brand/list')->with('success',"Brand Successfully Updated");
    }

    public function delete($id)
    {
        $brand = Brand::getSingle($id);
        $brand->delete();
       // $category->save();
        return redirect()->back()->with('success',"Brand Successfully Deleted");
    }



}
