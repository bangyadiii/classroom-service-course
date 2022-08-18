<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentors;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::query();
        $q = $request->query("q");
        $status = $request->query("status");

        $courses->when($q, function($query) use ($q){
            return $query->whereRaw("name LIKE '%". strtolower($q). "%'");
        });

        $courses->when($status, function($query) use ($status){
            return $query->where("status", "=", $status);
        });

        return response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan data",
            "data" => $courses->paginate(10)
        ]);

    }


    public function store(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "description" => "required|string",
            "certificate" => "boolean",
            "type" => "required|string|in:free,premium",
            "thumbnail" => "url",
            "price" => "required|integer",
            "status" => "required|in:draft,published",
            "level" => "required|in:all-level,beginner,intermediate,advanced",
            "mentor_id" => "required|integer",
        ];

        $data = $request->all();
        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $mentor  = Mentors::find($request->input("mentor_id"));
        if(!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor not found",
            ], 404);
        }

        $newCourse = Course::create($data);

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil menambahkan course",
            "data" => $newCourse
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $course = Course::with(["chapters.lessons","reviews", "mentor",  "imageCourse"])->findOrFail($id);
        $totalStudent = MyCourse::where("course_id", "=", $course->id)->count();
        $course["total_student"] = $totalStudent;

        return response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data course",
            "data" => $course
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "string",
            "description" => "string",
            "certificate" => "boolean",
            "type" => "string|in:free,premium",
            "thumbnail" => "url",
            "price" => "integer",
            "status" => "in:draft,published",
            "level" => "in:all-level,beginner,intermediate,advanced",
            "mentor_id" => "integer",
        ];

        $data = $request->all();


        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $course = Course::find($id);

        if(!$course){
            return response()->json([
                "status" => "error",
                "message" => "Course not found",
            ], 404);
        }

        $mentor  = Mentors::find($request->input("mentor_id"));
        if(!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor not found",
            ], 404);
        }

        $course->fill($data)->save();

        return \response()->json([
            "status" => "success",
            "message" => "Course has been updated.",
            "data" => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);

        if(!$course){

            return \response()->json([
                "status" => "error",
                "message" => "Course not found",
            ], 404);
        }

        $course->delete();

        return response()->json([
            "status" => "success",
            "message" => "Course deleted"
        ]);
    }
}
