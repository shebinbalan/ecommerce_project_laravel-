<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
class CategoryController extends Controller
{
    public function list()
    {  
        $data['getRecord'] = Category::getCategory();
        $data['header_title'] ="Category";
        return view('admin.category.list',$data);
    }

    public function add()
    {
       $data['header_title'] ="Add New Category";
        return view('admin.category.add',$data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:categories',
            
        ]);
        $category = new Category;
        $category->name =$request->name;
        $category->slug =$request->slug;
        $category->status =$request->status;
        $category->meta_title =$request->meta_title;
        $category->meta_description =$request->meta_description;
        $category->meta_keywords =$request->meta_keywords;
        $category->created_by =Auth::user()->id;
        $category->save();
       return redirect('admin/category/list')->with('success',"Category Successfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = Category::getSingle($id);
        $data['header_title'] ="Edit Category";
        return view('admin.category.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
       'slug' => 'required|unique:categories,slug,' . $id,
        ]);
        $category = Category::getSingle($id);
        $category->name =$request->name;
        $category->slug =$request->slug;
        $category->status =$request->status;
        $category->meta_title =$request->meta_title;
        $category->meta_description =$request->meta_description;
        $category->meta_keywords =$request->meta_keywords;
        $category->save();
       return redirect('admin/category/list')->with('success',"Category Successfully Updated");
    }

    public function delete($id)
    {
        $category = Category::getSingle($id);
        $category->delete();
       // $category->save();
        return redirect()->back()->with('success',"Category Successfully Deleted");
    }



}
