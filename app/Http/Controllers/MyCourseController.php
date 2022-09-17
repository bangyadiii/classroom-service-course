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

        $res = $course->when($userId, function ($query) use ($userId) {
            return $query->where("user_id", "=", $userId);
        })->with("course")->get();

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan my course",
            "data" => $res
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPremiumAccess(Request $request)
    {
    }

    public function store(Request $request)
    {
        $rules = [
            "user_id" => "required|integer",
            "course_id" => "required|integer",
        ];

        $data = $request->all();

        $validated = Validator::make($data, $rules);

        if ($validated->fails()) {
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $course = Course::find($request->input("course_id"));

        if (!$course) {
            return \response()->json([
                "status" => "error",
                "message" => "Course not found."
            ], 404);
        }
        $user = \getUser($request->input("user_id"));

        if ($user["status"] === "error") {
            return \response()->json([
                "status" => $user["status"],
                "message" => $user["message"]
            ], $user["http_code"]);
        }
        $courseId =  $request->input("course_id");
        $userId = $request->input("user_id");

        $isExistCourse = MyCourse::where("course_id", "=", $courseId)
            ->where("user_id", "=", $userId)
            ->exists();

        if ($isExistCourse) {
            return \response()->json([
                "status" => "error",
                "message" => "This class has been taken "
            ],  409);
        }

        if ($course["type"] === "premium" && $course["price"] > 0) {
            //
            $res = createOrder([
                "user" => $user["data"],
                "course" => $course
            ]);

            if ($res["status"] === 'error') {
                return \response()->json([
                    "status" => $res["status"],
                    "message" => $res["message"]
                ], $res["http_code"]);
            }

            return \response()->json($res);
        } else {
            $mycourse = MyCourse::create($data);
            return \response()->json([
                "status" => "success",
                "message" => "Berhasil menambahkan kelas",
                "data" => $mycourse
            ],  200);
        }
    }
}
