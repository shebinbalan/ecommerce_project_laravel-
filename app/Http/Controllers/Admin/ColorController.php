<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Auth;
class ColorController extends Controller
{
    public function list()
    {  
        $data['getRecord'] = Color::getRecord();
        $data['header_title'] ="Color";
        return view('admin.color.list',$data);
    }

    public function add()
    {
       $data['header_title'] ="Add New Color";
        return view('admin.color.add',$data);
    }

    public function insert(Request $request)
    {
       
        $color = new Color;
        $color->name =$request->name;
        $color->code =$request->code;
        $color->status =$request->status;
        $color->created_by =Auth::user()->id;
        $color->save();
       return redirect('admin/color/list')->with('success',"Color Successfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = color::getSingle($id);
        $data['header_title'] ="Edit Color";
        return view('admin.color.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $color = Color::getSingle($id);
        $color->name =$request->name;
        $color->code =$request->code;
        $color->status =$request->status;
        $color->save();
       return redirect('admin/color/list')->with('success',"Color Successfully Updated");
    }

    public function delete($id)
    {
        $color = Color::getSingle($id);
        $color->delete();
       // $category->save();
        return redirect()->back()->with('success',"Color Successfully Deleted");
    }

}
