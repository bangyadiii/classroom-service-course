<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ImageCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            "course_id" => "required|integer",
            "image" => "required|url",
        ];
        $data = $request->all();

        $validated = Validator::make($data, $rules);
        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        Course::findOrFail($request->input("course_id"));

        $newImage = ImageCourse::create($validated);

        return \response()->json([
            "status" => "success",
            "message" => "Image added successfully",
            "data" => $newImage
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $result = ImageCourse::findOrFail($id);

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan image course",
            "data" => $result
        ]);

    }

    public function edit(ImageCourse $imageCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImageCourse $imageCourse)
    {
        $rules = [
            "course_id" => "integer",
            "image" => "url",
        ];
        $data = $request->all();
        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        Course::findOrFail($request->input("course_id"));

        $imageCourse->fill($data);
        $imageCourse->save();

        return \response()->json([
            "status" => "success",
            "message" => "Image updated successfully",
            "data" => $imageCourse
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = ImageCourse::findOrFail($id);
        $data->delete();

        return \response()->json([
            "status" => "success",
            "message" => "Image Course deleted successfully"
        ]);


    }
}
