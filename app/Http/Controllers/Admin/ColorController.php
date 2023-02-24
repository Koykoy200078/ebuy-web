<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Http\Requests\ColorsFormRequest;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(ColorsFormRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = $request->status == true ? '1':'0';
        Color::create($validatedData);
        return redirect('admin/colors')->with('message', 'Color Added Successfully');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit',  compact('color') );
    }

    public function update(ColorsFormRequest $request, $color_id)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = $request->status == true ? '1':'0';
        Color::find($color_id)->update($validatedData);
        return redirect('admin/colors')->with('message', 'Color Updated Successfully');
    }

    public function destroy($color_id)
    {
        $color = Color::findOrFail($color_id);
        $color->delete();
        return redirect('admin/colors')->with('message', 'Color Deleted Successfully');

    }
}
