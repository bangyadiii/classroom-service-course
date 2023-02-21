<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageCourse\ImageCourseCreateRequest;
use App\Http\Requests\ImageCourse\ImageCourseUpdateRequest;
use App\Models\Course;
use App\Models\ImageCourse;
use App\Traits\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    use ResponseFormatter;


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageCourseCreateRequest $request)
    {
        Course::findOrFail($request->input("course_id"));

        $result = \postCourseImage(["image" => $request->images]);
        if ($result['meta']["status"] === 'error') {
            \abort(Response::HTTP_NOT_FOUND, "Course not found");
        }

        $newImage = ImageCourse::create($request->validated());

        return $this->success(201, "Image added successfully", $newImage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ImageCourse::findOrFail($id);

        return $this->success(200, "Get image course succcessful", $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function update(ImageCourseUpdateRequest $request, ImageCourse $imageCourse)
    {
        Course::findOrFail($request->input("course_id"));

        $imageCourse->fill($request->validated());
        $imageCourse->save();

        return $this->success(200, "Update image course succcessful", $imageCourse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageCourse  $imageCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ImageCourse::findOrFail($id);
        $data->delete();

        return $this->success(200, "Image Course deleted successfully");
    }
}
