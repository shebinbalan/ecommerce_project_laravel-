@extends('admin.layouts.app')

@section('style')
<link rel="stylesheet" href="{{url('public/assets/plugins/summernote/summernote-bs4.min.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Product</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                @include('admin.layouts._message')
                    <div class="card card-primary">
                        <form action="" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" required value="{{ old('title', $product->title) }}" placeholder="Title" name="title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SKU<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" required value="{{ old('sku', $product->sku) }}" placeholder="SKU" name="sku">
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Category <span style="color:red;">*</span></label>
                                            <select class="form-control" name="category_id" required id="ChangeCategory">
                                                <option value="">Select a Category</option>
                                                @foreach($getCategory as $value)
                                                <option value="{{ $value->id }}" {{ ($value->id == $product->category_id) ? 'selected' : '' }}>
                                    {{ $value->name }}
                                            </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Sub Category <span style="color:red;">*</span></label>
                                            <select class="form-control" name="sub_category_id" required id="getSubCategory">
                                                <option value="">Select a Category</option>
                                                @foreach($get_sub_category as $value)
                                                <option value="{{ $value->id }}" {{ ($value->id == $product->sub_category_id) ? 'selected' : '' }}>
                                    {{ $value->name }}
                                            </option>
                                                @endforeach
                                            </select>
                                           
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Brand <span style="color:red;">*</span></label>
                                            <select class="form-control" name="brand_id" required>
                                                <option value="">Select a Brand</option>
                                                @foreach($getBrand as $brand)
                                                <option {{ ($product->brand_id==$brand->id) ? 'selected' : '' }} value="{{ $brand->id }}" >
                        {{ $brand->name }}
                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Color<span style="color:red;">*</span></label>
                                            @foreach($getColor as $color)
                                            @php
                                            $checked ='';
                                            @endphp
                                            @foreach($product->getColor as $pcolor)
                                            @if($pcolor->color_id == $color->id)
                                            @php
                                            $checked ='checked';
                                            @endphp
                                            @endif
                                            @endforeach
                                            <div><label><input {{$checked}} type="checkbox" name="color_id[]" value="{{$color->id}}">{{$color->name}}</label></div>
                                                    </option>
                                            @endforeach
                                        </div>
                                    </div> 
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price($)<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" required value="{{!empty($product->price)? $product->price :''}}" placeholder="Price" name="price">
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Old Price($)<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" required value="{{!empty($product->old_price)? $product->old_price :''}}" placeholder="Old Price" name="old_price">
                                        </div>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Size<span style="color:red;">*</span></label>
                                            <div>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Price($)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="AppendSize">
                                                    @php
                                                    $i_s = 1;
                                                @endphp

                                                @foreach ($product->getSize as $size)
                                                    <tr id="DeleteSize_{{$i_s}}">
                                                        <td><input type="text" value="{{$size->name}}" name="size[{{$i_s}}][name]" placeholder="Name" class="form-control"></td>
                                                        <td><input type="text" value="{{$size->price}}" name="size[{{$i_s}}][price]" placeholder="Price" class="form-control"></td>
                                                        <td style="width:200px;">
                                                            <button type="button" id="{{$i_s}}" class="btn btn-danger DeleteSize_">Delete</button>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i_s++;
                                                    @endphp
                                                @endforeach
                                                        <tr>
                                                            <td><input type="text" name="size[100][name]" class="form-control"></td>
                                                            <td><input type="text" name="size[100][price]" class="form-control"></td>
                                                            <td  style="width:200px;"><button type="button" class="btn btn-primary AddSize">Add</button></td>
                                                        </tr>
                                                       <!--  <tr>
                                                            <td><input type="text" name="" class="form-control"></td>
                                                            <td><input type="text" name="" class="form-control"></td>
                                                            <td><button type="button" class="btn btn-danger btn-sm">Delete</button></td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Image</label>
                                     <input type="file" name="image[]" class="form-control" style="padding:5x;" multiple accept="image/*">
                                    </div>
                                </div> 
                            </div>

                                <hr>
                                @if (!empty($product->getImage->count()))
                                <div class="row" id="sortable">
                                    @foreach ($product->getImage as $image)                                
                                    @if (!empty($image->getLogo()))
                                <div class="col-md-1 sortable_image" style="text-align:center;" id="{{$image->id}}">                           
                                  <img style="height: 100px; width: 100px;" src="{{ $image->getLogo() }}">  
                                  <a onclick="return confirm(' Are you sure want to delete?');"  href="{{url('admin/product/image_delete/'.$image->id)}}" style="margin-top: 10px;"class="btn btn-danger btn-sm">Delete</a>                          
                                </div>
                                @endif
                                @endforeach
                                </div> 
                                @endif
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Short Description</label>
                                            <textarea class="form-control" required placeholder="Short Description" name="short_description">{{ old('short_description',$product->short_description) }}</textarea>
                                        </div>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" required placeholder="Description" name="description">{{ old('description',$product->description) }}</textarea>
                                        </div>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Additional Information</label>
                                            <textarea class="form-control" required placeholder="Additional Information" name="additional_info">{{ old('additional_info',$product->additional_info) }}</textarea>
                                        </div>
                                    </div> 
                                </div>  

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Shipping Returns</label>
                                            <textarea class="form-control" required placeholder="Shipping Returns" name="shipping_returns">{{ old('shipping_returns',$product->shipping_returns) }}</textarea>
                                        </div>
                                    </div> 
                                </div>  

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status<span style="color:red;">*</span></label>
                                            <select class="form-control" name="status">
                                                <option {{ old('status',$product->status == 0) ? 'selected' : '' }} value="0">Active</option>
                                                <option {{ old('status',$product->status == 1) ? 'selected' : '' }} value="1">Inactive</option>
                                            </select>
                                        </div>    
                                    </div>  
                                </div>    
                            </div>           
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>             
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="{{url('public/assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{url('public/sorttable/jquery-ui.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#sortable").sortable({
        update: function(event, ui) {
            var photo_id = new Array();
            $('.sortable_image').each(function() {
                var id = $(this).attr('id');
                photo_id.push(id);
            });
            $.ajax({
            type: "POST",
            url: "{{url('admin/product_image_sortable')}}",
            data:{
            "photo_id":photo_id,
            '_token': "{{ csrf_token() }}", 
            },
            dataType:"json",
            success: function(data) {
           

            },

            error: function (data) {
            }

            });
        }
    });
});




     $('.editor').summernote({
        height: 100     
     });
     $('body').delegate('.DeleteSize_', 'click', function(e) {
        var id = $(this).attr('id');
    $('#DeleteSize_' + id).remove();
     });
var i = 101;  // Initialize the variable outside the event handler

$('body').delegate('.AddSize', 'click', function(e) {
    var html = '<tr id="DeleteSize_' + i + '">\n\
<td>\n\
<input type="text" name="size[' + i + '][name]" placeholder="Name" class="form-control">\n\
</td>\n\
<td>\n\
<input type="text" name="size[' + i + '][price]" placeholder="Size" class="form-control">\n\
</td>\n\
<td>\n\
<button type="button" id="' + i + '" class="btn btn-danger DeleteSize">Delete</button>\n\
</td>\n\
</tr>';
    i++;
    $('#AppendSize').append(html);
});

   
$('body').delegate('#ChangeCategory', 'change', function(e) {
var id = $(this).val();

$.ajax({
type: "POST",
url: "{{url('admin/get_sub_category')}}",
data:{
  "id":id,
  '_token': "{{ csrf_token() }}", 
},
dataType:"json",
success: function(data) {
$('#getSubCategory').html(data.html);

},

error: function (data) {
}

});
});
// $(document).on('click', '.DeleteSize_', function(e) {
//     var id = $(this).attr('id');
//     $('#DeleteSize_' + id).remove();
// });
</script>

@endsection
