<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = new Category;
        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];


        $uploadPath = 'uploads/category/';
        if($request->hasFile('image')){
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;

                $file->move('uploads/category/', $filename);
                $category->image = $uploadPath.$filename;
        }

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_keyword = $validatedData['meta_keyword'];
        $category->meta_description = $validatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';
        $category->save();

        return redirect('admin/category')->with('message', 'Category Added Successfuly');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $category)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string'
            ],
            'slug' => [
                'required',
                'string'
            ],
            'description' => [
                'required',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg'
            ],
            'meta_title' => [
                'required',
                'string'
            ],
            'meta_keyword' => [
                'required',
                'string'

            ],
            'meta_description' => [
                'required',
                'string'

            ],
        ]);



        
        $category = Category::findOrFail($category);

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        
        if($request->hasFile('image')){

                $uploadPath = 'uploads/category/';

                $path = 'uploads/category/'.$category->image;
                if(File::exists($path)){
                    File::delete($path);
                }

                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;

                $file->move('uploads/category/',$filename);
                $category->image = $uploadPath.$filename;
        }
        // else
        // {
        //     $file = $request->file("image");
        //     $path = 'uploads/category/'.$category->image;
        //     $file->move("uploads/category/",$category->image->getClientOriginalName());
        //     $Image                = $file->getClientOriginalName();
        // }

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_keyword = $validatedData['meta_keyword'];
        $category->meta_description = $validatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';
        $category->update();

        return redirect('admin/category')->with('message', 'Category Updated Successfuly');
    }


}
