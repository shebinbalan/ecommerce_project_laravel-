<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Auth;
class SubCategoryController extends Controller
{
    public function list()
    {  
        $data['getRecord'] = SubCategory::getRecord();
        $data['header_title'] ="SubCategory";
        return view('admin.sub_category.list',$data);
    }

    public function add()
    {
        $data['getCategory'] = Category::getCategory();
        $data['header_title'] ="Add New SubCategory";
        return view('admin.sub_category.add',$data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:sub_categories',
            
        ]);
        $sub_category = new SubCategory;
        $sub_category->category_id =$request->category_id;
        $sub_category->name =$request->name;
        $sub_category->slug =$request->slug;
        $sub_category->status =$request->status;
        $sub_category->meta_title =$request->meta_title;
        $sub_category->meta_description =$request->meta_description;
        $sub_category->meta_keywords =$request->meta_keywords;
        $sub_category->created_by = Auth::user()->id;
        $sub_category->save();
       return redirect('admin/sub_category/list')->with('success',"Sub Category Successfully Created");
    }

    public function edit($id)
    {
        $data['getCategory'] = Category::getCategory();
        $data['getRecord'] = SubCategory::getSingle($id);
        $data['header_title'] ="Edit Sub Category";
        return view('admin.sub_category.edit',$data);
    }
     public function update(Request $request,$id)
     {
        $request->validate([
            'slug' => 'required|unique:sub_categories,slug,' . $id,
            
        ]);
        $sub_category =SubCategory::getSingle($id);
        $sub_category->category_id =$request->category_id;
        $sub_category->name =$request->name;
        $sub_category->slug =$request->slug;
        $sub_category->status =$request->status;
        $sub_category->meta_title =$request->meta_title;
        $sub_category->meta_description =$request->meta_description;
        $sub_category->meta_keywords =$request->meta_keywords;        
        $sub_category->save();
       return redirect('admin/sub_category/list')->with('success',"Sub Category Successfully Updated");
    }
    public function delete($id)
    {
        $subcategory = SubCategory::getSingle($id);
        $subcategory->delete();
       // $category->save();
        return redirect()->back()->with('success',"Subcategory Successfully Deleted");
    }

    public function get_sub_category(Request $request)
    {
       $category_id =$request->id;
       $get_sub_category = SubCategory::getRecordSubCategory($category_id);
       $html ='';
       $html .='<option value="">Select a Category</option>';
       foreach($get_sub_category as $value)
       {
        $html .='<option value="'.$value->name.'">'.$value->name.'</option>';
       }
       $json['html']=$html;
       echo json_encode($json);
    }

}
