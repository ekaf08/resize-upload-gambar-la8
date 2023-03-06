<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class ImageController extends Controller
{
    public function index()
    {
        return view('form.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = $request->file('image')->getClientOriginalName() . '.' . $image->extension();
        $input['original'] = 'ori-' . $request->file('image')->getClientOriginalName() . '.' . $image->extension();

        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->path());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $input['imagename']);

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);

        return back()->with('success', 'Image Upload Successful')->with('imageName', $input['imagename']);
    }
}
