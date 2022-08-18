<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->query("user_id");

        $course = MyCourse::query();

        $res = $course->when($userId, function($query) use ($userId){
            return $query->where("user_id", "=", $userId);
        })->with("course")->get();

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan mycourse",
            "data" => $res
        ]);

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
            "course_id" => "required|integer" ,
            "user_id" => "required|integer"
        ];

        $data = $request->all();

        $validated = Validator::make($data,$rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error" ,
                "message" => $validated->errors()
            ], 400);
        }

        $course = Course::find($request->input("course_id"));

        if(!$course){
            return \response()->json([
                "status" => "error" ,
                "message" => "Course not found."
            ], 400);
        }
        $user = \getUser($request->input("user_id"));

        if($user["status"] === "error"){
            return \response()->json([
                "status" => $user["status"],
                "message" => $user["message"]
            ], $user["http_code"]);
        }
        $courseId =  $request->input("course_id");
        $userId = $request->input("user_id");

        $isExistCourse = MyCourse::where("course_id", "=",$courseId)
                ->where("user_id", "=", $userId)
                ->exist();

        if($isExistCourse){
            return \response()->json([
                "status" => "error" ,
                "message" => "This class has been taken "
            ],  409);
        }

        $mycourse = MyCourse::create($data);
        return \response()->json([
            "status" => "success" ,
            "message" => "Berhasil menambahkan kelas",
            "data" => $mycourse
        ],  200);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function show(MyCourse $myCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function edit(MyCourse $myCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyCourse $myCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyCourse $myCourse)
    {
        //
    }
}
